<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;

use app\common\models\SellInvoice;
use app\common\models\Custom;
use app\common\models\SellOrder;
use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class SellInvoiceController extends BaseController
{
    public $page_title = '销售发票';
    public $title_model = 'app\common\models\SellInvoice';
    public $status_label = 'sell_invoice_status';
    
    public function beforeAction($action)
    {
      parent::beforeAction($action);

      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','update','admit','insert-goods','delete-goods','update-goods-label'];
      $admit_allow_arr = ['edit','view'];

      $scope_model = 'app\common\models\SellInvoice';
      $status_label = 'sell_invoice_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }

 
    //删除
    public function actionDelete($id){
      $SellInvoice = SellInvoice::findone($id);
      $SellInvoice->delete();
      message::result_json(1,'删除成功');
    }

    //添加
    public function actionInsert(){
      $SellInvoice = new SellInvoice();
      $SellInvoice->load(Yii::$app->request->post());

      if (!$SellInvoice->fee || $SellInvoice->fee <= 0) {
        Message::result_json(2,'请输入发票金额');
      }

      if (!$SellInvoice->custom_id) {
        Message::result_json(2,'客户不能为空');
      }else{
        $custom = Custom::findone($SellInvoice->custom_id);
        $SellInvoice->custom_name = $custom->custom_name;
      }

      $SellInvoice->remain_time = $SellInvoice->remain_time?strtotime($SellInvoice->remain_time):time();
      $SellInvoice->invoice_time = $SellInvoice->invoice_time?strtotime($SellInvoice->invoice_time):time();

      if ($SellInvoice->invoice_rate) {
        $SellInvoice->invoice_rate_fee = round($SellInvoice->invoice_rate*$SellInvoice->fee/100,2);
      }

      if ($SellInvoice->order_id) {
        $sell_order = SellOrder::findone($SellInvoice->order_id);
        if (!$sell_order) {
          Message::result_json(2,'销售单不存在');
        }else{
          $SellInvoice->custom_id = $sell_order->custom_id;
          $SellInvoice->custom_name = $sell_order->custom_name;
          $SellInvoice->relate_order_sn = $sell_order->order_sn;
          $SellInvoice->model = 'SellOrder';
          $SellInvoice->order_total = $sell_order->total;
        }
        //计算收款单总金额 收款单总金额不能大于销售单金额
        $sum_total = SellInvoice::find()->where(['order_id'=>$SellInvoice->order_id,'model'=>'SellOrder'])->sum('fee');
        $sum_total += $SellInvoice->fee;
        if ($sum_total > $sell_order->total) {
          Message::result_json(2,'收款单总金额不能大于销售单金额');
        }
      }

      $SellInvoice->sell_invoice_status = 0;
      $SellInvoice->order_sn = Common_fun::create_sn('app\common\models\SellInvoice',5);
      
      $SellInvoice->save(false);
      Message::result_json(1,'添加成功');

    }

    public function actionView($id){
       $this->layout = 'empty';
        $SellInvoice = SellInvoice::findone($id);
        return $this->render('create', ['sell_invoice'=>$SellInvoice,'id'=>$id]);
    }

    public function actionEdit($id){
        $this->layout = 'empty';
        $SellOrder = SellOrder::findone($id);
        return $this->render('edit', ['sell_order'=>$SellOrder,'id'=>$id]);
    }

    public function actionUpdate($id){
      $SellInvoice = SellInvoice::findone($id);
      $SellInvoice->load(Yii::$app->request->post());

      if (!$SellInvoice->fee || $SellInvoice->fee <= 0) {
        Message::result_json(2,'请输入发票金额');
      }

      if (!$SellInvoice->custom_id) {
        Message::result_json(2,'客户不能为空');
      }else{
        $custom = Custom::findone($SellInvoice->custom_id);
        $SellInvoice->custom_name = $custom->custom_name;
      }

      $SellInvoice->remain_time = $SellInvoice->remain_time?strtotime($SellInvoice->remain_time):time();
      $SellInvoice->invoice_time = $SellInvoice->invoice_time?strtotime($SellInvoice->invoice_time):time();

      if ($SellInvoice->invoice_rate) {
        $SellInvoice->invoice_rate_fee = round($SellInvoice->invoice_rate*$SellInvoice->fee/100,2);
      }

      if ($SellInvoice->order_id) {
        $sell_order = SellOrder::findone($SellInvoice->order_id);
        if (!$sell_order) {
          Message::result_json(2,'销售单不存在');
        }else{
          $SellInvoice->custom_id = $sell_order->custom_id;
          $SellInvoice->custom_name = $sell_order->custom_name;
          $SellInvoice->relate_order_sn = $sell_order->order_sn;
          $SellInvoice->order_total = $sell_order->total;
          $SellInvoice->model = 'SellOrder';
        }
        //计算收款单总金额 收款单总金额不能大于销售单金额
        $sum_total = SellInvoice::find()->where(['order_id'=>$SellInvoice->order_id,'model'=>'SellOrder'])->andwhere(['<>','id',$id])->sum('fee');
        $sum_total += $SellInvoice->fee;
        if ($sum_total > $sell_order->total) {
          Message::result_json(2,'收款单总金额不能大于销售单金额');
        }
      }

      $SellInvoice->save(false);
      Message::result_json(1,'编辑成功');
    }

    public function actionAdmit($id,$process_status){
      //修改订单状态
      $SellInvoice = SellInvoice::findone($id);
      if ($SellInvoice->sell_invoice_status >= 1) {
          Message::result_json(2,'单据已复核!');
      }



      $admit_result = $this->base_admit($SellInvoice,'sell_invoice_status',$process_status);
      if ($admit_result['error'] > 2) {
        Message::result_json(2,$admit_result['message']);
      }

      if ($admit_result['error'] == 1) {
        //检查相关销售单据金额 和 全部收款单金额 对比 如果金额相同 则 设置销售单付款成功
        if ($SellInvoice->order_id && $SellInvoice->model == 'SellOrder') {
          $sell_order = SellOrder::findone($SellInvoice->order_id);
          $sell_invoice_total = SellInvoice::find()->where(['order_id'=>$SellInvoice->order_id,'model'=>$SellInvoice->model])->sum('fee');
          if ($sell_order && $sell_order->total) {
            if ($sell_order->total - $sell_invoice_total < 1) {
              $sell_order->invoice_status=  1;
              $sell_order->save(false);
            }
          }
        }
      }

      Message::result_json(1,'复核成功');

    }
}
