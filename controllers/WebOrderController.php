<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\WebOrderGoods;
use app\common\models\WebOrder;
use app\common\models\WebUser;
use app\common\models\Goods;

use app\common\models\OrderLog;

use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class WebOrderController extends BaseController
{
    
    public function beforeAction($action)
    {
      parent::beforeAction($action);

      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['remend_history','delete','edit','admit','insert-goods','delete-goods','update-goods-label'];
      $admit_allow_arr = ['second-admit','create-ask-price','update-goods-label','create-sell-order'];

      $scope_model = 'app\common\models\WebOrder';
      $status_label = 'custom_order_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }

    //添加网上订单
    public function actionCreate(){
        $this->layout = 'empty';
        return $this->render('create', []);
    }

    //删除客户方案
    public function actionDelete($id){
      $custom_order = WebOrder::findone($id);
      $custom_order->delete();
      message::show_message('删除成功');
    }

    //添加客户方案
    public function actionInsert(){
 
    }

    public function actionEdit($id){
      $this->layout = 'empty';
        $custom_order = WebOrder::findone($id);
        return $this->render('edit', ['web_order'=>$custom_order,'id'=>$id]);
    }

    public function actionUpdate($id){
        $WebOrder = WebOrder::findone($id);
        $CustomOrder->load(Yii::$app->request->post());

        if (!$CustomOrder->custom_id) {
          Message::result_json(2,'客户不能为空');
        }

        $custom = Custom::findone($CustomOrder->custom_id);
        if (!$custom) {
          Message::result_json(2,'客户不存在，请重新选择');
        }else{
          $CustomOrder->custom_name = $custom->custom_name;
        }
        if (strlen($CustomOrder->order_name) < 2) {
          Message::result_json(2,'单据名称不能为空');
        }

        $CustomOrder->save(false);
        Message::result_json(1,'编辑成功');
    }

    public function actionView($id){
        $custom_order = CustomOrder::findone($id);
        $custom_order_goods = CustomOrderGoods::find()->where(['order_id'=>$custom_order->id])->all();

        return $this->render('view', ['custom_order'=>$custom_order,'id'=>$id,'custom_order_goods'=>$custom_order_goods]);
    }

    public function actionAdmit($id){
      //修改订单状态
      $CustomOrder = CustomOrder::findone($id);
      if ($CustomOrder->custom_order_status >= 1) {
          Message::result_json(2,'单据已复核!');
      }
      $CustomOrder->admit_user_id = Yii::$app->session['manage_user']['id'];
      $CustomOrder->admit_user_name = Yii::$app->session['manage_user']['admin_name'];
      $CustomOrder->admit_time = time();
      $CustomOrder->custom_order_status = 1;
      $CustomOrder->save(false);
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
        $order = CustomOrder::findone($order_id);

      }
      $add_goods_error = [];
      if ($goods_id) {
          if (is_array($goods_id)) {

            foreach ($goods_id as $key => $value) {
              $goods = Goods::findone($value);
              if ($goods) {
                //插入数据
                $CustomOrderGoods = new CustomOrderGoods();
                $add_goods_result = $CustomOrderGoods->AddGoods($order_id,$goods);
                if (!$add_goods_result) {
                    $add_goods_error[] = $CustomOrderGoods->add_goods_error;
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
            $CustomOrderGoods = new CustomOrderGoods();
            $add_goods_result = $CustomOrderGoods->AddGoods($order_id,$goods);

            if (!$add_goods_result) {
                $add_goods_error = $CustomOrderGoods->add_goods_error;
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

      message::result_json(2,'数据错误2');
    }
    public function actionDeleteGoods($id){
        $data_id = Yii::$app->request->get('data_id');
        $CustomOrderGoods = CustomOrderGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        $CustomOrderGoods->delete();

        message::result_json(1,'删除成功');
    }

    public function actionUpdateGoodsLabel(){

        $data_type  = trim(Yii::$app->request->get('data_type'));
        $value  = trim(Yii::$app->request->get('value'));
        $id  = Yii::$app->request->get('id');
        $data_id  = Yii::$app->request->get('data_id');

        $CustomOrderGoods = CustomOrderGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        if($CustomOrderGoods){
          $origin_value = $CustomOrderGoods->$data_type;
          $CustomOrderGoods->$data_type = $value;
          $CustomOrderGoods->save(false);

          //如果当局已经复核  则记录修改记录
          $custom_order = CustomOrder::findone($id);
          if ($custom_order && $custom_order->custom_order_status > 1) {
            $order_log = new OrderLog();
            $order_log->model = 'CustomOrder';
            $order_log->order_id = $id;
            $order_log->lable_name = $data_type;
            $order_log->origin_value = $origin_value;
            $order_log->new_value = $value;
            $order_log->goods_id = $CustomOrderGoods->goods_id;
            $order_log->goods_name = $CustomOrderGoods->goods_name;
            $order_log->goods_sn = $CustomOrderGoods->goods_sn;
            $order_log->save(false);
          }

          $supplier_price_arr = ['consult'];
          $sale_price_arr = ['platform_rate','tranform_rate'];
          if (in_array($data_type, $supplier_price_arr)) {
            $value = $CustomOrderGoods->supplier_price * $value;
          }

          if (in_array($data_type, $sale_price_arr)) {
            $value = $CustomOrderGoods->sale_price * $value;
          }

          //根据公式计算产生变化的值
          //当前只计算当前列的值的变化
          $calculate_value = [];
          $calculate_value[] = ['label_name'=>'saleTotal','new_value'=>$CustomOrderGoods->saleTotal];
          $calculate_value[] = ['label_name'=>'supplierTotal','new_value'=>$CustomOrderGoods->supplierTotal];      
          $calculate_value[] = ['label_name'=>'profit','new_value'=>$CustomOrderGoods->profit];
          $calculate_value[] = ['label_name'=>'profitTotal','new_value'=>$CustomOrderGoods->profitTotal];
          $calculate_value[] = ['label_name'=>'profitRate','new_value'=>$CustomOrderGoods->profitRate];
          $calculate_value[] = ['label_name'=>'finalCostTotal','new_value'=>$CustomOrderGoods->finalCostTotal];
          $calculate_value[] = ['label_name'=>'faxPoint','new_value'=>$CustomOrderGoods->faxPoint];
          $calculate_value[] = ['label_name'=>'consultFee','new_value'=>$CustomOrderGoods->consultFee];        


          message::result_json(1,'success',$value,$calculate_value);
        }else{
          message::result_json(2,'没有此记录');
        }
    }
 

    //生成 销售单
    //需要订单状态为已审核create_sell_order
    public function actionCreateSellOrder($id){
      $custom_order = CustomOrder::findone($id);
      if ($custom_order->custom_order_status == 0) {
          message::result_json(2,'单据未复核');
      }
      if ($custom_order->custom_order_status == 1) {
          message::result_json(2,'单据未二次复核');
      }

      if ($custom_order->custom_order_status >= 3) {
          message::result_json(2,'已经生成过销售单');
      }

      $SellOrder = new SellOrder();
      $SellOrder->custom_id = $custom_order->custom_id;
      $SellOrder->custom_order_id = $id;
      $SellOrder->custom_name = $custom_order->custom_name;
      $SellOrder->order_sn = Common_fun::create_sn('app\common\models\SellOrder',5);
      $SellOrder->sell_order_status = 0;
      $SellOrder->save(false);

      //得到全部商品
      $goods_list = (new \yii\db\Query())
                    ->select([$SellOrder->id.' as `order_id`','goods_id','goods_name','goods_sn','isbn','number','market_price','sale_price','supplier_id','supplier_name','supplier_price','is_self_sell'])
                    ->from('custom_order_goods')
                    ->andwhere(['order_id'=>$id])
                    ->all();

      $goods_title =['order_id','goods_id','goods_name','goods_sn','isbn','number','market_price','sale_price','supplier_id','supplier_name','supplier_price','is_self_sell'];//测试数据键
      $res= Yii::$app->db->createCommand()->batchInsert(SellOrderGoods::tableName(), $goods_title, $goods_list)->execute();
      
      //修改订单状态为 已生成销售单
      $custom_order->custom_order_status = 3;
      $custom_order->save(false);

      message::result_json(1,'生成销售单成功!');
    }

    //单据修改历史
    public function actionRemendHistory($id){
      $this->layout = 'empty';
      $order_log = OrderLog::find()->where(['model'=>'CustomOrder','order_id'=>$id])->all();
      return $this->render('remend-history', ['order_log'=>$order_log]);
    }

}
