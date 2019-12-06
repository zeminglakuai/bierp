<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Payment;
use app\common\models\Supplier;
use app\common\models\Purchase;

use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class PaymentController extends BaseController
{
    public $page_title = '应付账款';
    public $title_model = 'app\common\models\Payment';
    public $status_label = 'payment_status';
    
    public function beforeAction($action)
    {
      parent::beforeAction($action);
      //检查当前单据用户是不是有操作权限

      $need_privi_arr = ['delete','edit','admit','update'];
      $admit_allow_arr = ['edit'];
      $scope_model = 'app\common\models\Payment';
      $status_label = 'payment_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }

    //删除
    public function actionDelete($id){
      $Payment = Payment::findone($id);
      if ($Payment) {
        $Payment->delete();
        message::result_json(1,'删除成功');
      }else{
        message::result_json(2,'单据不存在');
      }
    }

    //添加
    public function actionInsert(){
        $Payment = new Payment();
        $Payment->load(Yii::$app->request->post());

        if (!$Payment->supplier_id) {
          Message::result_json(2,'供货商不能为空');
        }else{
          $supplier = Supplier::findone($Payment->supplier_id);
          $Payment->supplier_name = $supplier->supplier_name;
        }

        $Payment->remain_time = $Payment->remain_time?strtotime($Payment->remain_time):time();

        if ($Payment->order_id) {
          $purchase = Purchase::findone($Payment->order_id);
          if (!$purchase) {
            Message::result_json(2,'采购单不存在');
          }else{
            $Payment->supplier_id = $purchase->supplier_id;
            $Payment->supplier_name = $purchase->supplier_name;
            $Payment->relate_order_sn = $purchase->order_sn;
            $Payment->model = 'Purchase';
          }
          //计算收款单总金额 收款单总金额不能大于销售单金额
          $sum_total = Payment::find()->where(['order_id'=>$Payment->order_id,'model'=>'Purchase'])->sum('fee');
          $sum_total += $Payment->fee;
          if ($sum_total > $purchase->total) {
            Message::result_json(2,'付款单总金额不能大于采购单金额');
          }
        }

        $Payment->order_sn = Common_fun::create_sn('app\common\models\Payment',5);
        $Payment->payment_status = 0;
        $Payment->save(false);
        Message::result_json(1,'添加成功');

    }

    public function actionEdit($id){
      $this->layout = 'empty';
        $Payment = Payment::findone($id);
        return $this->render('create', ['payment'=>$Payment,'id'=>$id]);
    }

    public function actionUpdate($id){
        $Payment = Payment::findone($id);

        if (!$Payment->supplier_id) {
          Message::result_json(2,'供货商不能为空');
        }else{
          $supplier = Supplier::findone($Payment->supplier_id);
          $Payment->supplier_name = $supplier->supplier_name;
        }

        $Payment->remain_time = $Payment->remain_time?strtotime($Payment->remain_time):time();

        if ($Payment->order_id) {
          $purchase = Purchase::findone($Payment->order_id);
          if (!$purchase) {
            Message::result_json(2,'采购单不存在');
          }else{
            $Payment->supplier_id = $purchase->supplier_id;
            $Payment->supplier_name = $purchase->supplier_name;
            $Payment->relate_order_sn = $purchase->order_sn;
            $Payment->model = 'Purchase';
          }
          //计算收款单总金额 收款单总金额不能大于销售单金额
          $sum_total = Payment::find()->where(['order_id'=>$Payment->order_id,'model'=>'Purchase'])->sum('fee');
          $sum_total += $Payment->fee;
          if ($sum_total > $purchase->total) {
            Message::result_json(2,'付款单总金额不能大于采购单金额');
          }
        }

        $Payment->save(false);
        Message::result_json(2,'编辑成功');
    }


    public function actionView($id){
        $this->layout = 'empty';
        $Payment = Payment::findone($id);
        return $this->render('create', ['payment'=>$Payment,'id'=>$id]);
    }    

    public function actionAdmit($id,$process_status){
      //修改状态
      $Payment = Payment::findone($id);
      if ($Payment->status_done >= 1) {
          Message::result_json(2,'单据已复核!');
      }

      //检查相关销售单据金额 和 全部收款单金额 对比 如果金额相同 则 设置销售单付款成功
      if ($Payment->order_id) {
        $Purchase = Purchase::findone($Payment->order_id);
        $payment_total = Payment::find()->where(['order_id'=>$Payment->order_id,'model'=>$Payment->model])->sum('fee');
        if ($Purchase && $Purchase->total) {
          if ($Purchase->total - $payment_total < 1) {
            $Purchase->pay_status=  1;
            $Purchase->status_name =  '已复核';
            $Purchase->status_done=  1;
            $Purchase->save(false);
          }
        }
      }

      $Payment->admit_user_id = Yii::$app->session['manage_user']['id'];
      $Payment->admit_user_name = Yii::$app->session['manage_user']['admin_name'];
      $Payment->admit_time = time();
      $Payment->payment_status = 1;
      $Payment->status_name = '已复核';
      $Payment->status_done = '1';
      $Payment->save(false);
      
      Message::result_json(1,'复核成功');

    }


}
