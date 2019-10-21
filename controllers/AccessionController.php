<?php
namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;
use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Custom;
use app\common\models\Accession;
use app\common\models\Admin;
use yii\helpers\Url;
use app\includes\Message;
use app\controllers\BaseController;
use app\includes\Common_fun;
use app\common\models\UploadForm;
use app\common\models\FileInfo;

class AccessionController extends BaseController
{
    public $page_title = '入职管理';
    public $title_model = 'app\common\models\Accession';
    public $status_label = 'accession_status';
    
    public function beforeAction($action)
    {
      parent::beforeAction($action);
      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','admit','update','insert-goods','delete-goods','update-goods-label'];
      $admit_allow_arr = ['edit','view'];

      $scope_model = 'app\common\models\Accession';
      $status_label = 'accession_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }
 
    //删除
    public function actionDelete($id){
      $Accession = Accession::findone($id);
      $Accession->delete();
      message::result_json(1,'删除成功');
    }

    //添加
    public function actionInsert(){
        $Accession = new Accession();
        $Accession->load(Yii::$app->request->post());

        //检查申请人
        if (!$Accession->user_id) {
           Message::result_json(2,'请填写申请人');
        }

        $admin = Admin::findone($Trip->user_id);
        $Accession->real_name = $admin->real_name;
        $Accession->position = $admin->role->role_name;
        $Accession->save(false);

        $upload_result = UploadForm::upload_files();
        if ($upload_result && is_array($upload_result)) {
          if (count($upload_result['file']) > 0) {
            foreach ($upload_result['file'] as $key => $value) {
              $FileInfo = new FileInfo();
              $FileInfo->belong_id = $Accession->id;
              $FileInfo->file_path = $value['file_name'];
              $FileInfo->file_desc = $value['file_desc'];
              $FileInfo->type      = $value['file_type'];
              $FileInfo->model = 'accession';
              $FileInfo->save(false);
            }
          }
        }

        
        Message::result_json(1,'添加成功');
    }

    public function actionView($id){
        $this->layout = 'empty';
        $Accession = Accession::findone($id);
        return $this->render('create', ['accession'=>$Accession,'id'=>$id]);
    }
 

    public function actionUpdate($id){
      $MaterielRequest = MaterielRequest::findone($id);
      if ($MaterielRequest) {
        $MaterielRequest->load(Yii::$app->request->post());

        //检查申请人
        if (!$Accession->user_id) {
           Message::result_json(2,'请填写申请人');
        }

        $admin = Admin::findone($Trip->user_id);
        $Accession->real_name = $admin->real_name;
        $Accession->position = $admin->role->role_name;
        
        $upload_result = UploadForm::upload_files();
        if ($upload_result && is_array($upload_result)) {
          if (count($upload_result['file']) > 0) {
            foreach ($upload_result['file'] as $key => $value) {
              $FileInfo = new FileInfo();
              $FileInfo->belong_id = $Accession->id;
              $FileInfo->file_path = $value['file_name'];
              $FileInfo->file_desc = $value['file_desc'];
              $FileInfo->type      = $value['file_type'];
              $FileInfo->model = 'accession';
              $FileInfo->save(false);
            }
          }
        }

        $Trip->save(false);
        Message::result_json(1,'编辑成功');
      }else{
        Message::result_json(2,'编辑失败');
      }
    }

    public function actionAdmit($id,$process_status){
      //修改订单状态
      $MaterielRequest = MaterielRequest::findone($id);
      if ($MaterielRequest->specimen_purchase_status >= 1) {
          Message::result_json(2,'单据已复核!');
      }
 
      //检查商品进货价 和 数量 是不是齐备
      $order_goods = MaterielRequestGoods::find()->where(['order_id'=>$id])->all();
      if (!$order_goods) {
        Message::result_json(2,'物料不存在，不能复核！');
      }
      foreach ($order_goods as $key => $value) {
        if ($value->purchase_price <= 0 || $value->number <= 0) {
          Message::result_json(2,$value->materiel_name.'信息不完整！');
          break;
        }
      }
      
      $admit_result = $this->base_admit($MaterielRequest,'materiel_request_status',$process_status);
      if ($admit_result['error'] > 2) {
        Message::result_json(2,$admit_result['message']);
      }

      //复核成功  则声称物料采购单据
      if ($admit_result['error'] == 1) {
        
      }
      
      Message::result_json(1,'复核成功');

    }
 

    public function actionInsertMateriel(){
      $goods_id = Yii::$app->request->get('goods_id',0);
      $order_id = Yii::$app->request->get('order_id',0);
      $search_data = Yii::$app->request->get('search_data',0);

      if (!$order_id) {
        message::result_json(2,'数据错误');
      }else{
        $order = MaterielPurchase::findone($order_id);
      }

      $add_goods_error = [];
      if ($goods_id) {
          if (is_array($goods_id)) {
            foreach ($goods_id as $key => $value) {
              $goods = Materiel::findone($value);
              if ($goods) {
                //插入数据
                $MaterielPurchaseGoods = new MaterielPurchaseGoods();
                $add_goods_result = $MaterielPurchaseGoods->AddGoods($order_id,$goods);
                if (!$add_goods_result) {
                    $add_goods_error[] = $MaterielPurchaseGoods->add_goods_error;
                }
              }
            }
            if (count($add_goods_error) > 0) {
              message::result_json(1,$add_goods_error);
            }else{
              message::result_json(1,'添加成功');
            }

          }else{
            $goods = Materiel::findone($goods_id);
            //插入数据
            $MaterielPurchaseGoods = new MaterielPurchaseGoods();
            $add_goods_result = $MaterielPurchaseGoods->AddGoods($order_id,$goods);

            if (!$add_goods_result) {
                $add_goods_error = $MaterielPurchaseGoods->add_goods_error;
            }

            if (count($add_goods_error) > 0) {
              message::result_json(2,$add_goods_error);
            }else{
              message::result_json(1,'添加成功');
            }
          }
      }
      message::result_json(2,'数据错误');
    }
    public function actionDeleteGoods($id){
        $data_id = Yii::$app->request->get('data_id');
        $SpecimenPurchaseGoods = SpecimenPurchaseGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        $SpecimenPurchaseGoods->delete();
        message::result_json(1,'删除成功');
    }

    public function actionUpdateGoodsLabel($id){
        $data_type  = trim(Yii::$app->request->get('data_type'));
        $value  = trim(Yii::$app->request->get('value'));
        $id  = Yii::$app->request->get('id');
        $data_id  = Yii::$app->request->get('data_id');

        $SpecimenPurchaseGoods = SpecimenPurchaseGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        if($SpecimenPurchaseGoods){
          $SpecimenPurchaseGoods->$data_type = $value;
          $SpecimenPurchaseGoods->save(false);
          message::result_json(1,'成功',$value);
        }else{
          message::result_json(2,'没有此记录');
        }
    }


}
