<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\CustomOrderGoods;
use app\common\models\CustomOrder;
use app\common\models\Custom;
use app\common\models\Goods;
use app\common\models\Stock;
use app\common\models\Store;
use app\common\models\SellOrderGoods;
use app\common\models\SellOrder;
use app\common\models\SupplierGoods;
use app\common\models\OtherExportOrder;
use app\common\models\OtherExportOrderGoods;

use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class OtherExportOrderController extends BaseController
{
    public $page_title = '其他出库单';
    
    public $title_model = 'app\common\models\OtherExportOrder';
    public $detail_model = 'app\common\models\OtherExportOrderGoods';
    public $status_label = 'other_export_order_status';
    public $scope = 'true';
    public $search_allowed = ['order_sn'=>2,'custom_name'=>2,'other_import_order_status'=>1,'store_id'=>1];


    public function beforeAction($action)
    {
      parent::beforeAction($action);

      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','admit','insert-goods','delete-goods','update-goods-label'];
      $admit_allow_arr = ['edit'];

      $scope_model = 'app\common\models\OtherExportOrder';
      $status_label = 'other_export_order_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }
 
    //删除
    public function actionDelete($id){
      $ExportOrder = OtherExportOrder::findone($id);
      if ($ExportOrder) {
        $ExportOrder->delete();

        OtherExportOrderGoods::deleteall(['order_id'=>$id]);
        message::result_json(1,'删除成功');
      }else{
        message::result_json(1,'数据错误');
      }
    }

    //添加
    public function actionInsert(){
        $ExportOrder = new OtherExportOrder();
        $ExportOrder->load(Yii::$app->request->post());
        if ($ExportOrder->store_id) {
          $store = Store::findone($ExportOrder->store_id);
          $ExportOrder->store_name = $store->store_name;
        }
        $ExportOrder->order_sn = Common_fun::create_sn('app\common\models\OtherExportOrder',5);
        
        $ExportOrder->save(false);
        Message::result_json(1,'添加成功');
    }

    public function actionView($id){
        $ExportOrder = OtherExportOrder::findone($id);
        return $this->render('view', ['other_export_order'=>$ExportOrder,'id'=>$id]);
    }

    public function actionEdit($id){
        $this->layout = 'empty';
        $ExportOrder = OtherExportOrder::findone($id);
        return $this->render('edit', ['other_export_order'=>$ExportOrder,'id'=>$id]);
    }

    public function actionUpdate($id){
        $ExportOrder = ExportOrder::findone($id);
        $ExportOrder->load(Yii::$app->request->post());
        unset($ExportOrder->order_sn);
        $ExportOrder->save(false);
        Message::result_json(1,'编辑成功');
    }

    public function actionAdmit($id,$process_status){
      //修改订单状态
      $OtherExportOrder = OtherExportOrder::findone($id);

      $transaction = Yii::$app->db->beginTransaction();
      $admit_result = $this->base_admit($OtherExportOrder,'other_export_order_status',$process_status);
      if ($admit_result['error'] > 2) {
        $transaction->rollBack();
        Message::result_json(2,$admit_result['message']);
      }

      if ($admit_result['error'] == 1) {

        //减少库存 和 批次
        $OtherExportOrderGoods = OtherExportOrderGoods::find()->where(['order_id'=>$id])->all();
        foreach ($OtherExportOrderGoods as $key => $value) {
          //解开库存信息 分别扣减库存 和 批次
          $store_info = @unserialize($value->store_codes);
          if ($store_info) {
            $batch_info = [];
            $final_cost = 0; //最终成本 用户 返回到销售单中 记录成本
            $validate_number = 0; //检查store_codes 中商品数量之和 与 实发数量 是不是相同
            foreach ($store_info as $store_key => $store_value) {
              $stock = Stock::findone($store_value['id']);
              if ($stock && $stock_result = $stock->reduce_stock($value->goods_id,$store_value['number'],$OtherExportOrder->store_id)) {
                //记录
                $batch_info = array_merge($stock_result,$batch_info);
                $validate_number += $store_value['number'];
                foreach ($stock_result as $kk => $vv) {
                  $final_cost += round($vv['price']*$vv['number'],2);
                }
              }else{
                $transaction->rollBack();
                message::result_json(3,$value->goods_name.'-扣减库存错误',$value->goods_id);
              }
            }

            //检查store_codes 中商品数量之和 与 实发数量 是不是相同
            if ($validate_number <> $value->send_number) {
              message::result_json(4,$value->goods_name.'-实发数量与库位数量不一致,请刷新库位信息',$value->goods_id);
            }

            //记录消减批次信息
            $batch_info = serialize($batch_info);
            $value->batch_info = $batch_info;
            $value->final_cost = $final_cost;
            $value->save(false);
            //返回发货的商品信息 到 销售单
            if ($OtherExportOrder->sell_order_id) {

              $sell_order_goods = SellOrderGoods::find()->where(['order_id'=>$OtherExportOrder->sell_order_id,'goods_id'=>$value->goods_id])->one();
              if ($sell_order_goods) {
                $sell_order_goods->send_number = $value->send_number;
                $sell_order_goods->batch_info = $batch_info;
                $sell_order_goods->final_cost = $final_cost;
                $sell_order_goods->save(false);
              }
            }

          }else{
            $transaction->rollBack();
            message::result_json(4,'扣减库存错误',$value->goods_id);
          }
        }

        //提交事务
        $transaction->commit();
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
        $order = OtherExportOrder::findone($order_id);
      }

      $add_goods_error = [];
      if ($goods_id) {
          if (is_array($goods_id)) {

            foreach ($goods_id as $key => $value) {
              $goods = Goods::findone($value);
              if ($goods) {
                //插入数据
                $ExportOrderGoods = new OtherExportOrderGoods();
                $add_goods_result = $ExportOrderGoods->AddGoods($order_id,$goods);
                if (!$add_goods_result) {
                    $add_goods_error[] = $ExportOrderGoods->add_goods_error;
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
            $ExportOrderGoods = new ExportOrderGoods();
            $add_goods_result = $ExportOrderGoods->AddGoods($order_id,$goods);

            if (!$add_goods_result) {
                $add_goods_error = $ExportOrderGoods->add_goods_error;
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
        $ExportOrderGoods = OtherExportOrderGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        $ExportOrderGoods->delete();

        message::result_json(1,'删除成功');
    }

    public function actionUpdateGoodsLabel($id){
        $data_type  = trim(Yii::$app->request->get('data_type'));
        $value  = trim(Yii::$app->request->get('value'));
        $id  = Yii::$app->request->get('id');
        $data_id  = Yii::$app->request->get('data_id');

        if ($data_type = 'send_number') {
          $ExportOrderGoods = OtherExportOrderGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
          if($ExportOrderGoods){
            
            $ExportOrderGoods->store_code = serialize($present_goods_store_info);
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
