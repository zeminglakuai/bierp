<?php

namespace app\controllers;

use yii;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\AssetPurchase;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class AssetPurchaseOrderController extends BaseController
{
    public $page_title = '非经营性采购';
    public $title_model = 'app\common\models\AssetPurchase';
    public $detail_model = 'app\common\models\AssetPurchaseGoods';
    public $status_label = 'asset_purchase_status';
    public $scope = 'true';
    public $search_allowed = ['order_sn'=>2,'supplier_id'=>1,'supplier_name'=>2,'custom_order_id'=>1];
    public $enableCsrfValidation = false;

    public function beforeAction($action)
    {
      parent::beforeAction($action);
      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','admit','insert-goods','delete-goods','update-goods-label'];
      $admit_allow_arr = ['edit','view','update'];

      $scope_model = 'app\common\models\AssetPurchase';
      $status_label = 'asset_purchase_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }
 
    public function actionInsert(){
        $AssetPurchase = new AssetPurchase();
        $AssetPurchase->load(Yii::$app->request->post());

        if (!$AssetPurchase->validate()) {
 
            $return_mess = '';
            foreach ($AssetPurchase->errors as $key => $value) {
              $return_mess = $value[0];
              break;
            }

            Message::result_json(2,$return_mess);
        }
        
        $AssetPurchase->save(false);
        Message::result_json(1,'添加成功');
    }

    public function actionView($id){
        $AssetPurchase = AssetPurchase::findone($id);
        return $this->render('create', ['asset_purchase'=>$AssetPurchase,'id'=>$id]);
    }

    public function actionEdit($id){
        $this->layout = 'empty';
        $AssetPurchase = AssetPurchase::findone($id);
        return $this->render('create', ['asset_purchase'=>$AssetPurchase,'id'=>$id]);
    }

    public function actionUpdate($id){
        $AssetPurchase = AssetPurchase::findone($id);
        $AssetPurchase->load(Yii::$app->request->post());
        
        if (!$AssetPurchase->validate()) {
            print_r($AssetPurchase->errors);
            print_r($AssetPurchase->asset_name);
            Message::result_json(2,'添加成功');
        }

        $AssetPurchase->save(false);
        Message::result_json(1,'编辑成功');
    }

    public function actionAdmit($id,$process_status){
      //修改订单状态
      $DaifaOrder = DaifaOrder::findone($id);
      if ($DaifaOrder->daifa_order_status >= 1) {
          Message::result_json(2,'单据已复核!');
      }

      $admit_result = $this->base_admit($DaifaOrder,'daifa_order_status',$process_status);
      if ($admit_result['error'] > 2) {
        Message::result_json(2,$admit_result['message']);
      }

      if ($admit_result['error'] == 1) {
        //反馈 发货数量到 销售单
        // $sell_order_goods = SellOrderGoods::find()->where(['order_id'=>$DaifaOrder->sell_order_id,'goods_id'=>$value->goods_id])->one();
        // if ($sell_order_goods) {
        //   $sell_order_goods->send_number = $value->send_number;
        //   $sell_order_goods->save(false);
        // }
      }

      Message::result_json(1,'复核成功');
    }

    public function actionUpdateGoodsLabel($id){
        $data_type  = trim(Yii::$app->request->get('data_type'));
        $value  = trim(Yii::$app->request->get('value'));
        $id  = Yii::$app->request->get('id');
        $data_id  = Yii::$app->request->get('data_id');

        if ($data_type = 'send_number') {
          $ExportOrderGoods = ExportOrderGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
          if($ExportOrderGoods){
            //刷新库位信息
            //取得库存信息 并分配
            $ExportOrderGoods->$data_type = $value;

            $present_goods_store_info = Stock::get_store_by_number($ExportOrderGoods->goods_id,$value,$ExportOrder->store_id);
            if (!$present_goods_store_info) {
              message::result_json(2,$ExportOrderGoods->goods_name.'库存不足');
            }

            $ExportOrderGoods->store_codes = serialize($present_goods_store_info);
            $ExportOrderGoods->save(false);

            $new_store_codes = '';
            foreach ($present_goods_store_info as $kk => $vv) {
              $new_store_codes = $vv['store_code'].'/'.$vv['number'].'<br>';
            }

            message::result_json(1,'修改成功',$value,[["label_name"=>"store_codes","new_value"=>$new_store_codes]]);
          }else{
            message::result_json(2,'没有此记录');
          }
        }else{
          message::result_json(2,'参数错误');
        }
    }

}
