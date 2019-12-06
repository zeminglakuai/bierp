<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;

use app\common\models\AcceptInvoice;
use app\common\models\Supplier;
use app\common\models\Purchase;
use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class ServiceController extends BaseController
{
    public $page_title = '进项发票';
    public $title_model = 'app\common\models\AcceptInvoice';
    public $status_label = 'accept_invoice_status';
    
    public function beforeAction($action)
    {
      parent::beforeAction($action);

      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','update','admit','insert-goods','delete-goods','update-goods-label'];
      $admit_allow_arr = ['edit','view'];

      $scope_model = 'app\common\models\AcceptInvoice';
      $status_label = 'accept_invoice_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }
    
    //删除
    public function actionDelete($id){
      $AcceptInvoice = AcceptInvoice::findone($id);
      $AcceptInvoice->delete();
      message::result_json(1,'删除成功');
    }

    //添加
    public function actionInsert(){
      $AcceptInvoice = new AcceptInvoice();
      $AcceptInvoice->load(Yii::$app->request->post());

      if (!$AcceptInvoice->fee || $AcceptInvoice->fee <= 0) {
        Message::result_json(2,'请输入发票金额');
      }

      if (!$AcceptInvoice->supplier_id) {

        Message::result_json(2,'供货商不能为空');
      }else{
        $supplier = Supplier::findone($AcceptInvoice->supplier_id);
        $AcceptInvoice->supplier_name = $supplier->supplier_name;
      }

      $AcceptInvoice->remain_time = $AcceptInvoice->remain_time?strtotime($AcceptInvoice->remain_time):time();
      $AcceptInvoice->invoice_time = $AcceptInvoice->invoice_time?strtotime($AcceptInvoice->invoice_time):time();

      if ($AcceptInvoice->invoice_rate) {
        $AcceptInvoice->invoice_rate_fee = round($AcceptInvoice->invoice_rate*$AcceptInvoice->fee/100,2);
      }

      if ($AcceptInvoice->order_id) {
        $Purchase = Purchase::findone($AcceptInvoice->order_id);
        if (!$Purchase) {
          Message::result_json(2,'采购单不存在');
        }else{
          $AcceptInvoice->supplier_id = $Purchase->supplier_id;
          $AcceptInvoice->supplier_name = $Purchase->supplier_name;
          $AcceptInvoice->relate_order_sn = $Purchase->order_sn;
          $AcceptInvoice->model = 'Purchase';
          $AcceptInvoice->order_total = $Purchase->total;
        }
        //计算收款单总金额 收款单总金额不能大于销售单金额
        $sum_total = AcceptInvoice::find()->where(['order_id'=>$AcceptInvoice->order_id,'model'=>'SellOrder'])->sum('fee');
        $sum_total += $AcceptInvoice->fee;
        if ($sum_total > $Purchase->total) {
          Message::result_json(2,'发票总金额不能大于采购单金额');
        }
      }

      $AcceptInvoice->accept_invoice_status = 0;
      $AcceptInvoice->order_sn = Common_fun::create_sn('app\common\models\AcceptInvoice',5);
      
      $AcceptInvoice->save(false);
      Message::result_json(1,'添加成功');

    }

    public function actionView($id){
       $this->layout = 'empty';
        $AcceptInvoice = AcceptInvoice::findone($id);
        return $this->render('create', ['accept_invoice'=>$AcceptInvoice,'id'=>$id]);
    }

 

    public function actionUpdate($id){
      $AcceptInvoice = AcceptInvoice::findone($id);
      $AcceptInvoice->load(Yii::$app->request->post());

      if (!$AcceptInvoice->fee || $AcceptInvoice->fee <= 0) {
        Message::result_json(2,'请输入发票金额');
      }

      if (!$AcceptInvoice->supplier_id) {
        Message::result_json(2,'客户不能为空');
      }else{
        $supplier = Supplier::findone($AcceptInvoice->supplier_id);
        $AcceptInvoice->supplier_name = $supplier->supplier_name;
      }

      $AcceptInvoice->remain_time = $AcceptInvoice->remain_time?strtotime($AcceptInvoice->remain_time):time();
      $AcceptInvoice->invoice_time = $AcceptInvoice->invoice_time?strtotime($AcceptInvoice->invoice_time):time();

      if ($AcceptInvoice->invoice_rate) {
        $AcceptInvoice->invoice_rate_fee = round($AcceptInvoice->invoice_rate*$AcceptInvoice->fee/100,2);
      }

      if ($AcceptInvoice->order_id) {
        $Purchase = Purchase::findone($AcceptInvoice->order_id);
        if (!$Purchase) {
          Message::result_json(2,'采购单不存在');
        }else{
          $AcceptInvoice->supplier_id = $Purchase->supplier_id;
          $AcceptInvoice->supplier_name = $Purchase->supplier_name;
          $AcceptInvoice->relate_order_sn = $Purchase->order_sn;
          $AcceptInvoice->model = 'Purchase';
          $AcceptInvoice->order_total = $Purchase->total;
        }
        //计算收款单总金额 收款单总金额不能大于销售单金额
        $sum_total = AcceptInvoice::find()->where(['order_id'=>$AcceptInvoice->order_id,'model'=>'SellOrder'])->andwhere(['<>','id',$id])->sum('fee');
        $sum_total += $AcceptInvoice->fee;
        if ($sum_total > $Purchase->total) {
          Message::result_json(2,'发票总金额不能大于采购单金额');
        }
      }

      $AcceptInvoice->save(false);
      Message::result_json(1,'编辑成功');
    }

    public function actionAdmit($id){
      //修改订单状态
      $AcceptInvoice = AcceptInvoice::findone($id);
      if ($AcceptInvoice->accept_invoice_status >= 1) {
          Message::result_json(2,'单据已复核!');
      }

      //检查相关采购单据金额 和 全部发票单金额 对比 如果金额相同 则 设置采购单发票状态成功
      if ($AcceptInvoice->order_id) {
        $Purchase = Purchase::findone($AcceptInvoice->order_id);
        $purchase_total = AcceptInvoice::find()->where(['order_id'=>$AcceptInvoice->order_id,'model'=>$AcceptInvoice->model])->sum('fee');
        if ($Purchase && $Purchase->total) {
          if ($Purchase->total - $purchase_total < 1) {
            $Purchase->invoice_status=  1;
            $Purchase->save(false);
          }
        }
      }

      $AcceptInvoice->admit_user_id = Yii::$app->session['manage_user']['id'];
      $AcceptInvoice->admit_user_name = Yii::$app->session['manage_user']['admin_name'];
      $AcceptInvoice->admit_time = time();
      $AcceptInvoice->accept_invoice_status = 1;
      $AcceptInvoice->save(false);
      Message::result_json(1,'复核成功');
    }
}
