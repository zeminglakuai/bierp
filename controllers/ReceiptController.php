<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\SellOrder;
use app\common\models\SellOrderGoods;
use app\common\models\Receipt;
use app\common\models\Custom;
use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class ReceiptController extends BaseController
{
    public $page_title = '应付账款';
    public $title_model = 'app\common\models\Receipt';
    public $status_label = 'receipt_status';

    public function beforeAction($action)
    {
      parent::beforeAction($action);
      //检查当前单据用户是不是有操作权限

      $need_privi_arr = ['delete','edit','admit',];
      $admit_allow_arr = ['edit','view'];
      $scope_model = 'app\common\models\Receipt';
      $status_label = 'receipt_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }

    //添加
    public function actionCreate(){
        $this->layout = 'empty';
        return $this->render('create-receipt', []);
    }

    //删除
    public function actionDelete($id){
      $Receipt = Receipt::findone($id);
      if ($Receipt) {
        $Receipt->delete();
        message::result_json(1,'删除成功');
      }else{
        message::result_json(2,'单据不存在');
      }
    }

    //添加
    public function actionInsert(){
      $Receipt = new Receipt();
      $Receipt->load(Yii::$app->request->post());

      if (!$Receipt->fee || $Receipt->fee <= 0) {
        Message::result_json(2,'请输入收款金额');
      }

      if (!$Receipt->custom_id) {
        Message::result_json(2,'客户不能为空');
      }else{
        $custom = Custom::findone($Receipt->custom_id);
        $Receipt->custom_name = $custom->custom_name;
      }

      $Receipt->remain_time = $Receipt->remain_time?strtotime($Receipt->remain_time):time();

      if ($Receipt->order_id) {
        $sell_order = SellOrder::findone($Receipt->order_id);
        if (!$sell_order) {
          Message::result_json(2,'销售单不存在');
        }else{
          $Receipt->custom_id = $sell_order->custom_id;
          $Receipt->custom_name = $sell_order->custom_name;
          $Receipt->relate_order_sn = $sell_order->order_sn;
          $Receipt->model = 'SellOrder';
        }
        //计算收款单总金额 收款单总金额不能大于销售单金额
        $sum_total = Receipt::find()->where(['order_id'=>$Receipt->order_id,'model'=>'SellOrder'])->sum('fee');
        $sum_total += $Receipt->fee;
        if ($sum_total > $sell_order->total) {
          Message::result_json(2,'收款单总金额不能大于销售单金额');
        }
      }


      $Receipt->order_sn = Common_fun::create_sn('app\common\models\Receipt',5);
      
      $Receipt->save(false);
      Message::result_json(1,'添加成功');
    }

    public function actionEdit($id){
        $this->layout = 'empty';
        $Receipt = Receipt::findone($id);
        return $this->render('create-receipt', ['id'=>$id,'receipt'=>$Receipt]);
    }

    public function actionUpdate($id){
      $Receipt = Receipt::findone($id);
      $Receipt->load(Yii::$app->request->post());

      if (!$Receipt->fee || $Receipt->fee <= 0) {
        Message::result_json(2,'请输入收款金额');
      }

      if (!$Receipt->custom_id) {
        Message::result_json(2,'客户不能为空');
      }else{
        $custom = Custom::findone($Receipt->custom_id);
        $Receipt->custom_name = $custom->custom_name;
      }

      $Receipt->remain_time = $Receipt->remain_time?strtotime($Receipt->remain_time):time();

      if ($Receipt->order_id) {
        $sell_order = SellOrder::findone($Receipt->order_id);
        if (!$sell_order) {
          Message::result_json(2,'销售单不存在');
        }else{
          $Receipt->custom_id = $sell_order->custom_id;
          $Receipt->custom_name = $sell_order->custom_name;
          $Receipt->relate_order_sn = $sell_order->order_sn;
          $Receipt->model = 'SellOrder';
        }
        //计算收款单总金额 收款单总金额不能大于销售单金额
        $sum_total = Receipt::find()->where(['order_id'=>$Receipt->order_id,'model'=>'SellOrder'])->sum('fee');
        //$sum_total += $Receipt->fee;
        if ($sum_total > $sell_order->total) {
          Message::result_json(2,'收款单总金额不能大于销售单金额');
        }
      }

 
      
      $Receipt->save(false);
      Message::result_json(1,'编辑成功');
    }


    public function actionView($id){
        $this->layout = 'empty';
        $Receipt = Receipt::findone($id);
        return $this->render('create-receipt', ['id'=>$id,'receipt'=>$Receipt]);
    }    

    public function actionAdmit($id){
      //修改状态
      $Receipt = Receipt::findone($id);
      if ($Receipt->receipt_status >= 1) {
          Message::result_json(2,'单据已复核!');
      }

      //检查相关销售单据金额 和 全部收款单金额 对比 如果金额相同 则 设置销售单付款成功
      if ($Receipt->order_id) {
        $sell_order = SellOrder::findone($Receipt->order_id);
        $receipt_total = Receipt::find()->where(['order_id'=>$Receipt->order_id,'model'=>$Receipt->model])->sum('fee');
        if ($sell_order && $sell_order->total) {
          if ($sell_order->total - $receipt_total < 1) {
            $sell_order->pay_status=  1;
            $sell_order->save(false);
          }
        }
      }
      
      $Receipt->admit_user_id = Yii::$app->session['manage_user']['id'];
      $Receipt->admit_user_name = Yii::$app->session['manage_user']['admin_name'];
      $Receipt->admit_time = time();
      $Receipt->receipt_status = 1;
      $Receipt->status_name = '已复核';
      $Receipt->status_done = '1';
      $Receipt->save(false);
      Message::result_json(1,'复核成功');

    }

}
