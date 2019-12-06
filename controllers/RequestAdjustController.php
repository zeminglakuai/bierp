<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Store;
use app\common\models\RequestAdjustOrder;
use app\common\models\RequestAdjustOrderGoods;
use app\common\models\Goods;
use app\common\models\Stock;

use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\controllers\PurchaseController;
use app\includes\Common_fun;

class RequestAdjustController extends BaseController
{
    public $page_title = '申调单';
    public $title_model = 'app\common\models\RequestAdjustOrder';
    public $detail_model = 'app\common\models\RequestAdjustOrderGoods';
    public $status_label = 'request_adjust_order_status';
    
    public function beforeAction($action)
    {
      parent::beforeAction($action);

      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','admit','insert-goods','delete-goods','update-goods-label'];
      $admit_allow_arr = ['edit','view'];

      $scope_model = 'app\common\models\RequestAdjustOrder';
      $status_label = 'request_adjust_order_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }
 
    //删除
    public function actionDelete($id){
      $RequestAdjustOrder = RequestAdjustOrder::findone($id);
      if ($RequestAdjustOrder) {
        $RequestAdjustOrder->delete();
        message::result_json(1,'删除成功');
      }else{
        message::result_json(1,'数据错误');
      }
    }

    //添加
    public function actionInsert(){
        $RequestAdjustOrder = new RequestAdjustOrder();
        $RequestAdjustOrder->load(Yii::$app->request->post());

        if (!$RequestAdjustOrder->from_store_id) {
          Message::result_json(2,'出库仓库不能为空');
        }

        $Store = Store::findone($RequestAdjustOrder->from_store_id);
        if (!$Store) {
          Message::result_json(2,'出库仓库不存在，请重新选择');
        }else{
          $RequestAdjustOrder->from_store_name = $Store->store_name;
        }

        if (!$RequestAdjustOrder->to_store_id) {
          Message::result_json(2,'出库仓库不能为空');
        }

        $Store = Store::findone($RequestAdjustOrder->to_store_id);
        if (!$Store) {
          Message::result_json(2,'接受仓库不存在，请重新选择');
        }else{
          $RequestAdjustOrder->to_store_name = $Store->store_name;
        }

        if ($RequestAdjustOrder->to_store_id == $RequestAdjustOrder->from_store_id) {
          Message::result_json(2,'接受仓库不能与出库仓库相同');
        }

        $RequestAdjustOrder->order_sn = Common_fun::create_sn('app\common\models\RequestAdjustOrder',5);
        
        $RequestAdjustOrder->save(false);
        Message::result_json(1,'添加成功');
    }

    public function actionView($id){
        $RequestAdjustOrder = RequestAdjustOrder::findone($id);
        return $this->render('view', ['request_adjust_order'=>$RequestAdjustOrder,'id'=>$id]);
    }

    public function actionEdit($id){
        $this->layout = 'empty';
        $RequestAdjustOrder = RequestAdjustOrder::findone($id);
        return $this->render('create', ['request_adjust_order'=>$RequestAdjustOrder,'id'=>$id]);
    }

    public function actionUpdate($id){
        $RequestAdjustOrder = RequestAdjustOrder::findone($id);
        $RequestAdjustOrder->load(Yii::$app->request->post());
        unset($RequestAdjustOrder->order_sn);


        if (!$RequestAdjustOrder->from_store_id) {
          Message::result_json(2,'出库仓库不能为空');
        }

        $Store = Store::findone($RequestAdjustOrder->from_store_id);
        if (!$Store) {
          Message::result_json(2,'出库仓库不存在，请重新选择');
        }else{
          $RequestAdjustOrder->from_store_name = $Store->store_name;
        }

        if (!$RequestAdjustOrder->to_store_id) {
          Message::result_json(2,'出库仓库不能为空');
        }

        $Store = Store::findone($RequestAdjustOrder->to_store_id);
        if (!$Store) {
          Message::result_json(2,'接受仓库不存在，请重新选择');
        }else{
          $RequestAdjustOrder->to_store_name = $Store->store_name;
        }

        if ($RequestAdjustOrder->to_store_id == $RequestAdjustOrder->from_store_id) {
          Message::result_json(2,'接受仓库不能与出库仓库相同');
        }

        $RequestAdjustOrder->save(false);
        Message::result_json(1,'编辑成功');
    }

    public function actionAdmit($id,$process_status){
      //修改订单状态
      $RequestAdjustOrder = RequestAdjustOrder::findone($id);

      $admit_result = $this->base_admit($RequestAdjustOrder,$this->status_label,$process_status);
      if ($admit_result['error'] > 2) {
        Message::result_json(2,$admit_result['message']);
      }

      //复核成功  则生成双方出库单 和 入库单
      if ($admit_result['error'] == 1) {
          /*修改库存*/
          $RequestAdjustOrderGoods = RequestAdjustOrderGoods::find()->where(['order_id'=>$id])->all();
          $stock=new stock;
          foreach ($RequestAdjustOrderGoods as $key=>$val){
              $res0=$stock->add_stock($RequestAdjustOrder->to_store_id,'',$val->goods_id,$val->number,'','');
              $res1=$stock->reduce_stock($val->goods_id,$val->number,$RequestAdjustOrder->to_store_id);
          }
          Message::result_json(1,'复核成功');

      }

//      Message::result_json(1,'复核成功');
    }

    public function actionInsertGoods(){
      $goods_id = Yii::$app->request->get('goods_id',0);
      $order_id = Yii::$app->request->get('order_id',0);
      $search_data = Yii::$app->request->get('search_data',0);

      if (!$order_id) {
        message::result_json(2,'数据错误');
      }else{
        $order = RequestAdjustOrder::findone($order_id);
      }

      $add_goods_error = [];
      if ($goods_id) {
          if (is_array($goods_id)) {

            foreach ($goods_id as $key => $value) {
              $goods = Goods::findone($value);
              if ($goods) {
                //插入数据
                $RequestAdjustOrderGoods = new RequestAdjustOrderGoods();
                $add_goods_result = $RequestAdjustOrderGoods->AddGoods($order_id,$goods);
                if (!$add_goods_result) {
                    $add_goods_error[] = $RequestAdjustOrderGoods->add_goods_error;
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
            $RequestAdjustOrderGoods = new RequestAdjustOrderGoods();
            $add_goods_result = $RequestAdjustOrderGoods->AddGoods($order_id,$goods);

            if (!$add_goods_result) {
                $add_goods_error = $RequestAdjustOrderGoods->add_goods_error;
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
        $RequestAdjustOrderGoods = RequestAdjustOrderGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        $RequestAdjustOrderGoods->delete();

        message::result_json(1,'删除成功');
    }

    public function actionUpdateGoodsLabel(){
        $data_type  = trim(Yii::$app->request->get('data_type'));
        $value  = trim(Yii::$app->request->get('value'));
        $id  = Yii::$app->request->get('id');
        $data_id  = Yii::$app->request->get('data_id');

        $RequestAdjustOrderGoods = RequestAdjustOrderGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        if($RequestAdjustOrderGoods){
          $RequestAdjustOrderGoods->$data_type = $value;
          $RequestAdjustOrderGoods->save(false);
          message::result_json(1,'chengg',$value);
        }else{
          message::result_json(2,'没有此记录');
        }
    }
}
