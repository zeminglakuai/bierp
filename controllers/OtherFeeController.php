<?php
namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;
use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Custom;
use app\common\models\Store;
use app\common\models\OtherFee;
use yii\helpers\Url;
use app\includes\Message;
use app\controllers\BaseController;
use app\includes\Common_fun;

class OtherFeeController extends BaseController
{
    public $page_title = '费用管理';
    public $title_model = 'app\common\models\OtherFee';
    public $status_label = 'other_fee_status';
    
    public function beforeAction($action)
    {
      parent::beforeAction($action);
      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','admit','update','insert-goods','delete-goods','update-goods-label'];
      $admit_allow_arr = ['edit','view'];

      $scope_model = 'app\common\models\OtherFee';
      $status_label = 'other_fee_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }
 
    //删除
    public function actionDelete($id){
      $OtherFee = OtherFee::findone($id);
      $OtherFee->delete();
      message::result_json(1,'删除成功');
    }

    //添加
    public function actionInsert(){
        $OtherFee = new OtherFee();
        $OtherFee->load(Yii::$app->request->post());

        if ($OtherFee->custom_id > 0) {
          $custom = Custom::findone($OtherFee->custom_id);
          $OtherFee->custom_name = $custom->custom_name;
        }
 
        $OtherFee->order_sn = Common_fun::create_sn('app\common\models\OtherFee',5);
        
        $OtherFee->save(false);
        Message::result_json(1,'添加成功');
    }

    public function actionView($id){
        $this->layout = 'empty';
        $OtherFee = OtherFee::findone($id);
        return $this->render('create', ['other_fee'=>$OtherFee,'id'=>$id]);
    }
 

    public function actionUpdate($id){
      $OtherFee = OtherFee::findone($id);
      if ($OtherFee) {
        $OtherFee->load(Yii::$app->request->post());

        $OtherFee->save(false);
        Message::result_json(1,'编辑成功');
      }else{
        Message::result_json(2,'编辑失败');
      }
    }

    public function actionAdmit($id,$process_status){
      //修改订单状态
      $SpecimenPurchase = SpecimenPurchase::findone($id);
      if ($SpecimenPurchase->specimen_purchase_status >= 1) {
          Message::result_json(2,'单据已复核!');
      }

      //检查供货商是不是存在
      if ($SpecimenPurchase->supplier_id > 0) {
      }else{
        Message::result_json(2,'请登记供货商!');
      }
 
      //检查商品进货价 和 数量 是不是齐备
      $order_goods = SpecimenPurchaseGoods::find()->where(['order_id'=>$id])->all();
      if (!$order_goods) {
        Message::result_json(2,'商品不存在，不能复核！');
      }
      foreach ($order_goods as $key => $value) {
        if ($value->purchase_price <= 0 || $value->number <= 0) {
          Message::result_json(2,$value->goods_name.'信息不完整！');
          break;
        }
      }
      
      $admit_result = $this->base_admit($SpecimenPurchase,'specimen_purchase_status',$process_status);
      if ($admit_result['error'] > 2) {
        Message::result_json(2,$admit_result['message']);
      }

      if ($admit_result['error'] == 1) {
        $SpecimenPurchase->status_done = 1;
        $SpecimenPurchase->save(false);
      }
      
      Message::result_json(1,'复核成功');

    }
 

    public function actionInsertGoods(){
      $goods_id = Yii::$app->request->get('goods_id',0);
      $order_id = Yii::$app->request->get('order_id',0);
      $search_data = Yii::$app->request->get('search_data',0);

      if (!$order_id) {
        message::result_json(2,'数据错误');
      }else{
        $order = SpecimenPurchase::findone($order_id);
      }

      $add_goods_error = [];
      if ($goods_id) {
          if (is_array($goods_id)) {
            foreach ($goods_id as $key => $value) {
              $goods = Goods::findone($value);
              if ($goods) {
                //插入数据
                $PurchaseGoods = new SpecimenPurchaseGoods();
                $add_goods_result = $PurchaseGoods->AddGoods($order_id,$goods);
                if (!$add_goods_result) {
                    $add_goods_error[] = $PurchaseGoods->add_goods_error;
                }
              }
            }
            if (count($add_goods_error) > 0) {
              message::result_json(1,$add_goods_error);
            }else{
              message::result_json(1,'添加成功');
            }

          }else{
            $goods = Goods::findone($goods_id);
            //插入数据
            $PurchaseGoods = new SpecimenPurchaseGoods();
            $add_goods_result = $PurchaseGoods->AddGoods($order_id,$goods);

            if (!$add_goods_result) {
                $add_goods_error = $PurchaseGoods->add_goods_error;
            }

            if (count($add_goods_error) > 0) {
              message::result_json(2,$add_goods_error);
            }else{
              message::result_json(1,'添加成功');
            }
          }
      }

      if ($search_data) {

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
