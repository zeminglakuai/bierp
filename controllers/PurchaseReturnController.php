<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\PurchaseGoods;
use app\common\models\Purchase;
use app\common\models\PurchaseReturn;
use app\common\models\PurchaseReturnGoods;
use app\common\models\Goods;
use app\common\models\Supplier;
use app\common\models\Store;
use app\common\models\OtherExportOrder;
use app\common\models\OtherExportOrderGoods;

use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class PurchaseReturnController extends BaseController
{
    public $page_title = '采购退货单';
    public $title_model = 'app\common\models\PurchaseReturn';
    public $detail_model = 'app\common\models\PurchaseReturnGoods';
    public $status_label = 'purchase_return_status';
    
    public function beforeAction($action)
    {
      parent::beforeAction($action);

      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','admit','insert-goods','delete-goods','update-goods-label'];
      $admit_allow_arr = ['edit','view'];

      $scope_model = 'app\common\models\PurchaseReturn';
      $status_label = 'purchase_return_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }

    //删除
    public function actionDelete($id){
      $PurchaseReturn = PurchaseReturn::findone($id);
      $PurchaseReturn->delete();
      message::result_json(1,'删除成功');
    }

    //添加
    public function actionInsert(){
      $post_data = Yii::$app->request->post('PurchaseReturn');

      $Purchase = Purchase::findone($post_data['purchase_id']);
      $PurchaseGoods = PurchaseGoods::find()->where(['order_id'=>$post_data['purchase_id']])->all();
      if ($Purchase && $PurchaseGoods) {
        //检查是不是已经生成退货单
        $PurchaseReturn = PurchaseReturn::find()->where(['purchase_id'=>$post_data['purchase_id']])->one();
        if ($PurchaseReturn) {
          message::result_json(2,'采购退货单已经存在');
        }
        //新建退货单
        $PurchaseReturn = new PurchaseReturn;
        $PurchaseReturn->supplier_id = $Purchase->supplier_id;
        $PurchaseReturn->supplier_name = $Purchase->supplier_name;
        $PurchaseReturn->purchase_return_status = 0;
        $PurchaseReturn->purchase_id = $Purchase->id;
        $PurchaseReturn->purchase_sn = $Purchase->order_sn;        
        $PurchaseReturn->order_sn = Common_fun::create_sn('app\common\models\PurchaseReturn',5);
        $PurchaseReturn->save(false);

        $insert_goods_list = [];
        foreach ($PurchaseGoods as $key => $value) {
          $insert_goods_list[$key] = ['order_id'=>$PurchaseReturn->id,
                                      'goods_id'=>$value->goods_id,
                                      'goods_name'=>$value->goods_name,
                                      'goods_sn'=>$value->goods_sn,
                                      'isbn'=>$value->isbn,
                                      'market_price'=>$value->market_price,
                                      'purchase_price'=>$value->sale_price,
                                      'number'=>$value->number,
                                      'return_number'=>0,                                      
                                      ];
        }

        $insert_title = ['order_id','goods_id','goods_name','goods_sn','isbn','market_price','purchase_price','number','return_number'];
        $res= Yii::$app->db->createCommand()->batchInsert(PurchaseReturnGoods::tableName(), $insert_title, $insert_goods_list)->execute();

        message::result_json(1,'添加成功');

      }else{
        message::result_json(2,'单据不存在');
      }
    }

    public function actionView($id){
        $PurchaseReturn = PurchaseReturn::findone($id);
        return $this->render('view', ['purchase_return'=>$PurchaseReturn,'id'=>$id]);
    }

    public function actionEdit($id){
      $this->layout = 'empty';
      $PurchaseReturn = PurchaseReturn::findone($id);
      return $this->render('edit', ['purchase_return'=>$PurchaseReturn,'id'=>$id]);
    }

    public function actionUpdate($id){
      $PurchaseReturn = PurchaseReturn::findone($id);
      if ($PurchaseReturn) {
        $PurchaseReturn->load(Yii::$app->request->post());
 

        $Purchase->save(false);
        Message::result_json(1,'编辑成功');
      }else{
        Message::result_json(2,'编辑失败');
      }

    }

    public function actionAdmit($id,$process_status){
      //修改订单状态
      $PurchaseReturn = PurchaseReturn::findone($id);
      if ($PurchaseReturn->purchase_return_status >= 1) {
          Message::result_json(2,'单据已复核!');
      }

      //检查供货商是不是存在
      if ($PurchaseReturn->supplier_id > 0) {
      }else{
        Message::result_json(2,'请登记供货商!');
      }
 
      //检查商品进货价 和 数量 是不是齐备
      $order_goods = PurchaseReturnGoods::find()->where(['order_id'=>$id])->all();
      if (!$order_goods) {
        Message::result_json(2,'商品不存在，不能复核！');
      }

      foreach ($order_goods as $key => $value) {
        if ($value->purchase_price <= 0 || $value->number <= 0) {
          Message::result_json(2,$value->goods_name.'信息不完整！');
          break;
        }

        if ($value->return_number > $value->number) {
          Message::result_json(2,$value->goods_name.'退货数大于进货数');
          break;
        }

      }
  
      $admit_result = $this->base_admit($PurchaseReturn,'purchase_return_status',$process_status);
      if ($admit_result['error'] > 2) {
        Message::result_json(2,$admit_result['message']);
      }

      if ($admit_result['error'] == 1) {
        $this->CreateOtherExport($id);
        Message::result_json(1,'复核并生成其他出库单成功');
      }

      Message::result_json(1,'复核成功');

    }

    //生成出库单
    private function CreateOtherExport($id){

      $PurchaseReturn = PurchaseReturn::findone($id);
      $purchase_goods = PurchaseReturnGoods::find()->where(['order_id'=>$id])->all();
 
      $transaction = Yii::$app->db->beginTransaction();
 
      //新建出库单
      $ExportOrder = new OtherExportOrder();
      $ExportOrder->store_id = $PurchaseReturn->store_id;
      $ExportOrder->store_name = $PurchaseReturn->store_name;
      $ExportOrder->order_sn = Common_fun::create_sn('app\common\models\OtherImportOrder',5);
      $ExportOrder->save(false);

      foreach ($purchase_goods as $key => $value) {
        $purchase_goods_arr[$value->goods_id]['order_id'] = $ExportOrder->id; 
        $purchase_goods_arr[$value->goods_id]['goods_id'] = $value->goods_id;
        $purchase_goods_arr[$value->goods_id]['goods_name'] = $value->goods_name;
        $purchase_goods_arr[$value->goods_id]['goods_sn'] = $value->goods_sn;
        $purchase_goods_arr[$value->goods_id]['isbn'] = $value->isbn;
        $purchase_goods_arr[$value->goods_id]['market_price'] = $value->market_price;
        $purchase_goods_arr[$value->goods_id]['number'] = $value->return_number;
      }

      if ($purchase_goods_arr) {
          $goods_title =['order_id','goods_id','goods_name','goods_sn','isbn','market_price','number'];//数据键
          $res= Yii::$app->db->createCommand()->batchInsert(OtherExportOrderGoods::tableName(), $goods_title, $purchase_goods_arr)->execute();
          //提交事务
          $transaction->commit();
      }else{
        $transaction->rollback();
        message::result_json(2,'没有商品记录');
      }
    }

    public function actionInsertGoods(){
      $goods_id = Yii::$app->request->get('goods_id',0);
      $order_id = Yii::$app->request->get('order_id',0);
      $search_data = Yii::$app->request->get('search_data',0);

      if (!$order_id) {
        message::result_json(2,'数据错误');
      }else{
        $order = Purchase::findone($order_id);
      }

      $add_goods_error = [];
      if ($goods_id) {
          if (is_array($goods_id)) {
            foreach ($goods_id as $key => $value) {
              $goods = Goods::findone($value);
              if ($goods) {
                //插入数据
                $PurchaseGoods = new PurchaseGoods();
                $add_goods_result = $PurchaseGoods->AddGoods($order_id,$goods);
                if (!$add_goods_result) {
                    $add_goods_error[] = $PurchaseGoods->add_goods_error;
                }
              }
            }
            if (count($add_goods_error) > 0) {
              message::result_json(1,$add_goods_error);
            }else{
              message::result_json(1,'添加成功');
            }

          }else{
            $goods = Goods::findone($goods_id);
            //插入数据
            $PurchaseGoods = new PurchaseGoods();
            $add_goods_result = $PurchaseGoods->AddGoods($order_id,$goods);

            if (!$add_goods_result) {
                $add_goods_error = $PurchaseGoods->add_goods_error;
            }

            if (count($add_goods_error) > 0) {
              message::result_json(2,$add_goods_error);
            }else{
              message::result_json(1,'添加成功');
            }
          }
      }

      if ($search_data) {

      }

      message::result_json(2,'数据错误');
    }
    public function actionDeleteGoods($id){
        $data_id = Yii::$app->request->get('data_id');
        $SellOrderGoods = SellOrderGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        $SellOrderGoods->delete();

        message::result_json(1,'删除成功');
    }

    public function actionUpdateGoodsLabel(){
        $data_type  = trim(Yii::$app->request->get('data_type'));
        $value  = trim(Yii::$app->request->get('value'));
        $id  = Yii::$app->request->get('id');
        $data_id  = Yii::$app->request->get('data_id');

        $PurchaseReturnGoods = PurchaseReturnGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        if($PurchaseReturnGoods){
          $PurchaseReturnGoods->$data_type = $value;
          $PurchaseReturnGoods->save(false);
          message::result_json(1,'chengg',$value);
        }else{
          message::result_json(2,'没有此记录');
        }
    }


}
