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
use app\common\models\SellOrderGoods;
use app\common\models\SellOrder;
use app\common\models\SellOrderReturnGoods;
use app\common\models\SellOrderReturn;
use app\common\models\SupplierGoods;
use app\common\models\Supplier;
use app\common\models\Purchase;
use app\common\models\PurchaseGoods;
use app\common\models\exportOrder;
use app\common\models\exportOrderGoods;

use app\common\models\DaifaOrder;
use app\common\models\DaifaOrderGoods;

use app\common\models\Receipt;
use app\common\models\SellInvoice;
use app\common\models\Stock;
use app\common\models\Store;
use app\common\models\Contract;
use yii\helpers\Url;
use app\includes\Message;
use app\controllers\BaseController;
use app\includes\Common_fun;

class SellOrderController extends BaseController
{

    public $page_title = '销售单';
    public $title_model = 'app\common\models\SellOrder';
    public $detail_model = 'app\common\models\SellOrderGoods';
    public $status_label = 'sell_order_status';

    public function beforeAction($action)
    {
      parent::beforeAction($action);

      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','admit','insert-goods','delete-goods','update-goods-label'];
      $admit_allow_arr = ['edit','view','invoice','receipt','create-purchase-order',
                          'create-sell-order-return','create-invoice','insert-invoice','update-invoice','delete-invoice',
                          'create-receipt','insert-receipt','update-receipt','delete-receipt',
                          ];

      $scope_model = 'app\common\models\SellOrder';
      $status_label = 'sell_order_status';

      

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }

    public function actionIndex()
    {
  		return $this->render('index', []);
    }

    //添加
    public function actionCreate(){
        $this->layout = 'empty';
        return $this->render('create', []);
    }
    //删除
    public function actionDelete($id){
      $custom_order = SellOrder::findone($id);
      $custom_order->delete();
      message::result_json(1,'删除成功');
    }

    //添加
    public function actionInsert(){
        $SellOrder = new SellOrder();
        $SellOrder->load(Yii::$app->request->post());

        if (!$SellOrder->custom_id) {
          Message::result_json(2,'客户不能为空');
        }

        $custom = Custom::findone($SellOrder->custom_id);
        if (!$custom) {
          Message::result_json(2,'客户不存在，请重新选择');
        }else{
          $SellOrder->custom_name = $custom->custom_name;
        }

        $SellOrder->order_sn = Common_fun::create_sn('app\common\models\SellOrder',5);
        
        $SellOrder->save(false);
        Message::result_json(1,'添加成功');
    }

    public function actionView($id){
        $SellOrder = SellOrder::findone($id);
        return $this->render('view', ['sell_order'=>$SellOrder,'id'=>$id]);
    }

    public function actionEdit($id){
        $this->layout = 'empty';
        $SellOrder = SellOrder::findone($id);
        return $this->render('edit', ['sell_order'=>$SellOrder,'id'=>$id]);
    }

    public function actionUpdate($id){
        $SellOrder = SellOrder::findone($id);
        $SellOrder->load(Yii::$app->request->post());
        if ($SellOrder->store_id && $store = Store::findone($SellOrder->store_id)) {
          $SellOrder->store_name = $store->store_name;
        }
        unset($SellOrder->order_sn);

        $SellOrder->save(false);
        Message::result_json(1,'编辑成功');
    }

    public function actionAdmit($id,$process_status){
      //修改订单状态
      $SellOrder = SellOrder::findone($id);

      //检查销售单是不是有添加发货仓库
      if ($SellOrder->store_id <= 0) {
        Message::result_json(2,'请添加发货仓库!');
      }

      //检查销售单合同是不是通过审核
      $contract = Contract::findone($SellOrder);


      //建立事务
      $transaction = Yii::$app->db->beginTransaction();

      $admit_result = $this->base_admit($SellOrder,'sell_order_status',$process_status);
      if ($admit_result['error'] > 2) {
        //回滚事务
        $transaction->rollBack();
        Message::result_json(2,$admit_result['message']);
      }


      //这里（单据已经设置已复核  但是生成出库单 会出意外情况，导致单据不能复核） 没有处理

      if ($admit_result['error'] == 1) {
        //审核通过并生成 出库单
        $create_reulst= $this->CreateOutport($id);
        if ($create_reulst['error']  > 1) {
          //回滚事务
          $transaction->rollBack();
          Message::result_json(2,$create_reulst['message']);
        }

        $transaction->commit();
        Message::result_json(1,$create_reulst['message']);
      }


      $transaction->commit();
      Message::result_json(1,$admit_result['message']);
    }

    //生成出库单
    private function CreateOutport($id){

      $SellOrder = SellOrder::findone($id);

      $sell_goods = SellOrderGoods::find()->where(['order_id'=>$id])->all();

      //是不是要分仓库发货 由什么决定！！！

      $sell_goods_arr = [];
      $daifa_sell_goods_arr = [];

      
      foreach ($sell_goods as $key => $value) {

        //新建出库单
        if (!isset($sell_goods_arr[$SellOrder->store_id])) {
          $sell_goods_arr[$SellOrder->store_id]['custom_id'] = $SellOrder->custom_id;
          $sell_goods_arr[$SellOrder->store_id]['custom_name'] = $SellOrder->custom_name;
          
          $exportOrder = new exportOrder();
          $exportOrder->custom_id = $SellOrder->custom_id;
          $exportOrder->custom_name = $SellOrder->custom_name;
          $exportOrder->store_id = $SellOrder->store_id;
          $exportOrder->sell_order_id = $SellOrder->id;
          $exportOrder->sell_order_sn = $SellOrder->order_sn;
          $exportOrder->store_name = $SellOrder->store_name;
          $exportOrder->shipping_method = $SellOrder->shipping_method;
          $exportOrder->consignee = $SellOrder->consignee;
          $exportOrder->tel = $SellOrder->tel;
          $exportOrder->address = $SellOrder->address;
          $exportOrder->order_sn = Common_fun::create_sn('app\common\models\ExportOrder',5);
          $exportOrder->save(false);
          $sell_goods_arr[$SellOrder->store_id]['order_id'] = $exportOrder->id;
        }

        //新建代发单
        if (!isset($daifa_sell_goods_arr[$value->supplier_id]) && $value->is_daifa > 0) {

          $daifa_sell_goods_arr[$value->supplier_id]['custom_id'] = $SellOrder->custom_id;
          $daifa_sell_goods_arr[$value->supplier_id]['custom_name'] = $SellOrder->custom_name;
          
          $DaifaOrder = new DaifaOrder();
          $DaifaOrder->custom_id = $SellOrder->custom_id;
          $DaifaOrder->custom_name = $SellOrder->custom_name;

          $DaifaOrder->supplier_id = $value->supplier_id;
          $DaifaOrder->supplier_name = $value->supplier_name;

          $DaifaOrder->sell_order_id = $SellOrder->id;
          $DaifaOrder->sell_order_sn = $SellOrder->order_sn;

          $DaifaOrder->shipping_method = $SellOrder->shipping_method;
          $DaifaOrder->consignee = $SellOrder->consignee;
          $DaifaOrder->tel = $SellOrder->tel;
          $DaifaOrder->address = $SellOrder->address;
          $DaifaOrder->order_sn = Common_fun::create_sn('app\common\models\DaifaOrder',5);
          $DaifaOrder->save(false);

          $daifa_sell_goods_arr[$value->supplier_id]['order_id'] = $DaifaOrder->id;
        }



        //代发需要拿到供货商 并且按照不同的供货商生成对应的代发订单


        if ($value['is_daifa'] > 0) {
          $daifa_sell_goods_arr[$value->supplier_id]['goods_list'][$value->goods_id]['order_id'] = $daifa_sell_goods_arr[$value->supplier_id]['order_id']; 
          $daifa_sell_goods_arr[$value->supplier_id]['goods_list'][$value->goods_id]['goods_id'] = $value->goods_id;
          $daifa_sell_goods_arr[$value->supplier_id]['goods_list'][$value->goods_id]['goods_name'] = $value->goods_name;
          $daifa_sell_goods_arr[$value->supplier_id]['goods_list'][$value->goods_id]['goods_sn'] = $value->goods_sn;
          $daifa_sell_goods_arr[$value->supplier_id]['goods_list'][$value->goods_id]['isbn'] = $value->isbn;
          $daifa_sell_goods_arr[$value->supplier_id]['goods_list'][$value->goods_id]['market_price'] = $value->market_price;
          $daifa_sell_goods_arr[$value->supplier_id]['goods_list'][$value->goods_id]['sale_price'] = $value->sale_price;
          $daifa_sell_goods_arr[$value->supplier_id]['goods_list'][$value->goods_id]['number'] = $value->number;
        }else{
          $sell_goods_arr[$SellOrder->store_id]['goods_list'][$value->goods_id]['order_id'] = $sell_goods_arr[$SellOrder->store_id]['order_id']; 
          $sell_goods_arr[$SellOrder->store_id]['goods_list'][$value->goods_id]['goods_id'] = $value->goods_id;
          $sell_goods_arr[$SellOrder->store_id]['goods_list'][$value->goods_id]['goods_name'] = $value->goods_name;
          $sell_goods_arr[$SellOrder->store_id]['goods_list'][$value->goods_id]['goods_sn'] = $value->goods_sn;
          $sell_goods_arr[$SellOrder->store_id]['goods_list'][$value->goods_id]['isbn'] = $value->isbn;
          $sell_goods_arr[$SellOrder->store_id]['goods_list'][$value->goods_id]['market_price'] = $value->market_price;
          $sell_goods_arr[$SellOrder->store_id]['goods_list'][$value->goods_id]['sale_price'] = $value->sale_price;
          $sell_goods_arr[$SellOrder->store_id]['goods_list'][$value->goods_id]['number'] = $value->number;

          //取得库存信息 并分配
          $store_info = Stock::goods_stock($value->goods_id,$SellOrder->store_id);
          if (!$store_info) {
            return ['error'=>2,'message'=>$value->goods_name.'库存不足','content'=>''];
          }


          $present_goods_store_info = [];
          $remain_number = $value->number;
          $remain_done = 0;
          foreach ($store_info as $store_key => $store_value) {
            $remain_number = $store_value->number - $remain_number;
            if ($remain_number >= 0) {
              //锁定库存  用于直接扣除库存 避免出现把其他方案的进货被其他 方案发送走 批次在出库单审核的时候处理
              //取消锁定库存 因为后面取消发货或没有发货处理起来很麻烦
              //$store_value->number = $remain_number;
              //$store_value->save(false);
              $remain_done = 1;
              $present_goods_store_info[] = ['id'=>$store_value->id,'store_code'=>$store_value->stor_code,'number'=>$value->number];
              break;
            }else{
              //$store_value->number = 0;
              //$store_value->save(false);
              $remain_done = 0;
              $remain_number = abs($remain_number);
              $present_goods_store_info[] = ['id'=>$store_value->id,'store_code'=>$store_value->stor_code,'number'=>$store_value->number];
            }
          }

          if (!$remain_done) {
            return ['error'=>2,'message'=>$value->goods_name.'库存不足','content'=>''];
          }

          //初始化库存信息
          $sell_goods_arr[$SellOrder->store_id]['goods_list'][$value->goods_id]['store_code'] = serialize($present_goods_store_info);
          $sell_goods_arr[$SellOrder->store_id]['goods_list'][$value->goods_id]['send_number'] = $value->number;
        }
      }

      //print_r($sell_goods_arr);
      // print_r($daifa_sell_goods_arr);
      // die();
      if ($sell_goods_arr || $daifa_sell_goods_arr) {
        foreach ($sell_goods_arr as $kk => $vv) {
          $goods_title =['order_id','goods_id','goods_name','goods_sn','isbn','market_price','sale_price','number','store_codes','send_number'];//数据键
          $res= Yii::$app->db->createCommand()->batchInsert(exportOrderGoods::tableName(), $goods_title, $vv['goods_list'])->execute();
        }

        foreach ($daifa_sell_goods_arr as $kk => $vv) {
          $goods_title =['order_id','goods_id','goods_name','goods_sn','isbn','market_price','sale_price','number'];//数据键
          $res= Yii::$app->db->createCommand()->batchInsert(DaifaOrderGoods::tableName(), $goods_title, $vv['goods_list'])->execute();
        }

        return ['error'=>1,'message'=>'生成出库单 和 代发订单成功','content'=>''];
      }else{
        return ['error'=>2,'message'=>'单据中没有商品','content'=>''];
      }
    }

    //插入单据商品
    public function actionInsertGoods(){
      $goods_id = Yii::$app->request->get('goods_id',0);
      $order_id = Yii::$app->request->get('order_id',0);
      $search_data = Yii::$app->request->get('search_data',0);

      if (!$order_id) {
        message::result_json(2,'数据错误');
      }else{
        $order = SellOrder::findone($order_id);
      }

      $add_goods_error = [];
      if ($goods_id) {
          if (is_array($goods_id)) {

            foreach ($goods_id as $key => $value) {
              $goods = Goods::findone($value);
              if ($goods) {
                //插入数据
                $SellOrderGoods = new SellOrderGoods();
                $add_goods_result = $SellOrderGoods->AddGoods($order_id,$goods);
                if (!$add_goods_result) {
                    $add_goods_error[] = $SellOrderGoods->add_goods_error;
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
            $SellOrderGoods = new SellOrderGoods();
            $add_goods_result = $SellOrderGoods->AddGoods($order_id,$goods);

            if (!$add_goods_result) {
                $add_goods_error = $SellOrderGoods->add_goods_error;
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

      message::result_json(2,'数据错误2');
    }
    public function actionDeleteGoods($id){
        $data_id = Yii::$app->request->get('data_id');
        $SellOrderGoods = SellOrderGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        $SellOrderGoods->delete();

        message::result_json(1,'删除成功');
    }

    public function actionUpdateGoodsLabel(){
        $data_type  = trim(Yii::$app->request->get('data_type'));
        $value  = trim(Yii::$app->request->get('value'));
        $id  = Yii::$app->request->get('id');
        $data_id  = Yii::$app->request->get('data_id');

        //如果单据有客户方案  则不允许修改 售价 和 数量
        $sell_order = SellOrder::findone($id); 
        if ($sell_order) {
          
        }else{
          message::result_json(2,'没有此记录');
        }

        $SellOrderGoods = SellOrderGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        if($SellOrderGoods){
          $SellOrderGoods->$data_type = $value;
          $SellOrderGoods->save(false);
          message::result_json(1,'成功',$value);
        }else{
          message::result_json(2,'没有此记录');
        }
    }

    //生成 采购单
    public function actionCreatePurchaseOrder($id){
      // $this->layout = 'empty';
      // $SellOrder = SellOrder::findone($id);
      // return $this->render('create-purchase', ['sell_order'=>$SellOrder,'id'=>$id]);
      $goods_id_arr = Yii::$app->request->get('goods_id_arr');

      $sell_order = SellOrder::findone($id);
      if (!$sell_order) {
        message::result_json(2,'没有单据记录');
      }

      $sell_goods = SellOrderGoods::find()->where(['order_id'=>$id])->all();
      //按照不同供货商 分组商品
      $sell_goods_arr = [];
      foreach ($sell_goods as $key => $value) {
        if (!in_array($value->goods_id, $goods_id_arr) || !$value->supplier_id) {
          continue;
        }

        if (!isset($sell_goods_arr[$value->supplier_id])) {
          $sell_goods_arr[$value->supplier_id]['supplier_id'] = $value->supplier_id;
          $sell_goods_arr[$value->supplier_id]['supplier_name'] = $value->supplier_name;

          //新建采购单
          $purchase_order = new Purchase();
          $purchase_order->supplier_id = $value->supplier_id;
          $purchase_order->supplier_name = $value->supplier_name;
          $purchase_order->store_id = $sell_order->store_id;
          $purchase_order->store_name = $sell_order->store_name;
          $purchase_order->order_sn = Common_fun::create_sn('app\common\models\Purchase',5);
          $purchase_order->sell_order_id = $sell_order->id;
          $purchase_order->sell_order_sn = $sell_order->order_sn;

          $purchase_order->save(false);

          $sell_goods_arr[$value->supplier_id]['order_id'] = $purchase_order->id;
        }

        $sell_goods_arr[$value->supplier_id]['goods_list'][$value->goods_id]['order_id'] = $sell_goods_arr[$value->supplier_id]['order_id']; 
        $sell_goods_arr[$value->supplier_id]['goods_list'][$value->goods_id]['goods_id'] = $value->goods_id;
        $sell_goods_arr[$value->supplier_id]['goods_list'][$value->goods_id]['goods_name'] = $value->goods_name;
        $sell_goods_arr[$value->supplier_id]['goods_list'][$value->goods_id]['goods_sn'] = $value->goods_sn;
        $sell_goods_arr[$value->supplier_id]['goods_list'][$value->goods_id]['isbn'] = $value->isbn;
        $sell_goods_arr[$value->supplier_id]['goods_list'][$value->goods_id]['number'] = $value->number;
        $sell_goods_arr[$value->supplier_id]['goods_list'][$value->goods_id]['market_price'] = $value->market_price;
        $sell_goods_arr[$value->supplier_id]['goods_list'][$value->goods_id]['purchase_price'] = $value->supplier_price;
      }

      if ($sell_goods_arr) {
        foreach ($sell_goods_arr as $kk => $vv) {
          $goods_title =['order_id','goods_id','goods_name','goods_sn','isbn','number','market_price','purchase_price'];//数据键
          $res= Yii::$app->db->createCommand()->batchInsert(PurchaseGoods::tableName(), $goods_title, $vv['goods_list'])->execute();
        }
      }else{
        message::result_json(2,'没有商品记录');
      }
      message::result_json(1,'采购单生成成功');
    }


    //生成退货单
    public function actionCreateSellOrderReturn($id){
      $goods_id_arr = Yii::$app->request->get('goods_id_arr');

      $sell_order = SellOrder::findone($id);
      if (!$sell_order) {
        message::result_json(2,'没有单据记录');
      }else{
        if ($sell_order->status_done <> 1) {
          message::result_json(2,'未复核单据不能生成退货单');
        }
      }

      $sell_goods = SellOrderGoods::find()->where(['order_id'=>$id])->all();

      $transaction = Yii::$app->db->beginTransaction();
      //新建退货单
      $sell_order_return = new SellOrderReturn();
      $sell_order_return->sell_order_id = $sell_order->id;
      $sell_order_return->sell_order_sn = $sell_order->order_sn;
      $sell_order_return->custom_id = $sell_order->custom_id;
      $sell_order_return->custom_name = $sell_order->custom_name;
      $sell_order_return->order_sn = Common_fun::create_sn('app\common\models\SellOrderReturn',5);
      $sell_order_return->save(false);

      $sell_goods_arr = [];
      foreach ($sell_goods as $key => $value) {
        if (in_array($value->goods_id, $goods_id_arr)) {
          $sell_goods_arr[$value->goods_id]['order_id'] = $sell_order_return->id; 
          $sell_goods_arr[$value->goods_id]['goods_id'] = $value->goods_id;
          $sell_goods_arr[$value->goods_id]['goods_name'] = $value->goods_name;
          $sell_goods_arr[$value->goods_id]['goods_sn'] = $value->goods_sn;
          $sell_goods_arr[$value->goods_id]['isbn'] = $value->isbn;
          $sell_goods_arr[$value->goods_id]['number'] = $value->number;
          $sell_goods_arr[$value->goods_id]['return_number'] = $value->number;
          $sell_goods_arr[$value->goods_id]['market_price'] = $value->market_price;
        }
      }

      if ($sell_goods_arr) {
        $goods_title =['order_id','goods_id','goods_name','goods_sn','isbn','number','return_number','market_price'];//数据键
        try {
          $res= Yii::$app->db->createCommand()->batchInsert(SellOrderReturnGoods::tableName(), $goods_title, $sell_goods_arr)->execute();
        } catch (Exception $e) {
          $transaction->rollBack();
          message::result_json(2,'插入商品错误');
        }
        $transaction->commit();
        
      }else{
        message::result_json(2,'没有商品记录');
      }
      message::result_json(1,'销售退货单生成成功');
    }


    public function actionReceipt($id){
      //得到相关付款单
      $this->layout = 'empty';
      $receipt = Receipt::find()->where(['order_id'=>$id])->all();
      return $this->render('receipt', ['receipt'=>$receipt,'id'=>$id]);
    }

    //创建收款单
    public function actionCreateReceipt($id){
        $this->layout = 'empty';
        return $this->render('create-receipt', ['id'=>$id]);
    }

    //创建收款单
    public function actionInsertReceipt(){
      $Receipt = new Receipt();
      $Receipt->load(Yii::$app->request->post());

      if (!$Receipt->fee || $Receipt->fee <= 0) {
        Message::result_json(2,'请输入收款金额');
      }

      if (!$Receipt->order_id) {
        Message::result_json(2,'销售单不能为空');
      }

      $Receipt->remain_time = $Receipt->remain_time?strtotime($Receipt->remain_time):time();

      $sell_order = SellOrder::findone($Receipt->order_id);
      if (!$sell_order) {
        Message::result_json(2,'销售单不存在');
      }else{
        $Receipt->custom_id = $sell_order->custom_id;
        $Receipt->custom_name = $sell_order->custom_name;
        $Receipt->relate_order_sn = $sell_order->order_sn;
        $Receipt->order_total = $sell_order->total;
        $Receipt->model = 'SellOrder';
      }

      //计算收款单总金额 收款单总金额不能大于销售单金额
      $sum_total = Receipt::find()->where(['order_id'=>$Receipt->order_id,'model'=>'SellOrder'])->sum('fee');
      $sum_total += $Receipt->fee;
      if ($sum_total > $sell_order->total) {
        Message::result_json(2,'收款单总金额不能大于销售单金额');
      }

      $Receipt->order_sn = Common_fun::create_sn('app\common\models\Receipt',5);
      
      $Receipt->save(false);
      Message::result_json(1,'添加成功');
    }

    //编辑收款单
    public function actionEditReceipt($id){
        $this->layout = 'empty';
        $Receipt = Receipt::findone($id);
        return $this->render('create-receipt', ['id'=>$id,'receipt'=>$Receipt]);
    }

    //编辑收款单
    public function actionUpdateReceipt($id){
      $Receipt = Receipt::findone($id);
      $Receipt->load(Yii::$app->request->post());

      //如果已复核 则不允许编辑
      if ($Receipt->receipt_status > 0) {
        Message::result_json(2,'已有操作收款单，不允许编辑');
      }

      if (!$Receipt->fee || $Receipt->fee <= 0) {
        Message::result_json(2,'请输入收款金额');
      }

      unset($Receipt->order_id);

      $Receipt->remain_time = $Receipt->remain_time?strtotime($Receipt->remain_time):time();

      //计算收款单总金额 收款单总金额不能大于销售单金额
      $sum_total = Receipt::find()->where(['order_id'=>$Receipt->order_id,'model'=>'SellOrder'])->andwhere(['<>','id',$id])->sum('fee');
      $sum_total += $Receipt->fee;
      if ($sum_total > $sell_order->total) {
        Message::result_json(2,'收款单总金额不能大于销售单金额');
      }
      
      $Receipt->save(false);
      Message::result_json(1,'编辑成功');
    }

    //删除收款单
    public function actionDeleteReceipt($id){
      $Receipt = Receipt::findone($id);
      if ($Receipt->receipt_status > 0) {
        Message::result_json(2,'已有操作收款单，不允许删除');
      }
      $Receipt->delete();
      Message::result_json(1,'删除成功');
    }


    //得到相关发票
    public function actionInvoice($id){
      $this->layout = 'empty';
      $SellInvoice = SellInvoice::find()->where(['order_id'=>$id])->all();
      return $this->render('invoice', ['sell_invoice'=>$SellInvoice,'id'=>$id]);
    }

    //创建发票
    public function actionCreateInvoice($id){
        $this->layout = 'empty';
        //得到以设置开票金额和
        $has_feed = SellInvoice::find()->where(['order_id'=>$id,'type'=>1])->sum('fee');

        //得到当前单据金额和
        $SellOrder = SellOrder::findone($id);
        $order_total = $SellOrder->total;

        $remind_fee = $order_total - $has_feed;

        return $this->render('create-invoice', ['id'=>$id,'remind_fee'=>$remind_fee]);
    }

    //创建发票
    public function actionInsertInvoice($id){
      $SellInvoice = new SellInvoice();
      $SellInvoice->load(Yii::$app->request->post());

      if (!$SellInvoice->fee || $SellInvoice->fee <= 0) {
        Message::result_json(2,'请输入收款金额');
      }

      if (!$SellInvoice->order_id) {
        Message::result_json(2,'销售单不能为空');
      }

      $SellInvoice->remain_time = $SellInvoice->remain_time?strtotime($SellInvoice->remain_time):time();

      $sell_order = SellOrder::findone($SellInvoice->order_id);
      if (!$sell_order) {
        Message::result_json(2,'销售单不存在');
      }else{
        $SellInvoice->custom_id = $sell_order->custom_id;
        $SellInvoice->custom_name = $sell_order->custom_name;
        $SellInvoice->relate_order_sn = $sell_order->order_sn;
        $SellInvoice->order_total = $sell_order->total;
        $SellInvoice->model = 'SellOrder';
      }
      
      $SellInvoice->sell_invoice_status = 0;
      //计算收款单总金额 收款单总金额不能大于销售单金额
      $sum_total = SellInvoice::find()->where(['order_id'=>$SellInvoice->order_id,'model'=>'SellOrder'])->sum('fee');
      $sum_total += $SellInvoice->fee;
      if ($sum_total > $sell_order->total) {
        Message::result_json(2,'发票总金额不能大于销售单金额');
      }

      $SellInvoice->order_sn = Common_fun::create_sn('app\common\models\SellInvoice',5);
      
      $SellInvoice->save(false);
      Message::result_json(1,'添加成功');
    }

    //编辑发票
    public function actionEditInvoice($invoice_id){
        $this->layout = 'empty';
        $SellInvoice = SellInvoice::findone($invoice_id);
        return $this->render('create-invoice', ['id'=>$id,'sell_invoice'=>$SellInvoice]);
    }

    //编辑发票
    public function actionUpdateInvoice($invoice_id){
      $SellInvoice = SellInvoice::findone($invoice_id);
      $SellInvoice->load(Yii::$app->request->post());

      //如果已复核 则不允许编辑
      if ($Receipt->sell_invoice_status > 0) {
        Message::result_json(2,'已有操作发票，不允许编辑');
      }

      if (!$SellInvoice->fee || $SellInvoice->fee <= 0) {
        Message::result_json(2,'请输入收款金额');
      }

      $sell_order = SellOrder::findone($SellInvoice->order_id);
      if (!$sell_order) {
        Message::result_json(2,'销售单不存在');
      }else{
        $SellInvoice->custom_id = $sell_order->custom_id;
        $SellInvoice->custom_name = $sell_order->custom_name;
      }

      $SellInvoice->remain_time = $SellInvoice->remain_time?strtotime($SellInvoice->remain_time):time();

      //计算收款单总金额 收款单总金额不能大于销售单金额
      $sum_total = SellInvoice::find()->where(['order_id'=>$SellInvoice->order_id,'model'=>'SellOrder'])->andwhere(['<>','id',$id])->sum('fee');
      $sum_total += $SellInvoice->fee;
      if ($sum_total > $sell_order->total) {
        Message::result_json(2,'发票总金额不能大于销售单金额');
      }
      
      $SellInvoice->save(false);
      Message::result_json(1,'编辑成功');
    }

    //删除发票
    public function actionDeleteInvoice($invoice_id){
      $SellInvoice = SellInvoice::findone($invoice_id);
      if ($SellInvoice->sell_invoice_status > 0) {
        Message::result_json(2,'已有操作发票，不允许删除');
      }
      $SellInvoice->delete();
      Message::result_json(1,'删除成功');
    }


}
