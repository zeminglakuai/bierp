<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Goods;
use app\common\models\Stock;
use app\common\models\Batch;
use app\common\models\Supplier;
use app\common\models\SellOrderGoods;
use app\common\models\SellOrder;
use app\common\models\Store;

use app\common\models\OtherImportOrder;
use app\common\models\OtherImportOrderGoods;

use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class OtherImportOrderController extends BaseController
{
    public $page_title = '其他入库单';
    
    public $title_model = 'app\common\models\OtherImportOrder';
    public $detail_model = 'app\common\models\OtherImportOrderGoods';
    public $status_label = 'other_import_order_status';
    public $scope = 'true';
    public $search_allowed = ['order_sn'=>2,'custom_name'=>2,'other_import_order_status'=>1,'store_id'=>1];

    
    public function beforeAction($action)
    {
      parent::beforeAction($action);

      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','admit','update','insert-goods','delete-goods','update-goods-label'];
      $admit_allow_arr = ['edit'];

      $scope_model = 'app\common\models\OtherImportOrder';
      $status_label = 'other_import_order_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }
    
    public function actionIndex(){
      if (isset(Yii::$app->session['manage_user']['store_id']) && Yii::$app->session['manage_user']['store_id'] > 0) {
        $init_condition = [['store_id'=>Yii::$app->session['manage_user']['store_id']]];
      }
      return $this->render('index',['init_condition'=>$init_condition]);
    }
    
    //删除
    public function actionDelete($id){
      $OtherImportOrder = OtherImportOrder::findone($id);
      if ($OtherImportOrder) {
        $OtherImportOrder->delete();
        message::result_json(1,'删除成功');
      }else{
        message::result_json(1,'数据错误');
      }
    }

    //添加
    public function actionInsert(){
        $OtherImportOrder = new OtherImportOrder();
        $OtherImportOrder->load(Yii::$app->request->post());


        if (!$OtherImportOrder->store_id) {
          Message::result_json(2,'仓库不能为空');
        }

        $store = Store::findone($OtherImportOrder->store_id);
        if (!$store) {
          Message::result_json(2,'仓库不存在，请重新选择');
        }else{
          $OtherImportOrder->store_name = $store->store_name;
        }

        $OtherImportOrder->order_sn = Common_fun::create_sn('app\common\models\OtherImportOrder',5);
        
        $OtherImportOrder->save(false);
        Message::result_json(1,'添加成功');
        
    }

    public function actionView($id){
        $OtherImportOrder = OtherImportOrder::findone($id);
        return $this->render('view', ['other_import_order'=>$OtherImportOrder,'id'=>$id]);
    }

    public function actionEdit($id){
        $this->layout = 'empty';
        $OtherImportOrder = OtherImportOrder::findone($id);
        return $this->render('create', ['other_import_order'=>$OtherImportOrder,'id'=>$id]);
    }

    public function actionUpdate($id){
        $OtherImportOrder = OtherImportOrder::findone($id);
        $OtherImportOrder->load(Yii::$app->request->post());

        if (!$OtherImportOrder->supplier_id) {
          Message::result_json(2,'供货商不能为空');
        }

        $supplier = Supplier::findone($OtherImportOrder->supplier_id);
        if (!$supplier) {
          Message::result_json(2,'供货商不存在，请重新选择');
        }else{
          $OtherImportOrder->supplier_name = $supplier->supplier_name;
        }

        if (!$OtherImportOrder->store_id) {
          Message::result_json(2,'仓库不能为空');
        }

        $store = Store::findone($OtherImportOrder->store_id);
        if (!$store) {
          Message::result_json(2,'仓库不存在，请重新选择');
        }else{
          $OtherImportOrder->store_name = $store->store_name;
        }

        $OtherImportOrder->save(false);
        Message::result_json(1,'编辑成功');

    }

    public function actionAdmit($id,$process_status){
      //修改订单状态
      $OtherImportOrder = OtherImportOrder::findone($id);

      $transaction = Yii::$app->db->beginTransaction();

      $admit_result = $this->base_admit($OtherImportOrder,'other_import_order_status',$process_status);
      if ($admit_result['error'] > 2) {
        $transaction->rollBack();
        Message::result_json(2,$admit_result['message']);
      }

      if ($admit_result['error'] == 1) {
        //复核成功  增加库存 和 批次
        //检查库位和 数量是不是有填写
        
        $ImportOrderGoods = OtherImportOrderGoods::find()->where(['order_id'=>$id])->all();
        foreach ($ImportOrderGoods as $key => $value) {
          if (!$value->real_number || !$value->store_code ) {
            $transaction->rollBack();
            Message::result_json(2,'单据信息不完整',$value->id);
          }
          //增加库存
          $stock = Stock::find()->where(['stor_code'=>$value->store_code,'goods_id'=>$value->goods_id,'store_id'=>$OtherImportOrder->store_id])->one();
          if (!$stock) {
            $stock = new Stock();
          }
          $stock->add_stock($OtherImportOrder->store_id,$value->store_code,$value->goods_id,$value->real_number,$value->purchase_price,$OtherImportOrder->order_sn);
        }
        $transaction->commit();
      }

      Message::result_json(1,'复核成功');
    }
 



    public function actionDeleteGoods($id){
        $data_id = Yii::$app->request->get('data_id');
        $OtherImportOrderGoods = OtherImportOrderGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        $OtherImportOrderGoods->delete();

        message::result_json(1,'删除成功');
    }




    public function actionUpdateGoodsLabel(){
        $data_type  = trim(Yii::$app->request->get('data_type'));
        $value  = trim(Yii::$app->request->get('value'));
        $id  = Yii::$app->request->get('id');
        $data_id  = Yii::$app->request->get('data_id');

        $OtherImportOrderGoods = OtherImportOrderGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        if($OtherImportOrderGoods){
          $OtherImportOrderGoods->$data_type = $value;
          $OtherImportOrderGoods->save(false);
          message::result_json(1,'处理成功',$value);
        }else{
          message::result_json(2,'没有此记录');
        }
    }
}
