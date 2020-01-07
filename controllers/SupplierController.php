<?php

namespace app\controllers;

use app\common\models\Goods;
use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Supplier;
use app\common\models\SupplierGoods;
use app\common\models\UploadForm;
use app\common\models\FileInfo;
use app\common\models\Consignee;
use app\common\models\Contact;
use app\common\models\UserExtendInfo;

use app\includes\Message;
use app\controllers\BaseController;
use yii\web\UploadedFile;


class SupplierController extends BaseController
{
  public $scope = true;
  public $search_allowed = ['category' => 3];
  public function beforeAction($action)
  {
    parent::beforeAction($action);

    //检查当前单据用户是不是有操作权限
    $need_privi_arr = ['delete', 'edit', 'admit', 'update-supplier-label', 'delete-contact', 'edit-contact', 'create-contact', 'update-contact', 'delete-consignee', 'edit-consignee', 'create-consignee', 'update-consignee'];
    $admit_allow_arr = ['delete', 'edit', 'admit', 'update', 'update-supplier-label', 'delete-contact', 'edit-contact', 'create-contact', 'update-contact', 'delete-consignee', 'edit-consignee', 'create-consignee', 'update-consignee'];

    $scope_model = 'app\common\models\Supplier';
    $status_label = 'supplier_status';

    if ($this->user_scope($action, $need_privi_arr, $admit_allow_arr, $scope_model, $status_label)) {
      return true;
    } else {
      return false;
    }
  }



  //查看/编辑供货商
  public function actionEdit($id)
  {
    $present_panel = Yii::$app->request->get('present_panel', 0);
    $supplier = Supplier::findone($id);
    if (!$supplier) {
      message::show_message('供货商不存在', [], 'error');
    }

    $consignee_list = Consignee::findAll(['belong_id' => $id, 'model' => 'SUPPLIER']);
    return $this->render('view', [
      'supplier' => $supplier,
      'present_panel' => $present_panel,
      'consignee_list' => $consignee_list,
    ]);
  }

  //添加供货商
  public function actionCreate()
  {
    $this->layout = 'empty';
    return $this->render('view', []);
  }

  //插入供货商
  public function actionInsert()
  {

    $supplier = new Supplier();
    $supplier->load(Yii::$app->request->post());

    if ($supplier->tel == '' || $supplier->contact == '') {
      Message::result_json(2, '联系人和联系电话不能为空');
    }
    if ($supplier->bank_payee == '' || $supplier->bank_code == '' || $supplier->bank_name == '') {
      Message::result_json(2, '财务信息不能为空');
    }
    if (strlen($supplier->supplier_name) > 2) {
      
      $supplier->status_done = 0;

      $supplier->add_user_id = Yii::$app->session['manage_user']['add_user_id'];
      $supplier->add_user_name = Yii::$app->session['manage_user']['add_user_name'];
      $supplier->depart_id = Yii::$app->session['manage_user']['depart_id'];
      $supplier->depart_name = Yii::$app->session['manage_user']['depart_name'];
      $supplier->add_time = time();
      $supplier->save(false);

      $upload_result = UploadForm::upload_files();
      if ($upload_result && is_array($upload_result)) {
        if (count($upload_result['file']) > 0) {
          foreach ($upload_result['file'] as $key => $value) {
            $FileInfo = new FileInfo();
            $FileInfo->belong_id = $supplier->id;
            $FileInfo->file_path = $value['file_name'];
            $FileInfo->file_desc = $value['file_desc'];
            $FileInfo->type      = $value['file_type'];
            $FileInfo->model = 'supplier';
            $FileInfo->save(false);
          }
        }
      }
      //摘取联系人信息 生成首位联系人
      if ($supplier->contact) {
        $supplier_contact = new Contact();
        $supplier_contact->name = $supplier->contact;
        $supplier_contact->tel = $supplier->tel;
        $supplier_contact->is_active = 1;

        $supplier_contact->belong_id = $supplier->id;
        $supplier_contact->belong_name = $supplier->supplier_name;
        $supplier_contact->model = 'supplier';
        $supplier_contact->save(false);
      }

      Message::result_json(1, '添加成功');
    } else {
      Message::result_json(2, '供货商名称不能为空');
    }
  }
  //更新供货商
  public function actionUpdate($id)
  {

    if ($id) {
      $supplier = Supplier::findone($id);
      $supplier->load(Yii::$app->request->post());

      if (empty($supplier->supplier_name)) {
        Message::show_message('请填写供货商名称', [], 'error');
      }

      $upload_result = UploadForm::upload_files();
      if ($upload_result && is_array($upload_result)) {
        if (count($upload_result['file']) > 0) {
          foreach ($upload_result['file'] as $key => $value) {
            $FileInfo = new FileInfo();
            $FileInfo->belong_id = $supplier->id;
            $FileInfo->file_path = $value['file_name'];
            $FileInfo->file_desc = $value['file_desc'];
            $FileInfo->type      = $value['file_type'];
            $FileInfo->model = 'supplier';
            $FileInfo->save(false);
          }
        }
      }
      $data = Yii::$app->db->createCommand("SELECT * FROM supplier where id=" . $id)->queryOne();
      $info = '';
      if (isset($data['old_back'])) {
        $info = @unserialize($data['old_back']);

        unset($data['old_back']);
        if (isset($info)) {
          $supplier->old_back = serialize(array_merge($data, $info));
        } else {
          $supplier->old_back = serialize($data);
        }
      } else {
        $supplier->old_back = serialize($data);
      }
      $supplier->supplier_status = 0;
      $supplier->save(false);

      message::result_json(1, '更新成功');
    } else {
      message::result_json(2, '供货商不存在');
    }
  }
  //    public function actionUpdate($id){
  //
  //      if ($id) {
  //        $supplier = Supplier::findone($id);
  //        /*保存旧ID*/
  //          $supplier->old_id=$id;
  //
  //          $supplier->save(false);
  //          /*重新添加一条数据*/
  //          $supplier = new Supplier();
  //          $supplier->old_id=0;
  //        $supplier->load(Yii::$app->request->post());
  //
  //        if (empty($supplier->supplier_name)) {
  //          Message::show_message('请填写供货商名称',[],'error');
  //        }
  //          $supplier->status_done=0;
  //          $supplier->supplier_status = 0;
  //          $upload_result = UploadForm::upload_files();
  //          /*开启事务*/
  //          $transaction = Yii::$app->db->beginTransaction();
  //          try {
  //              $supplier->insert(false);
  //              if ($upload_result && is_array($upload_result)) {
  //                  if (count($upload_result['file']) > 0) {
  //                      foreach ($upload_result['file'] as $key => $value) {
  //                          $FileInfo = new FileInfo();
  //                          $FileInfo->belong_id = $supplier->id;
  //                          $FileInfo->file_path = $value['file_name'];
  //                          $FileInfo->file_desc = $value['file_desc'];
  //                          $FileInfo->type      = $value['file_type'];
  //                          $FileInfo->model = 'supplier';
  //                          $FileInfo->save(false);
  //                      }
  //                  }
  //              }
  //              $transaction->commit();
  //          } catch (Exception $e) {
  //              $transaction->rollBack();
  //          }
  //
  //
  //
  //
  //
  //        message::result_json(1,'更新成功');
  //      }else{
  //        message::result_json(2,'供货商不存在');
  //      }
  //    }

  //更新供货商信息
  public function actionUpdateSupplierInfo()
  {
    $id = is_numeric(Yii::$app->request->get('id')) ? Yii::$app->request->get('id') : 0;
    $value = Yii::$app->request->get('value') ? trim(Yii::$app->request->get('value')) : '';
    $type = Yii::$app->request->get('type') ? trim(Yii::$app->request->get('type')) : '';

    $supplier = Supplier::findone($id);
    if (isset($supplier->id) || strlen($value) > 0) {

      $supplier->$type = $value;
      if (empty($supplier->$type)) {
        message::result_json(2, '值不允许为空');
      }
      $supplier->save(false);

      message::result_json(1, '修改成功', $value);
    } else {
      message::result_json(2, '缺少参数');
    }
  }
  public function actionAdmitList()
  {

    $confirm_list = Supplier::find()->where(['supplier_status' => 0])->all();

    return $this->render('confirm-list', [
      'confirm_list' => $confirm_list,
    ]);
  }
  //更新供货商信息
  public function actionAdmit($id)
  {
    $supplier = Supplier::findone($id);
    if ($supplier->supplier_status == 1) {
      message::result_json(2, '供货商已经审核通过！');
    } else {
      $supplier->supplier_status = 1;
      $supplier_id = $supplier->id;
      $transaction = Yii::$app->db->beginTransaction();

      $supplier->save(false);



      message::result_json(1, '审核通过', '', '');
    }
  }

  //删除供货商信息
  public function actionDelete($id)
  {
    //检查供货商 是不是有提供商品
    $if_check = SupplierGoods::find()->where(['supplier_id' => $id])->asarray()->all();
    if (count($if_check) > 0) {
      message::result_json(2, '供货商下已经有商品，不允许删除');
    }
    $supplier = Supplier::findone($id);
    if (isset($supplier->id)) {
      $supplier->delete(false);
      message::result_json(1, '删除成功');
    } else {
      message::result_json(2, '缺少参数');
    }
  }


  //添加收货信息
  public function actionCreateConsignee($id)
  {
    $this->layout = 'empty';

    return $this->render('create-consignee', ['id' => $id]);
  }

  //添加收货信息
  public function actionInsertConsignee($id)
  {
    $consignee = new Consignee;
    $consignee->load(Yii::$app->request->post());

    if (isset($consignee->consignee) && strlen($consignee->consignee) > 2) {
      $consignee->belong_id = $id;
      $consignee->model = 'SUPPLIER';
      $consignee->save(false);
      message::result_json(1, '添加成功');
    } else {
      message::result_json(2, '请填写收货人！');
    }
  }

  //编辑收货信息
  public function actionEditConsignee($id)
  {
    $this->layout = 'empty';
    $consignee_id = Yii::$app->request->get('consignee_id', 0);
    $consignee = Consignee::findone($consignee_id);
    return $this->render('create-consignee', ['consignee' => $consignee, 'id' => $consignee->belong_id]);
  }

  //添加收货信息
  public function actionUpdateConsignee($id, $consignee_id)
  {
    $consignee = Consignee::findone($consignee_id);
    $consignee->load(Yii::$app->request->post());

    if (isset($consignee->consignee) && strlen($consignee->consignee) > 2) {
      $consignee->save(false);
      message::result_json(1, '编辑成功');
    } else {
      message::result_json(2, '请填写收货人！');
    }
  }

  public function actionDeleteConsignee($id, $consignee_id)
  {
    $consignee = Consignee::find()->where(['id' => $consignee_id, 'belong_id' => $id])->one();
    if ($consignee) {
      $consignee->delete();
      message::result_json(1, '删除成功');
    } else {
      message::result_json(2, '数据不存在');
    }
  }



  //添加联系人
  public function actionCreateContact($id)
  {
    $this->layout = 'empty';
    return $this->render('create-contact', ['id' => $id]);
  }

  //添加联系人
  public function actionInsertContact($id)
  {
    $supplier_contact = new Contact();
    $supplier_contact->load(Yii::$app->request->post());

    if (strlen($supplier_contact->name) <= 2) {
      Message::result_json(2, '请填写联系人名称');
    }

    $supplier_contact->belong_id = $id;
    $supplier = Supplier::findone($supplier_contact->belong_id);
    $supplier_contact->belong_name = $supplier->supplier_name;
    $supplier_contact->model = 'supplier';

    $supplier_contact->save(false);

    $ContactExtendInfo = Yii::$app->request->post('ContactExtendInfo');
    if ($ContactExtendInfo['filed_id']) {
      foreach ($ContactExtendInfo['filed_id'] as $key => $value) {
        $contact_extend = new UserExtendInfo();
        $contact_extend->filed_id = $value;
        $contact_extend->filed_value = $ContactExtendInfo['filed_value'][$key];
        $contact_extend->contact_id = $supplier_contact->id;
        $contact_extend->save(false);
      }
    }
    Message::result_json(1, '添加成功');
  }

  //编辑
  public function actionEditContact($id)
  {
    $this->layout = 'empty';
    $contact_id = Yii::$app->request->get('contact_id', 0);
    $contact = Contact::find()->where(['belong_id' => $id, 'id' => $contact_id])->one();
    return $this->render('create-contact', ['contact' => $contact, 'id' => $contact->belong_id]);
  }

  // 
  public function actionUpdateContact($id, $contact_id)
  {
    $supplier_contact = Contact::find()->where(['belong_id' => $id, 'id' => $contact_id])->one();
    $supplier_contact->load(Yii::$app->request->post());

    if (strlen($supplier_contact->name) <= 2) {
      Message::result_json(2, '请填写联系人名称');
    }

    $supplier_contact->belong_id = $id;
    $supplier = Supplier::findone($supplier_contact->belong_id);
    $supplier_contact->belong_name = $supplier->supplier_name;
    $supplier_contact->model = 'supplier';

    $supplier_contact->save(false);

    $ContactExtendInfo = Yii::$app->request->post('ContactExtendInfo');
    if ($ContactExtendInfo['filed_id']) {
      foreach ($ContactExtendInfo['filed_id'] as $key => $value) {
        if (isset($ContactExtendInfo['id'][$key]) && $ContactExtendInfo['id'][$key] > 0) {
          $contact_extend = UserExtendInfo::find()->where(['id' => $ContactExtendInfo['id'][$key]])->one();
          $contact_extend = $contact_extend ? $contact_extend : new UserExtendInfo();

          $contact_extend->filed_id = $value;
          $contact_extend->filed_value = $ContactExtendInfo['filed_value'][$key];
          $contact_extend->contact_id = $supplier_contact->id;
          $contact_extend->save(false);
        } else {
          $contact_extend = new UserExtendInfo();
          $contact_extend->filed_id = $value;
          $contact_extend->filed_value = $ContactExtendInfo['filed_value'][$key];
          $contact_extend->contact_id = $supplier_contact->id;
          $contact_extend->save(false);
        }
      }
    }
    Message::result_json(1, '添加成功');
  }

  public function actionDeleteContact($id, $contact_id)
  {
    $Contact = Contact::find()->where(['id' => $contact_id, 'belong_id' => $id])->one();
    if ($Contact) {
      $Contact->delete();
      message::result_json(1, '删除成功');
    } else {
      message::result_json(2, '数据不存在');
    }
  }
}
