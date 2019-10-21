<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Custom;
use app\includes\Message;
use app\controllers\BaseController;
use yii\web\UploadedFile;
use app\common\models\UploadForm;
use app\common\models\Contact;
use app\common\models\Address;
use app\common\models\Consignee;
use app\common\models\FileInfo;
use app\common\models\UserExtendInfo;

class CustomController extends BaseController
{   

    public function actionIndex()
    {
      return $this->render('index', []);
    }

    //添加客户
    public function actionCreate(){
        $this->layout = 'empty';
        return $this->render('view', []);
    }

    //插入客户方案
    public function actionInsert(){

        $Custom = new Custom();
        $Custom->load(Yii::$app->request->post());
        
        if (strlen($Custom->custom_name) < 2) {
          Message::result_json(2,'客户名称不能为空');
        }
        $Custom->save(false);

        $upload_result = UploadForm::upload_files();
        if ($upload_result && is_array($upload_result)) {
          if (count($upload_result['file']) > 0) {
            foreach ($upload_result['file'] as $key => $value) {
              $FileInfo = new FileInfo();
              $FileInfo->belong_id = $Custom->id;
              $FileInfo->file_path = $value['file_name'];
              $FileInfo->file_desc = $value['file_desc'];
              $FileInfo->model = 'custom';
              $FileInfo->save(false);
            }
          }
        }

        if (isset($upload_result['error']) && count($upload_result['error']) > 0) {
          $upload_info = '';
          foreach ($upload_result['error'] as $key => $value) {
            $upload_info .=  $value.'<br>';
          }
        }
        Message::result_json(1,'添加成功'.$upload_info);
    }

    //编辑
    public function actionEdit($id){
        $custom = Custom::findone($id);
        return $this->render('view', ['custom'=>$custom]);
    }

    //编辑
    public function actionView($id){

        $custom = Custom::findone($id);
        return $this->render('view', ['custom'=>$custom]);
    }
    
    public function actionUpdate($id){
        $Custom = Custom::findone($id);
        if ($Custom) {
          $Custom->load(Yii::$app->request->post());

          if (empty($Custom->custom_name)) {
              message::result_json(2,'请填写名称');
          }

        $upload_result = UploadForm::upload_files();
        if ($upload_result && is_array($upload_result)) {
          if (count($upload_result['file']) > 0) {
            foreach ($upload_result['file'] as $key => $value) {
              $FileInfo = new FileInfo();
              $FileInfo->belong_id = $Custom->id;
              $FileInfo->file_path = $value['file_name'];
              $FileInfo->file_desc = $value['file_desc'];
              $FileInfo->model = 'custom';
              $FileInfo->save(false);
            }
          }
        }

          $Custom->save(false);
          message::result_json(1,'编辑成功');
        }else{
          message::result_json(1,'参数错误');
        }
    }

    public function actionDeleteImg($id){
      $custom_img = CustomImg::findone($id);
      if ($custom_img) {
        $custom_img->delete();
        message::result_json(1,'删除成功!');
      }else{
        message::result_json(2,'找不到数据!');
      }
    }

    //
    public function actionDelete($id){
        $Custom = Custom::findone($id);
        if ($Custom) {
          $Custom->is_delete = 1;
          $Custom->save(false);
          message::result_json(1,'删除成功');
        }else{
          message::result_json(2,'参数错误');
        }
    }


    //添加收货信息
    public function actionCreateConsignee($id){
      $this->layout = 'empty';

      return $this->render('create-consignee', ['id'=>$id]);
    }

    //添加收货信息
    public function actionInsertConsignee($id){
      $consignee = new Consignee;
      $consignee->load(Yii::$app->request->post());

      if (isset($consignee->consignee) && strlen($consignee->consignee) > 2) {
        $consignee->belong_id = $id;
        $consignee->model = 'custom';
        $consignee->save(false);
        message::result_json(1,'添加成功');
      }else{
        message::result_json(2,'请填写收货人！');
      }
    }

    //编辑收货信息
    public function actionEditConsignee($id,$consignee_id){
      $this->layout = 'empty';
      $consignee = Consignee::findone($consignee_id);
      return $this->render('create-consignee', ['consignee'=>$consignee,'id'=>$consignee->belong_id]);
    }

    //添加收货信息
    public function actionUpdateConsignee($id,$consignee_id){
      $consignee = Consignee::findone($consignee_id);
      $consignee->load(Yii::$app->request->post());

      if (isset($consignee->consignee) && strlen($consignee->consignee) > 2) {
        $consignee->save(false);
        message::result_json(1,'编辑成功');
      }else{
        message::result_json(2,'请填写收货人！');
      }
    }

    public function actionDeleteConsignee($id,$consignee_id){
      $consignee = Consignee::find()->where(['id'=>$consignee_id,'belong_id'=>$id])->one();
      if ($consignee) {
        $consignee->delete();
        message::result_json(1,'删除成功');
      }else{
        message::result_json(2,'数据不存在');
      }
    }



    //添加联系人
    public function actionCreateContact($id){
      $this->layout = 'empty';
      return $this->render('create-contact', ['id'=>$id]);
    }

    //添加联系人
    public function actionInsertContact($id){
        $contact = new Contact();
        $contact->load(Yii::$app->request->post());
        
        if (strlen($contact->name) <= 2) {
          Message::result_json(2,'请填写联系人名称');
        }

        $contact->belong_id = $id;
        $custom = Custom::findone($contact->belong_id);
        $contact->belong_name = $custom->custom_name;
        $contact->model = 'custom';

        $contact->save(false);
        
        $ContactExtendInfo = Yii::$app->request->post('ContactExtendInfo');
        if ($ContactExtendInfo['filed_id']) {
           foreach ($ContactExtendInfo['filed_id'] as $key => $value) {
              $contact_extend = new UserExtendInfo();
              $contact_extend->filed_id = $value;
              $contact_extend->filed_value = $ContactExtendInfo['filed_value'][$key];
              $contact_extend->contact_id = $contact->id;
              $contact_extend->save(false);
           }
        }
        Message::result_json(1,'添加成功');
    }

    //编辑
    public function actionEditContact($id){
      $this->layout = 'empty';
      $contact_id = Yii::$app->request->get('contact_id',0);
      $contact = Contact::find()->where(['belong_id'=>$id,'id'=>$contact_id])->one();
      return $this->render('create-contact', ['contact'=>$contact,'id'=>$contact->belong_id]);
    }

    // 
    public function actionUpdateContact($id,$contact_id){
        $contact = Contact::find()->where(['belong_id'=>$id,'id'=>$contact_id])->one();
        $contact->load(Yii::$app->request->post());
        
        if (strlen($contact->name) <= 2) {
          Message::result_json(2,'请填写联系人名称');
        }

        $contact->belong_id = $id;
        $Custom = Custom::findone($contact->belong_id);
        $contact->belong_name = $Custom->custom_name;
        $contact->model = 'custom';

        $contact->save(false);
        
        $ContactExtendInfo = Yii::$app->request->post('ContactExtendInfo');
        if ($ContactExtendInfo['filed_id']) {
           foreach ($ContactExtendInfo['filed_id'] as $key => $value) {
              if (isset($ContactExtendInfo['id'][$key]) && $ContactExtendInfo['id'][$key] > 0) {
                $contact_extend = UserExtendInfo::find()->where(['id'=>$ContactExtendInfo['id'][$key]])->one();
                $contact_extend = $contact_extend?$contact_extend:new UserExtendInfo();

                $contact_extend->filed_id = $value;
                $contact_extend->filed_value = $ContactExtendInfo['filed_value'][$key];
                $contact_extend->contact_id = $contact->id;
                $contact_extend->save(false);
              }else{
                $contact_extend = new UserExtendInfo();
                $contact_extend->filed_id = $value;
                $contact_extend->filed_value = $ContactExtendInfo['filed_value'][$key];
                $contact_extend->contact_id = $contact->id;
                $contact_extend->save(false);
              }

           }
        }
        Message::result_json(1,'添加成功');
    }

    public function actionDeleteContact($id,$contact_id){
      $Contact = Contact::find()->where(['id'=>$contact_id,'belong_id'=>$id])->one();
      if ($Contact) {
        $Contact->delete();
        message::result_json(1,'删除成功');
      }else{
        message::result_json(2,'数据不存在');
      }
    }



}
