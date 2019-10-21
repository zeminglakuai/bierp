<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Custom;
use app\common\models\Admin;
use app\common\models\UserExtendInfo;
use app\includes\Message;
use app\controllers\BaseController;

class CustomContactController extends BaseController
{   
    public function actionIndex()
    {	          
  		return $this->render('index', []);
    }

    //查看/编辑供货商联系人
    public function actionEdit($id){
      $this->layout = 'empty';
      $supplier_contact = Admin::find()->where(['id'=>$id])->andwhere('supplier_id > 0')->one();

      return $this->render('view', [
                  'supplier_contact' => $supplier_contact,
      ]);
 
    }

    //添加供货商联系人
    public function actionCreate(){
        $this->layout = 'empty';
        return $this->render('view', []);
    }

    //插入供货商
    public function actionInsert(){

        $supplier_contact = new Admin();
        $supplier_contact->load(Yii::$app->request->post());
        
        if (strlen($supplier_contact->admin_name) <= 2) {
          Message::result_json(2,'请填写联系人名称');
        }

        if (strlen($supplier_contact->real_name) <= 2) {
          Message::result_json(2,'请填写联系人姓名');
        }

        if ($supplier_contact->supplier_id <= 0) {
          Message::result_json(2,'请填写联系人所属供货商');
        }else{
          $supplier = Supplier::findone($supplier_contact->supplier_id);
          $supplier_contact->supplier_name = $supplier->supplier_name;
        }

        $supplier_contact->save(false);
        
        $UserExtendInfo = Yii::$app->request->post('UserExtendInfo');
        if ($UserExtendInfo['filed_id']) {
           foreach ($UserExtendInfo['filed_id'] as $key => $value) {
              $contact_extend = new UserExtendInfo();
              $contact_extend->filed_id = $value;
              $contact_extend->filed_value = $UserExtendInfo['filed_value'][$key];
              $contact_extend->contact_id = $supplier_contact->id;
              $contact_extend->save(false);
           }
        }
 

        Message::result_json(1,'添加成功');

    }

    //更新供货商
    public function actionUpdate($id){

      $supplier_contact = Admin::findone($id);
      $supplier_contact->load(Yii::$app->request->post());

      if (strlen($supplier_contact->admin_name) <= 2) {
        Message::result_json(2,'请填写联系人名称');
      }

      if (strlen($supplier_contact->real_name) <= 2) {
        Message::result_json(2,'请填写联系人姓名');
      }

      if ($supplier_contact->supplier_id <= 0) {
        Message::result_json(2,'请填写联系人所属供货商');
      }else{
        $supplier = Supplier::findone($supplier_contact->supplier_id);
        $supplier_contact->supplier_name = $supplier->supplier_name;
      }

      $supplier_contact->save(false);

      $ContactExtendInfo = Yii::$app->request->post('ContactExtendInfo');
      if ($ContactExtendInfo['filed_id']) {
         foreach ($ContactExtendInfo['filed_id'] as $key => $value) {
            if ($ContactExtendInfo['id'][$key] > 0) {
              $contact_extend = ContactExtendInfo::findone($ContactExtendInfo['id'][$key]);
              if ($contact_extend) {
                $contact_extend->filed_id = $value;
                $contact_extend->filed_value = $ContactExtendInfo['filed_value'][$key];
                $contact_extend->contact_id = $supplier_contact->id;
                $contact_extend->save(false);
              }else{
                $contact_extend = new ContactExtendInfo();
                $contact_extend->filed_id = $value;
                $contact_extend->filed_value = $ContactExtendInfo['filed_value'][$key];
                $contact_extend->contact_id = $supplier_contact->id;
                $contact_extend->save(false);
              }
            }else{
              $contact_extend = new ContactExtendInfo();
              $contact_extend->filed_id = $value;
              $contact_extend->filed_value = $ContactExtendInfo['filed_value'][$key];
              $contact_extend->contact_id = $supplier_contact->id;
              $contact_extend->save(false);
            }

         }
      }

      message::result_json(1,'更新成功');
    }

    //更新供货商信息
    public function actionUpdateSupplierInfo(){
        $id = is_numeric(Yii::$app->request->get('id'))?Yii::$app->request->get('id'):0;
        $value = Yii::$app->request->get('value')?trim(Yii::$app->request->get('value')):'';
        $type = Yii::$app->request->get('type')?trim(Yii::$app->request->get('type')):'';

        $supplier = Supplier::findone($id);
        if (isset($supplier->id) || strlen($value) > 0) {

            $supplier->$type = $value;
            if (empty($supplier->$type)) {
               message::result_json(2,'值不允许为空');
            }
            $supplier->save(false);

            message::result_json(1,'修改成功',$value);
        }else{
          message::result_json(2,'缺少参数');
        }
    }

    //删除供货商信息
    public function actionDelete(){
        $id = is_numeric(Yii::$app->request->get('id'))?Yii::$app->request->get('id'):0;
        //检查供货商 是不是有提供商品
        $if_check = SupplierGoods::find()->where(['supplier_id'=>$id])->asarray()->all();
        if (count($if_check) > 0) {
          message::show_message('供货商下已经有商品，不允许删除',[],'error');
        }
        $supplier = Supplier::findone($id);
        if (isset($supplier->id)) {
            $supplier->delete(false);
            message::show_message('删除成功');
        }else{
          message::show_message('缺少参数',[],'error');
        }
    }
 
}
