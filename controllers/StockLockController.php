<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\StockLockGoods;
use app\common\models\StockLock;
use app\common\models\Custom;
use app\common\models\Goods;
use app\common\models\Store;
use app\common\models\Supplier;
use yii\helpers\Url;
use app\includes\Message;
use app\controllers\BaseController;
use app\includes\Common_fun;

class StockLockController extends BaseController
{   
    public $page_title = '商品锁库';

    public $title_model = 'app\common\models\StockLock';
    public $detail_model = 'app\common\models\StockLockGoods';
    public $status_label = 'stock_lock_status';
    
    public function beforeAction($action)
    {
      parent::beforeAction($action);

      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','admit','insert-goods','delete-goods','update-goods-label'];
      $admit_allow_arr = ['edit','view',];

      $scope_model = 'app\common\models\StockLock';
      $status_label = 'stock_lock_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }

    //删除
    public function actionDelete($id){
      $StockLock = StockLock::findone($id);
      if ($StockLock) {
        $StockLock->delete();
        message::result_json(1,'删除成功');
      }else{
        message::result_json(2,'数据不存在');
      }
    }

    //添加商品锁库
    public function actionInsert(){
        $StockLock = new StockLock();
        $StockLock->load(Yii::$app->request->post());

        if (!$StockLock->store_id) {
          Message::result_json(2,'仓库不能为空');
        }

        $store = Store::findone($StockLock->store_id);
        if (!$store) {
          Message::result_json(2,'仓库不存在，请重新选择');
        }else{
          $StockLock->store_name = $store->store_name;
        }

        $StockLock->end_time = $StockLock->end_time?strtotime($StockLock->end_time):time();

        $StockLock->order_sn = Common_fun::create_sn('app\common\models\StockLock',5);

        $StockLock->save(false);
        Message::result_json(1,'添加成功');
    }

    public function actionUpdate($id){
        $StockLock = StockLock::findone($id);
        if (!$StockLock) {
          Message::result_json(2,'参数错误，单据不存在');
        }
        $StockLock->load(Yii::$app->request->post());

        if (!$StockLock->store_id) {
          Message::result_json(2,'仓库不能为空');
        }

        $store = Store::findone($StockLock->store_id);
        if (!$store) {
          Message::result_json(2,'仓库不存在，请重新选择');
        }else{
          $StockLock->store_name = $store->store_name;
        }

        $StockLock->end_time = $StockLock->end_time?strtotime($StockLock->end_time):time();
 
        $StockLock->save(false);
        Message::result_json(1,'编辑成功');
    }



    public function actionEdit($id){
      $this->layout = 'empty';
        $StockLock = StockLock::findone($id);
        return $this->render('create', ['stock_lock'=>$StockLock,'id'=>$id,]);
    } 

    public function actionView($id){
        $StockLock = StockLock::findone($id);
        return $this->render('view', ['stock_lock'=>$StockLock,'id'=>$id,]);
    }

    public function actionAdmit($id,$process_status){
      //修改订单状态
      $StockLock = StockLock::findone($id);

      $transaction = Yii::$app->db->beginTransaction();

      $admit_result = $this->base_admit($StockLock,'stock_lock_status',$process_status);
      if ($admit_result['error'] > 2) {
        $transaction->commit();
        Message::result_json(2,$admit_result['message']);
      }

      
      if ($admit_result['error'] == 1) {
        //取出库存 更新到订单商品列表中
        
        $transaction->commit();
      }
 
      Message::result_json(1,'复核成功');

    }

    public function actionInsertGoods(){
      $goods_id = Yii::$app->request->get('goods_id',0);
      $order_id = Yii::$app->request->get('order_id',0);
      $search_data = Yii::$app->request->get('search_data',0);

      if (!$order_id) {
        message::result_json(2,'数据错误');
      }else{
        //检查用户对该单据的操作权限
        $order = StockLock::findone($order_id);

      }

      $add_goods_error = [];
      if ($goods_id) {
          if (is_array($goods_id)) {
            foreach ($goods_id as $key => $value) {
              $goods = Goods::findone($value);
              if ($goods) {
                //插入数据
                $StockLockGoods = new StockLockGoods();
                $add_goods_result = $StockLockGoods->AddGoods($order_id,$goods);
                if (!$add_goods_result) {
                    $add_goods_error[] = $StockLockGoods->add_goods_error;
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
            $StockLockGoods = new StockLockGoods();
            $add_goods_result = $StockLockGoods->AddGoods($order_id,$goods);

            if (!$add_goods_result) {
                $add_goods_error = $StockLockGoods->add_goods_error;
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
        $StockLockGoods = StockLockGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        $StockLockGoods->delete();
        
        message::result_json(1,'删除成功');
    }

    public function actionUpdateGoodsLabel(){

        $data_type  = trim(Yii::$app->request->get('data_type'));
        $value  = trim(Yii::$app->request->get('value'));
        $id  = Yii::$app->request->get('id');
        $data_id  = Yii::$app->request->get('data_id');
        $allow_arr = ['reutrn_ask_price','return_number'];

        $StockLockGoods = StockLockGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        if($StockLockGoods){
          $StockLockGoods->$data_type = $value;
          $StockLockGoods->save(false);
          message::result_json(1,'chengg',$value);
        }else{
          message::result_json(2,'没有此记录');
        }
    }
 

}
