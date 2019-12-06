<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\CustomOrderGoods;
use app\common\models\CustomOrder;
use app\common\models\Custom;
use app\common\models\Goods;
use app\common\models\Stock;
use app\common\models\SellOrderGoods;
use app\common\models\SellOrder;
use app\common\models\Store;
use app\common\models\SellOrderReturnGoods;
use app\common\models\SellOrderReturn;

use app\common\models\SupplierGoods;
use app\common\models\Supplier;

use app\common\models\OtherImportOrder;
use app\common\models\OtherImportOrderGoods;
use app\common\config\lang_value_config;

use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class SellOrderReturnController extends BaseController
{

    public $page_title = '销售退换';
    public $title_model = 'app\common\models\SellOrderReturn';
    public $detail_model = 'app\common\models\SellOrderReturnGoods';
    public $status_label = 'sell_order_return_status';
    
    public function beforeAction($action)
    {
      parent::beforeAction($action);
      //检查当前单据用户是不是有操作权限

      $need_privi_arr = ['delete','edit','admit','insert-goods','delete-goods'];
      $admit_allow_arr = ['edit','view'];
      $scope_model = 'app\common\models\SellOrderReturn';
      $status_label = 'sell_order_return_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }

    //删除
    public function actionDelete($id){
      $SellOrderReturn = SellOrderReturn::findone($id);
      $SellOrderReturn->delete();
      $SellOrderReturnGoods = SellOrderReturnGoods::deleteall(['order_id'=>$id]);
      message::result_json(1,'删除成功');
    }

    //添加
    public function actionInsert(){
      $post_data = Yii::$app->request->post('SellOrderReturn');

      $sell_order = SellOrder::findone($post_data['sell_order_id']);
      //检查销售单是不是已经走完审批流程
      if (!$sell_order || $sell_order->status_done <> 1) {
        message::result_json(2,'销售单还未审核完毕');
      }
      $sell_order_goods = SellOrderGoods::find()->where(['order_id'=>$post_data['sell_order_id']])->all();
      if (!$sell_order_goods) {
        message::result_json(2,'销售单下没有商品');
      }
      if ($sell_order && $sell_order_goods) {
        //检查是不是已经生成退货单
        // $SellOrderReturn = SellOrderReturn::find()->where(['sell_order_id'=>$post_data['sell_order_id']])->one();
        // if ($SellOrderReturn) {
        //   message::result_json(2,'退货单已经存在');
        // }
        //新建退货单
        $sell_order_return = new SellOrderReturn;
        $sell_order_return->custom_id = $sell_order->custom_id;
        $sell_order_return->custom_name = $sell_order->custom_name;
        $sell_order_return->sell_order_return_status = 0;
        $sell_order_return->sell_order_id = $sell_order->id;
        $sell_order_return->sell_order_sn = $sell_order->order_sn;  
        $sell_order_return->store_id = $sell_order->store_id;
        $sell_order_return->store_name = $sell_order->store_name;

        $sell_order_return->order_sn = Common_fun::create_sn('app\common\models\SellOrderReturn',5);
        $sell_order_return->save(false);

        $insert_goods_list = [];
        foreach ($sell_order_goods as $key => $value) {
          $insert_goods_list[$key] = ['order_id'=>$sell_order_return->id,
                                      'goods_id'=>$value->goods_id,
                                      'goods_name'=>$value->goods_name,
                                      'goods_sn'=>$value->goods_sn,
                                      'isbn'=>$value->isbn,
                                      'market_price'=>$value->market_price,
                                      'sale_price'=>$value->sale_price,
                                      'return_price'=>$value->sale_price,
                                      'number'=>$value->number,
                                      'return_number'=>$value->number,                                      
                                      ];
        }

        $insert_title = ['order_id','goods_id','goods_name','goods_sn','isbn','market_price','sale_price','return_price','number','return_number'];
        $res= Yii::$app->db->createCommand()->batchInsert(SellOrderReturnGoods::tableName(), $insert_title, $insert_goods_list)->execute();

        message::result_json(1,'添加成功');
      }else{
        message::result_json(2,'单据不存在');
      }
    }

    public function actionEdit($id){
        $this->layout = 'empty';
        $SellOrderReturn = SellOrderReturn::findone($id); 
        return $this->render('edit', ['sell_order_return'=>$SellOrderReturn,'id'=>$id]);
    }

    public function actionUpdate($id){
        $SellOrderReturn = SellOrderReturn::findone($id);
        $SellOrderReturn->load(Yii::$app->request->post());
        if ($SellOrderReturn->store_id) {
          $store = Store::findone($SellOrderReturn->store_id);
          $SellOrderReturn->store_name = $store->store_name;
        }

        unset($SellOrderReturn->custom_id);
        unset($SellOrderReturn->custom_name);
        unset($SellOrderReturn->sell_order_id);
        unset($SellOrderReturn->sell_order_sn);
        $SellOrderReturn->save(false); 
        message::result_json(1,'编辑成功');
    }

    public function actionView($id){
        $SellOrderReturn = SellOrderReturn::findone($id);
        return $this->render('view', ['sell_order_return'=>$SellOrderReturn,'id'=>$id]);
    }

    public function actionAdmit($id){
      //修改订单状态
      $SellOrderReturn = SellOrderReturn::findone($id);
      if ($SellOrderReturn->sell_order_return_status >= 1) {
          Message::result_json(2,'单据已复核!');
      }



      //复核生成其他入库单

      //根据处理方式 如果是退回则反馈到销售单 如果是调换 则不反馈到销售单
      //
      //审核结束后 生成入库单  根据不同情况 如果是归仓库处理 选择已损坏无法入库 或 入库增加库存
      //                                    如果是归工厂处理 则没有后续处理

      $this->createImport($id);


      
      //反馈退货数量到 销售单
      $SellOrderReturnGoods = SellOrderReturnGoods::find()->where(['order_id'=>$id])->all();
      foreach ($SellOrderReturnGoods as $key => $value) {
        if ($value->return_number > 0) {
          $SellOrderGoods = SellOrderGoods::find()->where(['order_id'=>$SellOrderReturn->sell_order_id,'goods_id'=>$value->goods_id])->one();
          if ($SellOrderGoods) {
            $SellOrderGoods->return_number = $value->return_number;
            $SellOrderGoods->save(); 
          }
        }
      }


      $SellOrderReturn->admit_user_id = Yii::$app->session['manage_user']['id'];
      $SellOrderReturn->admit_user_name = Yii::$app->session['manage_user']['admin_name'];
      $SellOrderReturn->admit_time = time();
      $SellOrderReturn->sell_order_return_status = 1;
      $SellOrderReturn->save(false);

      Message::result_json(1,'复核成功');
    }

    //生成其他入库单
    private function CreateImport($id){

      //根据处理方式 如果是退回则反馈到销售单 如果是调换 则不反馈到销售单
      //
      //审核结束后 生成入库单  根据不同情况 如果是归仓库处理 选择已损坏无法入库 或 入库增加库存
      //                                    如果是归工厂处理 则没有后续处理

      $SellOrderReturn = SellOrderReturn::findone($id);
      $SellOrderReturnGoods = SellOrderReturnGoods::find()->where(['order_id'=>$id])->andwhere(['>','return_type',2])->all();

      $transaction = Yii::$app->db->beginTransaction();
 
      //新建入库单
      $OtherImportOrder = new OtherImportOrder();
      $OtherImportOrder->store_id = $SellOrderReturn->store_id;
      $OtherImportOrder->store_name = $SellOrderReturn->store_name;

      $OtherImportOrder->sell_order_return_id = $SellOrderReturn->id;
      $OtherImportOrder->sell_order_return_sn = $SellOrderReturn->order_sn;
      $OtherImportOrder->relate_order_id = $SellOrderReturn->id;
      $OtherImportOrder->relate_order_sn = $SellOrderReturn->order_sn;
      $OtherImportOrder->custom_id = $SellOrderReturn->custom_id;
      $OtherImportOrder->custom_name = $SellOrderReturn->custom_name;

      $OtherImportOrder->other_import_order_type = 1;
      $OtherImportOrder->other_import_order_status = 0;

      $OtherImportOrder->order_sn = Common_fun::create_sn('app\common\models\OtherImportOrder',5);
      $OtherImportOrder->save(false);

      $import_goods_arr = [];

      foreach ($SellOrderReturnGoods as $key => $value) {
        if ($value->return_number <= 0) {
          //取消事务
          $transaction->rollback();
          message::result_json(2,$value->goods_name.'未填写退回数量',$value->id);
        }

        $import_goods_arr[$value->goods_id]['order_id'] = $OtherImportOrder->id; 
        $import_goods_arr[$value->goods_id]['goods_id'] = $value->goods_id;
        $import_goods_arr[$value->goods_id]['goods_name'] = $value->goods_name;
        $import_goods_arr[$value->goods_id]['goods_sn'] = $value->goods_sn;
        $import_goods_arr[$value->goods_id]['isbn'] = $value->isbn;
        $import_goods_arr[$value->goods_id]['market_price'] = $value->market_price;
        $import_goods_arr[$value->goods_id]['number'] = $value->return_number;
        $import_goods_arr[$value->goods_id]['store_code'] = '';
        $import_goods_arr[$value->goods_id]['return_type'] = $value->return_type;
        //得到最近使用的库位
        $stock = Stock::find()->where(['goods_id'=>$value->goods_id,'store_id'=>$OtherImportOrder->store_id])->orderby('id DESC')->one();
        if ($stock) {
          $import_goods_arr[$value->goods_id]['store_code'] = $stock->stor_code;
        }
      }

      if ($import_goods_arr) {
          $goods_title =['order_id','goods_id','goods_name','goods_sn','isbn','market_price','number','store_code','return_type'];//数据键
          $res= Yii::$app->db->createCommand()->batchInsert(OtherImportOrderGoods::tableName(), $goods_title, $import_goods_arr)->execute();
          //提交事务
          $transaction->commit();

      }else{
        $transaction->rollback();
        message::result_json(2,'没有商品记录');
      }
    }

    public function actionDeleteGoods($id){
        $data_id = Yii::$app->request->get('data_id');
        $CustomOrderGoods = SellOrderReturnGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        $CustomOrderGoods->delete();

        message::result_json(1,'删除成功');
    }

    public function actionUpdateGoodsLabel(){
        $data_type  = trim(Yii::$app->request->get('data_type'));
        $value  = trim(Yii::$app->request->get('value'));
        $id  = Yii::$app->request->get('id');
        $data_id  = Yii::$app->request->get('data_id');

        $CustomOrderGoods = SellOrderReturnGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        if($CustomOrderGoods){

          if ($data_type == 'return_number') {
            if ($CustomOrderGoods->number < $value) {
               message::result_json(2,'退回商品数不能大于销售商品数！');
            }
          }

          $CustomOrderGoods->$data_type = $value;
          $CustomOrderGoods->save(false);
          message::result_json(1,'修改成功',$value);
        }else{
          message::result_json(2,'没有此记录');
        }
    }

 
}
