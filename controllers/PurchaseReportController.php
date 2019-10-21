<?php
namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;
use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Admin;
use app\common\models\Leave;

use yii\helpers\Url;
use app\includes\Message;
use app\controllers\BaseController;
use app\includes\Common_fun;

class PurchaseReportController extends BaseController
{
    public $page_title = '采购报表';
    public function beforeAction($action)
    {
      parent::beforeAction($action);
      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','admit','update'];
      $admit_allow_arr = ['edit','view'];

      $scope_model = 'app\common\models\Leave';
      $status_label = 'leave_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }
 
    //删除
    public function actionDelete($id){
      $Leave = Leave::findone($id);
      $Leave->delete();
      message::result_json(1,'删除成功');
    }

    //添加
    public function actionInsert(){
        $Leave = new Leave();
        $Leave->load(Yii::$app->request->post());
        $Leave->order_sn = Common_fun::create_sn('app\common\models\MaterielRequest',5);
        $Leave->save(false);
        Message::result_json(1,'添加成功');
    }

    public function actionView($id){
        $MaterielRequest = MaterielRequest::findone($id);
        return $this->render('view', ['MaterielRequest'=>$MaterielRequest,'id'=>$id]);
    }
 

    public function actionUpdate($id){
      $MaterielRequest = MaterielRequest::findone($id);
      if ($MaterielRequest) {
        $MaterielRequest->load(Yii::$app->request->post());

        $MaterielRequest->save(false);
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

      //复核成功  则create物料采购单据
      if ($admit_result['error'] == 1) {
        
      }
      
      Message::result_json(1,'复核成功');

    }
 

    public function actionInsertMateriel(){
      $goods_id = Yii::$app->request->get('goods_id',0);
      $order_id = Yii::$app->request->get('id',0);
      $search_data = Yii::$app->request->get('search_data',0);

      if (!$order_id) {
        message::result_json(2,'数据错误');
      }else{
        $order = MaterielRequest::findone($order_id);
      }

      $add_goods_error = [];
      if ($goods_id) {
          if (is_array($goods_id)) {
            foreach ($goods_id as $key => $value) {
              $goods = Materiel::findone($value);
              if ($goods) {
                //插入数据
                $MaterielPurchaseGoods = new MaterielRequestGoods();
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
            $MaterielPurchaseGoods = new MaterielRequestGoods();
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

    public function actionDeleteMateriel($id){
        $data_id = Yii::$app->request->get('data_id');
        $MaterielRequestGoods = MaterielRequestGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        $MaterielRequestGoods->delete();
        message::result_json(1,'删除成功');
    }

    public function actionUpdateMaterielLabel($id){
        $data_type  = trim(Yii::$app->request->get('data_type'));
        $value  = trim(Yii::$app->request->get('value'));
        $id  = Yii::$app->request->get('id');
        $data_id  = Yii::$app->request->get('data_id');

        $MaterielRequestGoods = MaterielRequestGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        if($MaterielRequestGoods){
          $MaterielRequestGoods->$data_type = $value;
          $MaterielRequestGoods->save(false);
          message::result_json(1,'成功',$value);
        }else{
          message::result_json(2,'没有此记录');
        }
    }


}
