<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\MaterielPurchaseGoods;
use app\common\models\MaterielPurchase;
use app\common\models\Materiel;
use app\common\models\Supplier;
use app\common\models\Store;

use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class MaterielPurchaseController extends BaseController
{
    public $page_title = '物料采购单';
    public $title_model = 'app\common\models\MaterielPurchase';
    public $detail_model = 'app\common\models\MaterielPurchaseGoods';
    public $status_label = 'materiel_purchase_status';
    
    public function beforeAction($action)
    {
      parent::beforeAction($action);

      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','admit','insert-goods','delete-goods','update-goods-label'];
      $admit_allow_arr = ['edit'];

      $scope_model = 'app\common\models\MaterielPurchase';
      $status_label = 'materiel_purchase_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }

    //删除
    public function actionDelete($id){
      $MaterielPurchase = MaterielPurchase::findone($id);
      $MaterielPurchase->delete();
      message::result_json(1,'删除成功');
    }

    //添加
    public function actionInsert(){
        $MaterielPurchase = new MaterielPurchase();
        $MaterielPurchase->load(Yii::$app->request->post());

        if (!$MaterielPurchase->supplier_id) {
          Message::result_json(2,'供货商不能为空');
        }

        $supplier = Supplier::findone($MaterielPurchase->supplier_id);
        if (!$supplier) {
          Message::result_json(2,'供货商不存在，请重新选择');
        }else{
          $MaterielPurchase->supplier_name = $supplier->supplier_name;
        }

        if ($MaterielPurchase->store_id > 0) {
          $store = Store::findone($MaterielPurchase->store_id); 
          $MaterielPurchase->store_name = $store->store_name;
        }

        $MaterielPurchase->order_sn = Common_fun::create_sn('app\common\models\MaterielPurchase',5);
        
        $MaterielPurchase->save(false);
        Message::result_json(1,'添加成功');
    }

    public function actionView($id){
        $MaterielPurchase = MaterielPurchase::findone($id);
        return $this->render('view', ['materiel_purchase'=>$MaterielPurchase,'id'=>$id]);
    }

    public function actionEdit($id){
      $this->layout = 'empty';
      $MaterielPurchase = MaterielPurchase::findone($id);
      return $this->render('edit', ['materiel_purchase'=>$MaterielPurchase,'id'=>$id]);
    }

    public function actionUpdate($id){
      $MaterielPurchase = MaterielPurchase::findone($id);
      if ($MaterielPurchase) {
        $MaterielPurchase->load(Yii::$app->request->post());
        if ($MaterielPurchase->store_id > 0) {
          $store = Store::findone($MaterielPurchase->store_id); 
          $MaterielPurchase->store_name = $store->store_name;
        }

        $MaterielPurchase->save(false);
        Message::result_json(1,'编辑成功');
      }else{
        Message::result_json(2,'编辑失败');
      }

    }

    public function actionAdmit($id,$process_status){
      //修改订单状态
      $MaterielPurchase = MaterielPurchase::findone($id);
 

      //检查供货商是不是存在
      if ($MaterielPurchase->supplier_id > 0) {
      }else{
        Message::result_json(2,'请填写供货商!');
      }


      //检查商品进货价 和 数量 是不是齐备
      $MaterielPurchaseGoods = MaterielPurchaseGoods::find()->where(['order_id'=>$id])->all();
      if (!$MaterielPurchaseGoods) {
        Message::result_json(2,'商品不存在，不能复核！');
      }
      foreach ($MaterielPurchaseGoods as $key => $value) {
        if ($value->purchase_price <= 0 || $value->number <= 0) {
          Message::result_json(2,$value->goods_name.'信息不完整！');
          break;
        }
      }

      $admit_result = $this->base_admit($MaterielPurchase,'materiel_purchase_status',$process_status);
      if ($admit_result['error'] > 2) {
        Message::result_json(2,$admit_result['message']);
      }

      if ($admit_result['error'] == 1) {
        //$this->CreateImport($id);
      }

      $MaterielPurchase->materiel_purchase_status = $process_status;
      $MaterielPurchase->status_name = $admit_result['content']['processed_name'];
      $MaterielPurchase->save(false);

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
    public function actionDeleteMateriel($id){
        $data_id = Yii::$app->request->get('data_id');
        $SellOrderGoods = MaterielPurchaseGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        $SellOrderGoods->delete();

        message::result_json(1,'删除成功');
    }

    public function actionUpdateMaterielLabel(){
        $data_type  = trim(Yii::$app->request->get('data_type'));
        $value  = trim(Yii::$app->request->get('value'));
        $id  = Yii::$app->request->get('id');
        $data_id  = Yii::$app->request->get('data_id');

        $PurchaseGoods = MaterielPurchaseGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        if($PurchaseGoods){
          $PurchaseGoods->$data_type = $value;
          $PurchaseGoods->save(false);
          message::result_json(1,'chengg',$value);
        }else{
          message::result_json(2,'没有此记录');
        }
    }


}
