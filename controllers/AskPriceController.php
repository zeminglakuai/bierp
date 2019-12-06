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

use app\common\models\SupplierGoods;
use app\common\models\Supplier;
use app\common\models\AskPriceOrder;
use app\common\models\AskPriceOrderGoods;

use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class AskPriceController extends BaseController
{   
    public $page_title = '询价报备';
    public $title_model = 'app\common\models\AskPriceOrder';
    public $detail_model = 'app\common\models\AskPriceOrderGoods';
    public $status_label = 'ask_price_order_status';
    
    public function beforeAction($action)
    {
      parent::beforeAction($action);

      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','admit','insert-goods','delete-goods','update-goods-label'];
      $admit_allow_arr = ['view','edit'];

      $scope_model = 'app\common\models\AskPriceOrder';
      $status_label = 'ask_price_order_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }

    public function actionIndex()
    {	
  		return $this->render('index', []);
    }

    //删除
    public function actionDelete($id){
      $AskPriceOrder = AskPriceOrder::findone($id);
      if ($AskPriceOrder) {
        $AskPriceOrder->delete();
        message::result_json(1,'删除成功');
      }else{
        message::result_json(2,'数据不存在');
      }
    }

    //添加询价单
    public function actionInsert(){
        $AskPriceOrder = new AskPriceOrder();
        $AskPriceOrder->load(Yii::$app->request->post());
        
        if (!$AskPriceOrder->supplier_id) {
          Message::result_json(2,'供货商不能为空');
        }

        $supplier = Supplier::findone($AskPriceOrder->supplier_id);
        if (!$supplier) {
          Message::result_json(2,'供货商不存在，请重新选择');
        }else{
          $AskPriceOrder->supplier_name = $supplier->supplier_name;
        }

        $AskPriceOrder->order_sn = Common_fun::create_sn('app\common\models\AskPriceOrder',5);
        $AskPriceOrder->access_secrect = substr(md5(uniqid(rand(), TRUE)),0,4);
        $AskPriceOrder->ask_price_order_status = 0;

        $AskPriceOrder->save(false);
        Message::result_json(1,'添加成功');
    }

    public function actionUpdate($id){
        $AskPriceOrder = AskPriceOrder::findone($id);
        $AskPriceOrder->load(Yii::$app->request->post());
        
        if (!$AskPriceOrder->supplier_id) {
          Message::result_json(2,'供货商不能为空');
        }

        $supplier = Supplier::findone($AskPriceOrder->supplier_id);
        if (!$supplier) {
          Message::result_json(2,'供货商不存在，请重新选择');
        }else{
          $AskPriceOrder->supplier_name = $supplier->supplier_name;
        }
 
        $AskPriceOrder->save(false);
        Message::result_json(1,'编辑成功');
    }

    public function actionView($id){
        $AskPriceOrder = AskPriceOrder::findone($id);
        return $this->render('view', ['ask_price_order'=>$AskPriceOrder,'id'=>$id]);
    } 

    public function actionEdit($id){
        $this->layout = 'empty';
        $AskPriceOrder = AskPriceOrder::findone($id);
        return $this->render('create', ['ask_price_order'=>$AskPriceOrder,'id'=>$id]);
    } 

    public function actionAdmit($id,$process_status){
      //修改订单状态
      $AskPriceOrder = AskPriceOrder::findone($id);

      //检查单据是不是已经询价结束
      $no_ask_price_goods = AskPriceOrderGoods::find()->where(['or','return_ask_price <= 0','return_number <= 0'])->andwhere(['order_id'=>$id])->one();
      if ($no_ask_price_goods) {
        Message::result_json(3,'还有未询价的商品!',$no_ask_price_goods->id);
      }

      $goods_list = AskPriceOrderGoods::find()->where(['order_id'=>$id])->all();
      if (!$goods_list) {
        Message::result_json(4,'单据没有商品!');
      }
      $admit_result = $this->base_admit($AskPriceOrder,'ask_price_order_status',$process_status);
      if ($admit_result['error'] > 2) {
        Message::result_json(2,$admit_result['message']);
      }

      if ($admit_result['error'] == 1) {
        //如果存在来源的客户方案 就反馈询价结果到客户方案中
        if ($AskPriceOrder->custom_order_id > 0) {
          //把供货商返回的价格 反馈到客户方案 订单中
          foreach ($goods_list as $key => $value) {
              $goods = CustomOrderGoods::find()->where(['goods_id'=>$value->goods_id,'order_id'=>$AskPriceOrder->custom_order_id])->one();
             
              if ($goods) {
                  $goods->supplier_name = $AskPriceOrder->supplier_name;
                  $goods->supplier_id = $AskPriceOrder->supplier_id;
                  $goods->supplier_price = $value->return_ask_price;
                  $goods->supplier_number = $value->return_number;
                  $goods->save(false);
              }
          }
          $AskPriceOrder->ask_price_order_status = 3;
        }else{
          $AskPriceOrder->ask_price_order_status = 2;
        }

        $AskPriceOrder->save(false);

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
        $order = AskPriceOrder::findone($order_id);

      }
      $add_goods_error = [];
      if ($goods_id) {
          if (is_array($goods_id)) {

            foreach ($goods_id as $key => $value) {
              $goods = Goods::findone($value);
              if ($goods) {
                //插入数据
                $AskPriceOrderGoods = new AskPriceOrderGoods();
                $add_goods_result = $AskPriceOrderGoods->AddGoods($order_id,$goods);
                if (!$add_goods_result) {
                    $add_goods_error[] = $AskPriceOrderGoods->add_goods_error;
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
            $AskPriceOrderGoods = new AskPriceOrderGoods();
            $add_goods_result = $AskPriceOrderGoods->AddGoods($order_id,$goods);

            if (!$add_goods_result) {
                $add_goods_error = $AskPriceOrderGoods->add_goods_error;
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
        $AskPriceOrderGoods = AskPriceOrderGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        $AskPriceOrderGoods->delete();
        
        message::result_json(1,'删除成功');
    }

    public function actionUpdateGoodsLabel(){

        $data_type  = trim(Yii::$app->request->get('data_type'));
        $value  = trim(Yii::$app->request->get('value'));
        $id  = Yii::$app->request->get('id');
        $data_id  = Yii::$app->request->get('data_id');
        $allow_arr = ['reutrn_ask_price','return_number'];

        $AskPriceOrderGoods = AskPriceOrderGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        if($AskPriceOrderGoods){
          $AskPriceOrderGoods->$data_type = $value;
          $AskPriceOrderGoods->save(false);
          message::result_json(1,'chengg',$value);
        }else{
          message::result_json(2,'没有此记录');
        }
    }

    public function actionViewCheckUrl($id){
        $this->layout = 'empty';
        $AskPriceOrder = AskPriceOrder::findone($id);
        return $this->render('check-url', ['ask_price_order'=>$AskPriceOrder]);
    }

}
