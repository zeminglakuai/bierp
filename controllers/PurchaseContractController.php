<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Supplier;
use app\common\models\Contract;
use app\common\models\Purchase; 

use app\common\models\Datum;
use app\common\models\UploadForm;
use app\common\models\FileInfo;
use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class PurchaseContractController extends BaseController
{
    public $page_title = '采购合同';
    public $title_model = 'app\common\models\Contract';
    public $status_label = 'contract_status';
    
    public function beforeAction($action)
    {
      parent::beforeAction($action);
      //检查当前单据用户是不是有操作权限

      $need_privi_arr = ['delete','edit','admit',];
      $admit_allow_arr = ['edit','view','contract-file'];
      $scope_model = 'app\common\models\Contract';
      $status_label = 'contract_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }


    //添加
    public function actionCreate(){
        $this->layout = 'empty';
        return $this->render('create', []);
    }

    //删除
    public function actionDelete($id){
      $sell_contract = Contract::findone($id);
      if ($sell_contract) {
        $sell_contract->delete();
        message::result_json(1,'删除成功');
      }else{
        message::result_json(2,'单据不存在');
      }
    }

    //添加
    public function actionInsert(){
        $Contract = new Contract();
        $Contract->load(Yii::$app->request->post());

        if (isset($Contract->order_id)) {
          //检查合同是否重复
          $if_cunzai = Contract::find()->where(['order_id'=>$Contract->order_id,'type'=>2])->one();
          if ($if_cunzai) {
            Message::result_json(2,'该采购单已经存在销售合同!');
          }

          $purchase = Purchase::findone($Contract->order_id);

          if (!$purchase) {
            Message::result_json(2,'采购单不存在');
          }else{
            $Contract->relate_order_sn = $purchase->order_sn;
            $Contract->supplier_id = $purchase->supplier_id;
            $Contract->supplier_name = $purchase->supplier_name;
          }
        }

        if (isset($Contract->template_id)) {
          $datum = Datum::findone($Contract->template_id);
          if ($datum) {
            $Contract->content = $datum->content;

            //处理合同中的字段
            $contract_arr = explode("{{", $datum->content);
            $param_arr = [];
            foreach ($contract_arr as $key => $value) {
              if (strpos($value,"}}") > 0) {
                $param_arr[] = ['label_name'=>substr($value,0, strpos($value,"}}")),'label_value'=>substr($value,0, strpos($value,"}}"))];
              }
            }

            $Contract->param = serialize($param_arr);


            $Contract->template_name = $datum->datum_name;
          }
        }

        $Contract->order_sn = Common_fun::create_sn('app\common\models\Contract',5);
        $Contract->type = 2;
        $Contract->contract_status = 0;
        $Contract->save(false);
        Message::result_json(2,'添加成功');
    }

    public function actionEdit($id){
      $this->layout = 'empty';
        $Contract = Contract::findone($id);
        $pay_type=[
          '1'=>'多次付款',
            '2'=>'一次付款',
            '3'=>'月结商户',
        ];
        return $this->render('edit', ['contract'=>$Contract,'id'=>$id,'$ptype'=>$pay_type]);
    }

    public function actionUpdate($id){
        $Contract = Contract::findone($id);

        if (!$Contract->custom_id) {
          Message::result_json(2,'客户不能为空');
        }

        $custom = Custom::findone($Contract->custom_id);
        if (!$custom) {
          Message::result_json(2,'客户不存在，请重新选择');
        }else{
          $Contract->custom_name = $custom->custom_name;
        }

        if (isset($Contract->order_id)) {
          //检查合同是否重复
          $if_cunzai = Contract::find()->where(['order_id'=>$Contract->order_id,'type'=>1,'id <> '.$id])->one();
          if ($if_cunzai) {
            Message::result_json(2,'该销售单已经存在销售合同!');
          }

          $SellOrder = SellOrder::findone($Contract->order_id);
          if (!$SellOrder) {
            Message::result_json(2,'销售单不存在');
          }else{
            $Contract->relate_order_sn = $SellOrder->order_sn;
          }
        }

        if (isset($Contract->template_id)) {
          $datum = Datum::findone($Contract->template_id);
          if ($datum) {
            $Contract->content = $datum->content;
          }
        }

        $Contract->save(false);
        Message::result_json(2,'编辑成功');
    }


    public function actionView($id){
      $Contract = Contract::findone($id);
      //处理合同中的字段
      $param_arr = unserialize($Contract->param);

      foreach ($param_arr as $key => $value) {
          $Contract->content = str_replace("{{".$value['label_name']."}}", "<span class='contract_editable' data-name='".$value['label_name']."'>".$value['label_value']."</span>", $Contract->content);
      }
      return $this->render('view', ['contract'=>$Contract,'id'=>$id]);
    }

    public function actionAdmit($id,$process_status){
      //修改状态
      $Contract = Contract::findone($id);

      $admit_result = $this->base_admit($Contract,'contract_status',$process_status);
      if ($admit_result['error'] > 2) {
        Message::result_json(2,$admit_result['message']);
      }

      Message::result_json(1,'复核成功');

    }

    public function actionContractFile(){
      $id = Yii::$app->request->get('id');
      $contract = Contract::findone($id);
      if (!$contract) {
        Message::result_json(2,'合同不存在!');
      }

      $upload_result = UploadForm::upload_mul();
      if ($upload_result && is_array($upload_result)) {
        if (count($upload_result['file']) > 0) {
          foreach ($upload_result['file'] as $key => $value) {
            $FileInfo = new FileInfo();
            $FileInfo->belong_id = $id;
            $FileInfo->file_path = $value['file_name'];
            $FileInfo->file_desc = $value['file_desc'];
            $FileInfo->model = 'contract';
            $FileInfo->save(false);
          }
        }
      }else{
        Message::result_json(2,'请选择要上传的文件!');
      }
      if (isset($upload_result['error']) && count($upload_result['error']) > 0) {
        $upload_info = '';
        foreach ($upload_result['error'] as $key => $value) {
          $upload_info .=  $value.'<br>';
        }
      }
      Message::result_json(1,'添加成功'.$upload_info);
    } 


    public function actionUpdateLabel($id){

      $value  = trim(Yii::$app->request->get('value',''));
      $data_name  = Yii::$app->request->get('data_name');

      if (empty($value)) {
         message::result_json(2,'不允许空');
      }

      $contract = Contract::findone($id);
      $param_arr = @unserialize($contract->param);
      foreach ($param_arr as $key => $vv) {
        if ($vv['label_name'] == $data_name) {
          $param_arr[$key]['label_value'] = $value;
          $contract->param = serialize($param_arr);
          $contract->save(false);

          //记录修改记录
 

          message::result_json(1,'success',$value);
          break;
        }
      }

      message::result_json(2,'不存在该值');
    }    

    public function actionKeepContract($id){
      $this->layout = 'empty';
        $Contract = Contract::findone($id);
        //处理合同中的字段

        return $this->render('keep-contract', ['contract'=>$Contract,'id'=>$id]);
    }

    public function actionActKeepContract($id){
        $Contract = Contract::findone($id);
        $Contract->load(Yii::$app->request->post());
        if ($Contract->keeper_user_id) {
          $admin = Admin::findone($Contract->keeper_user_id);
          $Contract->keeper_user_name = $admin->admin_name;
          $Contract->keep_time = time();
        }else{
          message::result_json(2,'请填写保存合同人');
        }

        $Contract->save(false);
        message::result_json(1,'success');
    }
}
