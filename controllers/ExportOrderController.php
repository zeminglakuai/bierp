<?php

namespace app\controllers;

use app\common\models\DictionaryValue;
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

use app\common\models\SellOrderGoods;
use app\common\models\SellOrder;

use app\common\models\SupplierGoods;
use app\common\models\ExportOrder;
use app\common\models\ExportOrderGoods;

use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class ExportOrderController extends BaseController
{
    public $page_title = '出库单';
    
    public $title_model = 'app\common\models\ExportOrder';
    public $detail_model = 'app\common\models\ExportOrderGoods';
    public $status_label = 'export_order_status';

    public function beforeAction($action)
    {
      parent::beforeAction($action);

      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','admit','insert-goods','delete-goods','update-goods-label'];
      $admit_allow_arr = ['edit','view'];

      $scope_model = 'app\common\models\ExportOrder';
      $status_label = 'export_order_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }
 
    //删除
    public function actionDelete($id){
      $ExportOrder = ExportOrder::findone($id);
      if ($ExportOrder) {
        $ExportOrder->delete();
        message::result_json(1,'删除成功');
      }else{
        message::result_json(1,'数据错误');
      }
    }

    //添加
    public function actionInsert(){
        $ExportOrder = new ExportOrder();
        $ExportOrder->load(Yii::$app->request->post());

        if (!$ExportOrder->custom_id) {
          Message::result_json(2,'客户不能为空');
        }

        $custom = Custom::findone($ExportOrder->custom_id);
        if (!$custom) {
          Message::result_json(2,'客户不存在，请重新选择');
        }else{
          $ExportOrder->custom_name = $custom->custom_name;
        }
        if (!$ExportOrder->store_id){
            Message::result_json(2,'仓库不能为空');
        }

        $DictionaryData = Yii::$app->db->createCommand("SELECT dictionary_value FROM dictionary_value where id=".$ExportOrder->shipping_method)->queryOne();

        $ExportOrder->shipping_method=$DictionaryData['dictionary_value'];
        $store = Yii::$app->db->createCommand("SELECT * FROM store where id=".$ExportOrder->store_id)->queryOne();
        $ExportOrder->store_name=$store['store_name'];
        $ExportOrder->order_sn = Common_fun::create_sn('app\common\models\ExportOrder',5);
        $ExportOrder->add_time = time();
        $ExportOrder->add_user_id = Yii::$app->session['manage_user']['id'];
        $ExportOrder->add_user_name = Yii::$app->session['manage_user']['admin_name'];
        $ExportOrder->save(false);


        Message::result_json(1,'添加成功');
    }

    public function actionView($id){
        $ExportOrder = ExportOrder::findone($id);
        $customers = Yii::$app->db->createCommand("SELECT * FROM export_order_goods where order_id=".$id)->queryAll();
        if ($customers){
            foreach ($customers as $k=>$v){
                $res=Yii::$app->db->createCommand("SELECT * FROM stock where store_id=".$ExportOrder['store_id']." and goods_id=".$v['goods_id'])->queryOne();
                if ($res){
                    $customers[$k]['num']=$res['number'];
                }else{
                    $customers[$k]['num']=0;
                }

            }
        }
        return $this->render('view', ['export_order'=>$ExportOrder,'id'=>$id,'customers'=>$customers]);
    }
//创建出库单据
    public function actionAdd(){
        $this->layout = 'empty';
        $list=Yii::$app->db->createCommand("SELECT * FROM custom order by id desc")->queryAll();
        $shiplist=Yii::$app->db->createCommand("SELECT * FROM dictionary_value where dictionary_id=7 order by id desc")->queryAll();
        $storelist=Yii::$app->db->createCommand("SELECT * FROM store  order by id desc")->queryAll();
        return $this->render('add',['list'=>$list,'shiplist'=>$shiplist,'storelist'=>$storelist]);
    }
    public function actionEdit($id){
        $this->layout = 'empty';
        $ExportOrder = ExportOrder::findone($id);
        return $this->render('edit', ['export_order'=>$ExportOrder,'id'=>$id]);
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
        $ExportOrder = ExportOrder::findone($id);
       if ($ExportOrder->export_order_status >= 1) {
            Message::result_json(2,'单据已复核!');
        }
        $transaction = Yii::$app->db->beginTransaction();
        $admit_result = $this->base_admit($ExportOrder,'export_order_status',$process_status);
        if ($admit_result['error'] > 2) {
            $transaction->rollBack();
            Message::result_json(2,$admit_result['message']);
        }
        if ($admit_result['error'] == 1) {
            //减少库存 和 批次
            $ExportOrderGoods = ExportOrderGoods::find()->where(['order_id'=>$id])->all();
            foreach ($ExportOrderGoods as $key => $value) {
                /*查询实际库存*/
                $customers = Yii::$app->db->createCommand("SELECT * FROM stock where goods_id=".$value['goods_id']." and store_id=".$ExportOrder['store_id'])->queryOne();
                /*对比库存数量并减掉*/
                if ($customers['number']>=$value['number']){
                    $num=$customers['number']-$value['number'];
                    /*保存剩余的库存数量*/
                    $sql="update stock set number=".$num." where goods_id=".$value['goods_id']." and store_id=".$ExportOrder['store_id'];
                   Yii::$app->db->createCommand($sql)->execute();
                   /*保存到日志文件*/

                    Yii::$app->db->createCommand("insert into stock_log (number,addtime,goods_id,store_id,can1,cuxiao) values (".$value['number'].",'".date('Y-m-d h:m:s')."',".$value['goods_id'].",".$ExportOrder['store_id'].",0,".$ExportOrder['cuxiao'].")")->execute();
                   /*库存预警 获取商品预警天数，获取剩余库存数量 减去 获取出库对应天数出库的数量 如果数量不够就生成出库单*/
                    /* 获取商品预警天数*/
                    $goods_warn_number=Yii::$app->db->createCommand("SELECT warn_number FROM goods where goods_id=".$value['goods_id'])->queryOne();
                    $goods_warn_number=$goods_warn_number['warn_number'];
                    /*获取剩余库存数量*/
                    $xykucun=$customers['number']-$value['number'];
/*出库对应天数出库的数量*/
                   $ckkucun=Yii::$app->db->createCommand("select sum(number) as num from stock_log where date_sub(CURDATE(),INTERVAL ".$goods_warn_number." DAY) <= DATE(addtime) and cuxiao=1 and goods_id=".$value['goods_id'])->queryOne();
                    $pjkucun=$ckkucun['num']/10;
                   if ($xykucun>$pjkucun){

                       /*生成采购单*/
                       /*1.获取此商品的供应商*/

                       $purchase=Yii::$app->db->createCommand("select s.id,s.supplier_name,p.purchase_status from purchase_goods as pg left join purchase as p on pg.order_id=p.id left join supplier as s on p.supplier_id=s.id where pg.goods_id=".$value['goods_id']."  order by p.id desc")->queryOne();
                       /*判断此商品前一个供应商是否有为开始的采购单*/
                       $purchase_info=Yii::$app->db->createCommand("select id from purchase where purchase_status=0 and supplier_id=".$purchase['id'])->queryOne();

                        if (isset($purchase_info)){
                            Yii::$app->db->createCommand("insert into purchase_goods (order_id,goods_id,order_type) values (".$purchase_info['id'].",".$value['goods_id'].",1)")->execute();
                        }else{
                            /*2.生成采购单*/
                            $order_sn = Common_fun::create_sn('app\common\models\Purchase',5);
                            $sql="insert into purchase (order_name,order_sn,add_time,add_user_id,add_user_name,depart_id,depart_name,purchase_type,supplier_id,supplier_name) values ( '".$order_sn."','".$order_sn."',".time().",1,'admin',1,'广州客维总部',1,". $purchase['id'].",'".$purchase['supplier_name']."')";
                            Yii::$app->db->createCommand($sql)->execute();
                            $purchase_id=Yii::$app->db->getLastInsertID();
                            Yii::$app->db->createCommand("insert into purchase_goods (order_id,goods_id,order_type) values (".$purchase_id.",".$value['goods_id'].",1)")->execute();
                        }

                   }
                }else{
                    $purchase=Yii::$app->db->createCommand("select s.id,s.supplier_name,p.purchase_status from purchase_goods as pg left join purchase as p on pg.order_id=p.id left join supplier as s on p.supplier_id=s.id where pg.goods_id=".$value['goods_id']."  order by p.id desc")->queryOne();
                    /*判断此商品前一个供应商是否有为开始的采购单*/
                    $purchase_info=Yii::$app->db->createCommand("select id from purchase where purchase_status=0 and supplier_id=".$purchase['id'])->queryOne();

                    if (isset($purchase_info)){
                        Yii::$app->db->createCommand("insert into purchase_goods (order_id,goods_id,order_type) values (".$purchase_info['id'].",".$value['goods_id'].",1)")->execute();
                    }else{
                        /*2.生成采购单*/
                        $order_sn = Common_fun::create_sn('app\common\models\Purchase',5);
                        $sql="insert into purchase (order_name,order_sn,add_time,add_user_id,add_user_name,depart_id,depart_name,purchase_type,supplier_id,supplier_name) values ( '".$order_sn."','".$order_sn."',".time().",1,'admin',1,'广州客维总部',1,". $purchase['id'].",'".$purchase['supplier_name']."')";
                        Yii::$app->db->createCommand($sql)->execute();
                        $purchase_id=Yii::$app->db->getLastInsertID();
                        Yii::$app->db->createCommand("insert into purchase_goods (order_id,goods_id,order_type) values (".$purchase_id.",".$value['goods_id'].",1)")->execute();
                    }
                    Message::result_json(2,'库存不足');

                }


            }

            //设置对应销售单为发货状态
            $sell_order = SellOrder::findone($ExportOrder->sell_order_id);
            if ($sell_order) {
                $sell_order->shipping_status = 1;
                $sell_order->save(false);
            }

            //提交事务
            $transaction->commit();
        }
       /* foreach ($purchase as $k=>$v){

        }*/
        exit();

        Message::result_json(1,'复核成功');
    }
    /*备份而已*/
    public function actionAdmit1($id,$process_status){

      $ExportOrder = ExportOrder::findone($id);
      if ($ExportOrder->export_order_status >= 1) {
          Message::result_json(2,'单据已复核!');
      }

      $transaction = Yii::$app->db->beginTransaction();
      $admit_result = $this->base_admit($ExportOrder,'export_order_status',$process_status);
      if ($admit_result['error'] > 2) {
        $transaction->rollBack();
        Message::result_json(2,$admit_result['message']);
      }

      if ($admit_result['error'] == 1) {
        //减少库存 和 批次
        $ExportOrderGoods = ExportOrderGoods::find()->where(['order_id'=>$id])->all();

        foreach ($ExportOrderGoods as $key => $value) {
          //解开库存信息 分别扣减库存 和 批次
          $store_info = @unserialize($value->store_codes);
          if ($store_info) {
            $batch_info = [];
            $final_cost = 0; //最终成本 用户 返回到销售单中 记录成本
            $validate_number = 0; //检查store_codes 中商品数量之和 与 实发数量 是不是相同
            foreach ($store_info as $store_key => $store_value) {
              $stock = Stock::findone($store_value['id']);
                $stock_result = $stock->reduce_stock($value->goods_id,$store_value['number'],$ExportOrder->store_id);
                print_r($stock_result);exit();
              if ($stock && $stock_result) {
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
            if ($ExportOrder->sell_order_id) {

              $sell_order_goods = SellOrderGoods::find()->where(['order_id'=>$ExportOrder->sell_order_id,'goods_id'=>$value->goods_id])->one();
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

        //设置对应销售单为发货状态
        $sell_order = SellOrder::findone($ExportOrder->sell_order_id);
        if ($sell_order) {
          $sell_order->shipping_status = 1;
          $sell_order->save(false);
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
        $order = ExportOrder::findone($order_id);
      }

      $add_goods_error = [];
      if ($goods_id) {
          if (is_array($goods_id)) {

            foreach ($goods_id as $key => $value) {
              $goods = Goods::findone($value);
              if ($goods) {
                //插入数据
                $ExportOrderGoods = new ExportOrderGoods();
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
        $ExportOrderGoods = ExportOrderGoods::find()->where(['order_id'=>$id,'goods_id'=>$data_id])->one();
        $ExportOrderGoods->delete();

        message::result_json(1,'删除成功');
    }

    public function actionUpdateGoodsLabel($id){
        $data_type  = trim(Yii::$app->request->get('data_type'));
        $value  = trim(Yii::$app->request->get('value'));
        $id  = Yii::$app->request->get('id');
        $data_id  = Yii::$app->request->get('data_id');
        $ExportOrder = ExportOrder::find()->where(['id'=>$id])->one();

        if ($data_type = 'send_number') {
          $ExportOrderGoods = ExportOrderGoods::find()->where(['order_id'=>$id,'goods_id'=>$data_id])->one();
          if($ExportOrderGoods){
              /*拿库存数量*/
              $stockinfo= Yii::$app->db->createCommand("SELECT * FROM stock where store_id=".$ExportOrder['store_id']." and goods_id=".$ExportOrderGoods['goods_id'])->queryOne();
              if ($stockinfo['number']>=$ExportOrderGoods['number']){
                  $sql="update export_order_goods set number=".$value." where goods_id=".$data_id." and order_id=".$id;
                  Yii::$app->db->createCommand($sql)->execute();
              }else{
                  message::result_json(2,'库存不够');
              }
              message::result_json(1,'修改成功',$value);
            //刷新库位信息
            //取得库存信息 并分配
           /* $ExportOrderGoods->$data_type = $value;


            $present_goods_store_info = Stock::get_store_by_number($ExportOrderGoods->goods_id,$value,$ExportOrder->store_id);
            if (!$present_goods_store_info) {
              message::result_json(2,$ExportOrderGoods->goods_name.'库存不足');
            }

            $ExportOrderGoods->store_codes = serialize($present_goods_store_info);
            $ExportOrderGoods->save(false);

            $new_store_codes = '';
            foreach ($present_goods_store_info as $kk => $vv) {
              $new_store_codes = $vv['store_code'].'/'.$vv['number'].'<br>';
            }*/

//            message::result_json(1,'修改成功',$value,[["label_name"=>"store_codes","new_value"=>$new_store_codes]]);
          }else{
            message::result_json(2,'没有此记录');
          }
        }else{
          message::result_json(2,'参数错误');
        }
    }

    public function actionReloadStoreCode($id){
      $ExportOrder = ExportOrder::findone($id);
      if ($ExportOrder->export_order_status >= 1) {
          Message::result_json(2,'单据已复核!');
      }

      //更新库存
      $ExportOrderGoods = ExportOrderGoods::find()->where(['order_id'=>$id])->all();
      foreach ($ExportOrderGoods as $key => $value) {
        //取得库存信息 并分配
        $present_goods_store_info = Stock::get_store_by_number($value->goods_id,$value->send_number,$ExportOrder->store_id);
        if ($present_goods_store_info) {
          $value->store_codes = serialize($present_goods_store_info);
          $value->save(false);
        }else{
          message::result_json(12,$value->goods_name.'库存不足');
        }
      }
      message::result_json(1,'更新成功');
    }

}
