<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Goods;
use app\common\models\Stock;
use app\common\models\Batch;
use app\common\models\Supplier;
use app\common\models\SellOrderGoods;
use app\common\models\SellOrder;
use app\common\models\Store;
use app\common\models\SupplierGoods;
use app\common\models\ImportOrder;
use app\common\models\ImportOrderGoods;

use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class ImportOrderController extends BaseController
{
    public $page_title = '入库单';
    public $title_model = 'app\common\models\ImportOrder';
    public $detail_model = 'app\common\models\ImportOrderGoods';
    public $status_label = 'import_order_status';

    public function beforeAction($action)
    {
      parent::beforeAction($action);

      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','admit','update','insert-goods','delete-goods','update-goods-label'];
      $admit_allow_arr = ['edit','view'];

      $scope_model = 'app\common\models\ImportOrder';
      $status_label = 'import_order_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }
    
    public function actionIndex(){
      if (isset(Yii::$app->session['manage_user']['store_id']) && Yii::$app->session['manage_user']['store_id'] > 0) {
        $init_condition = [['store_id'=>Yii::$app->session['manage_user']['store_id']]];
      }
      return $this->render('index',['init_condition'=>$init_condition]);
    }
    
    //删除
    public function actionDelete($id){
      $ImportOrder = ImportOrder::findone($id);
      if ($ImportOrder) {
        $ImportOrder->delete();
        message::result_json(1,'删除成功');
      }else{
        message::result_json(1,'数据错误');
      }
    }

    //添加
    public function actionInsert(){
        $ImportOrder = new ImportOrder();
        $ImportOrder->load(Yii::$app->request->post());

        if (!$ImportOrder->supplier_id) {
          Message::result_json(2,'供货商不能为空');
        }

        $supplier = Supplier::findone($ImportOrder->supplier_id);
        if (!$supplier) {
          Message::result_json(2,'供货商不存在，请重新选择');
        }else{
          $ImportOrder->supplier_name = $supplier->supplier_name;
        }

        if (!$ImportOrder->store_id) {
          Message::result_json(2,'仓库不能为空');
        }

        $store = Store::findone($ImportOrder->store_id);
        if (!$store) {
          Message::result_json(2,'仓库不存在，请重新选择');
        }else{
          $ImportOrder->store_name = $store->store_name;
        }

        $ImportOrder->order_sn = Common_fun::create_sn('app\common\models\ImportOrder',5);
        
        $ImportOrder->save(false);
        Message::result_json(1,'添加成功');
    }

   public function actionView($id){
        $ImportOrder = ImportOrder::findone($id);
        return $this->render('view', ['import_order'=>$ImportOrder,'id'=>$id]);
    }
    public function actionSavestore(){
        $store_id  = Yii::$app->request->get('sele');
        $goods_id = Yii::$app->request->get('id');
        $number  = Yii::$app->request->get('num');
        if (!$store_id||!$goods_id){
            Message::result_json(2,'操作失败');
        }
        $Stock_list = Stock::find()->where(['goods_id'=>$goods_id,'store_id'=>$store_id])->one();
        $StockInfo=new Stock();
        $StockInfo->store_id=$store_id;
        $StockInfo->goods_id=$goods_id;
        $StockInfo->add_time = time();
        $StockInfo->add_user_id = Yii::$app->session['manage_user']['id'];
        $StockInfo->add_user_name = Yii::$app->session['manage_user']['admin_name'];
        if(count($Stock_list) > 0){
            $number+=$Stock_list['number'];

            $StockInfo->number=$number;
            $StockInfo->save(false);

        }else{
            $StockInfo->number=$number;
            $StockInfo->save(false);
        }
        Message::result_json(1,'添加成功',$StockInfo->number);
    }

    public function actionEdit($id){
        $this->layout = 'empty';
        $ImportOrder = ImportOrder::findone($id);
        return $this->render('create', ['import_order'=>$ImportOrder,'id'=>$id]);
    }

    public function actionUpdate($id){
        $ImportOrder = ImportOrder::findone($id);
        $ImportOrder->load(Yii::$app->request->post());

        if (!$ImportOrder->supplier_id) {
          Message::result_json(2,'供货商不能为空');
        }

        $supplier = Supplier::findone($ImportOrder->supplier_id);
        if (!$supplier) {
          Message::result_json(2,'供货商不存在，请重新选择');
        }else{
          $ImportOrder->supplier_name = $supplier->supplier_name;
        }

        if (!$ImportOrder->store_id) {
          Message::result_json(2,'仓库不能为空');
        }

        $store = Store::findone($ImportOrder->store_id);
        if (!$store) {
          Message::result_json(2,'仓库不存在，请重新选择');
        }else{
          $ImportOrder->store_name = $store->store_name;
        }

        $ImportOrder->save(false);
        Message::result_json(1,'编辑成功');

    }

    public function actionAdmit($id,$process_status){
        //修改订单状态
        $ImportOrder = ImportOrder::findone($id);
        if ($ImportOrder->import_order_status >= 1) {
            Message::result_json(2,'单据已复核!');
        }

        $transaction = Yii::$app->db->beginTransaction();

        $admit_result = $this->base_admit($ImportOrder,'import_order_status',$process_status);
        if ($admit_result['error'] > 2) {
            $transaction->rollBack();
            Message::result_json(2,$admit_result['message']);
        }

        if ($admit_result['error'] == 1) {
            //符合成功 增加库存 和 批次
            //检查库位和数量是不是有填写

            $ImportOrderGoods = ImportOrderGoods::find()->where(['order_id'=>$id])->all();

            foreach ($ImportOrderGoods as $key => $value) {
                if (!$value->real_number) {
                    $transaction->rollBack();
                    Message::result_json(2,'单据信息不完整',$value->id);
                }
                //增加库存
                $stock = Stock::find()->where(['goods_id'=>$value->goods_id,'store_id'=>$ImportOrder->store_id])->one();
                if (!$stock) {
                    $value->real_number+=$stock['number'];

                }
                $stock = new Stock();

                $stock->add_stocks($ImportOrder->store_id,'',$value->goods_id,$value->real_number,$value->purchase_price,$ImportOrder->order_sn,time(),Yii::$app->session['manage_user']['id'],Yii::$app->session['manage_user']['admin_name']);
            }

            $transaction->commit();
        }

        Message::result_json(1,'复核成功');
    }


    /*获取一条商品*/
    public function actionGoodOne(){
        $id  = Yii::$app->request->get('goods_id');
        $goods_info = Goods::findone($id);
        if (!$goods_info) {
            message::result_json(2,'数据错误');
        }else{

            $val="<tr><td>$goods_info->goods_name</td><td>$goods_info->goods_sn</td><td>$goods_info->isbn</td><td>$goods_info->market_price</td><td><a class='delete_goods' action='delete-goods' href='javascript:void();'><span class='glyphicon glyphicon-trash'></span>删除</a></td></tr>";
            message::result_json(1,'',$val);
        }
    }
    public function actionInsertGoods(){
      $goods_id = Yii::$app->request->get('goods_id',0);
      $order_id = Yii::$app->request->get('order_id',0);
      $search_data = Yii::$app->request->get('search_data',0);

      if (!$order_id) {
        message::result_json(2,'数据错误');
      }else{
        $order = ImportOrder::findone($order_id);
      }

      $add_goods_error = [];
      if ($goods_id) {
          if (is_array($goods_id)) {

            foreach ($goods_id as $key => $value) {
              $goods = Goods::findone($value);
              if ($goods) {
                //插入数据
                $ImportOrderGoods = new ImportOrderGoods();
                $add_goods_result = $ImportOrderGoods->AddGoods($order_id,$goods);
                if (!$add_goods_result) {
                    $add_goods_error[] = $ImportOrderGoods->add_goods_error;
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
            $ImportOrderGoods = new ImportOrderGoods();
            $add_goods_result = $ImportOrderGoods->AddGoods($order_id,$goods);

            if (!$add_goods_result) {
                $add_goods_error = $ImportOrderGoods->add_goods_error;
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
    /*添加到库存*/
    public function actionInsertGoodStore(){
        $goods_id = Yii::$app->request->get('goods_id',0);


    }
    public function actionDeleteGoods($id){
        $data_id = Yii::$app->request->get('data_id');
        $ImportOrderGoods = ImportOrderGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        $ImportOrderGoods->delete();

        message::result_json(1,'删除成功');
    }

    public function actionUpdateGoodsLabel(){
        $data_type  = trim(Yii::$app->request->get('data_type'));
        $value  = trim(Yii::$app->request->get('value'));
        $id  = Yii::$app->request->get('id');
        $data_id  = Yii::$app->request->get('data_id');

        $ImportOrderGoods = ImportOrderGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        if($ImportOrderGoods){
          $ImportOrderGoods->$data_type = $value;
          $ImportOrderGoods->save(false);
          message::result_json(1,'chengg',$value);
        }else{
          message::result_json(2,'没有此记录');
        }
    }
    /*创建入库单*/
    public function actionCreateOrder()
    {

        return $this->render('goods-list',[
                'data_list'=>$data_list,
                'title_arr'=>$this->title_arr,
                'search_data' => $search_data,
                'model'=>$model,
                'model_name'=>$model_name,
                'model_name_lower'=>$model_name_lower,
                'opration' => $this->opration,
                'update_label_url' => $this->update_label_url,
                'present_action' => $this->present_action,
                'width' => $this->width,
            ]
        );

    }
}
