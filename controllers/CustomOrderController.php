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

use app\common\models\OrderLog;
use app\common\models\SupplierGoods;
use app\common\models\Supplier;
use app\common\models\AskPriceOrder;
use app\common\models\AskPriceOrderGoods;
use app\common\models\Val;

use app\common\models\SellOrder;
use app\common\models\SellOrderGoods;

use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class CustomOrderController extends BaseController
{
    public $page_title = 'B2B方案';
    public $title_arr = ['id'=>1,'order_sn'=>0,'order_name'=>0,'custom_name'=>0,'goodsNumber'=>0 ,'saleTotal'=>0,'add_user_name'=>0,'depart_name'=>0,'add_time'=>0,'custom_order_status'=>0];
    public $scope = true;
    public $search_allowed = ['order_sn'=>2,'order_name'=>2,'custom_name'=>2,'custom_order_status'=>1];
    public $title_model = 'app\common\models\CustomOrder';
    public $detail_model = 'app\common\models\CustomOrderGoods';
    public $status_label = 'custom_order_status';

    public function beforeAction($action)
    {
        parent::beforeAction($action);

        //检查当前单据用户是不是有操作权限
        $need_privi_arr = ['remend_history','delete','edit','admit','insert-goods','delete-goods','update-goods-label'];
        $admit_allow_arr = ['create-ask-price','create-sell-order','view','export','export-ppt','remend-history'];
        $scope_model = 'app\common\models\CustomOrder';
        $status_label = 'custom_order_status';

        if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
            return true;
        }else{
            return false;
        }
    }

    //添加客户方案
    public function actionCreate(){
        $this->layout = 'empty';
        return $this->render('create', []);
    }

    //删除客户方案
    public function actionDelete($id){
        $custom_order = CustomOrder::findone($id);
        $custom_order->delete();
        message::result_json(1,'删除成功');
    }

    //添加客户方案
    public function actionInsert(){
        $CustomOrder = new CustomOrder();
        $CustomOrder->load(Yii::$app->request->post());

        if (!$CustomOrder->custom_id) {
            Message::result_json(2,'客户不能为空');
        }

        $custom = Custom::findone($CustomOrder->custom_id);
        if (!$custom) {
            Message::result_json(2,'客户不存在，请重新选择');
        }else{
            $CustomOrder->custom_name = $custom->custom_name;
        }
        if (strlen($CustomOrder->order_name) < 2) {
            Message::result_json(2,'单据名称不能为空');
        }

        $CustomOrder->order_sn = Common_fun::create_sn('app\common\models\CustomOrder',5);

        $CustomOrder->save(false);
        Message::result_json(1,'添加成功');
    }

    public function actionEdit($id){
        $this->layout = 'empty';
        $custom_order = CustomOrder::findone($id);
        return $this->render('edit', ['custom_order'=>$custom_order,'id'=>$id]);
    }

    public function actionUpdate($id){
        $CustomOrder = CustomOrder::findone($id);
        $CustomOrder->load(Yii::$app->request->post());

        if (!$CustomOrder->custom_id) {
            Message::result_json(2,'客户不能为空');
        }

        $custom = Custom::findone($CustomOrder->custom_id);
        if (!$custom) {
            Message::result_json(2,'客户不存在，请重新选择');
        }else{
            $CustomOrder->custom_name = $custom->custom_name;
        }
        if (strlen($CustomOrder->order_name) < 2) {
            Message::result_json(2,'单据名称不能为空');
        }

        $CustomOrder->save(false);
        Message::result_json(1,'编辑成功');
    }

    public function actionView($id){
        $custom_order = CustomOrder::findone($id);
//        $custom_order_goods = CustomOrderGoods::find()->where(['order_id'=>$custom_order->id])->all();

        $custom_order_goods=Yii::$app->db->createCommand("select cog.*,g.goods_img from custom_order_goods as cog left join goods as g on g.goods_id=cog.goods_id where cog.order_id=".$custom_order->id)->queryAll();
        if (isset($custom_order_goods)) {
            foreach ($custom_order_goods as $k => $val) {
                $custom_order_goods[$k]['supplier']=Yii::$app->db->createCommand("select * from goods_supplier where goods_id=".$val['goods_id'])->queryAll();
            }
        }
        $vallist= Yii::$app->db->createCommand("select * from val where order_id=".$id)->queryAll();
        return $this->render('view', ['custom_order'=>$custom_order,'id'=>$id,'custom_order_goods'=> $custom_order_goods, 'vallist' => $vallist]);
    }

    public function actionAdmit($id,$process_status){
        $CustomOrder = CustomOrder::findone($id);

        //检查是不是有商品
        $custom_order_goods = CustomOrderGoods::findAll(['order_id'=>$id]);
        if (!$custom_order_goods) {
            Message::result_json(2,'单据下没有商品!');
        }

        $admit_result = $this->base_admit($CustomOrder,'custom_order_status',$process_status);
        if ($admit_result['error'] > 2) {
            Message::result_json(2,$admit_result['message']);
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
            //检查用户对该单据的操作权限
            $order = CustomOrder::findone($order_id);
        }
        $add_goods_error = [];

        if ($goods_id) {
            $custom_order = CustomOrder::findone($order_id);
            if (is_array($goods_id)) {
                foreach ($goods_id as $key => $value) {
                    $goods = Goods::findone($value);
                    if ($goods) {
                        //插入数据
                        $CustomOrderGoods = new CustomOrderGoods();
                        $add_goods_result = $CustomOrderGoods->AddGoods($order_id,$goods);
                        if (!$add_goods_result) {
                            $add_goods_error[] = $CustomOrderGoods->add_goods_error;
                        }

                        //如果单据在复核中 则记录历史
                        if ($custom_order && $custom_order->custom_order_status >= 1) {
                            $order_log = new OrderLog();
                            $order_log->model = 'CustomOrder';
                            $order_log->order_id = $order_id;
                            $order_log->lable_name = '添加商品操作';
                            $order_log->goods_id = $CustomOrderGoods->goods_id;
                            $order_log->goods_name = $CustomOrderGoods->goods_name;
                            $order_log->goods_sn = $CustomOrderGoods->goods_sn;
                            $order_log->save(false);
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
                $CustomOrderGoods = new CustomOrderGoods();
                $add_goods_result = $CustomOrderGoods->AddGoods($order_id,$goods);

                if (!$add_goods_result) {
                    $add_goods_error = $CustomOrderGoods->add_goods_error;
                }

                //如果单据在复核中 则记录历史

                if ($custom_order && $custom_order->custom_order_status >= 1) {
                    $order_log = new OrderLog();
                    $order_log->model = 'CustomOrder';
                    $order_log->order_id = $order_id;
                    $order_log->lable_name = '添加商品操作';
                    $order_log->goods_id = $CustomOrderGoods->goods_id;
                    $order_log->goods_name = $CustomOrderGoods->goods_name;
                    $order_log->goods_sn = $CustomOrderGoods->goods_sn;
                    $order_log->save(false);
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
        $CustomOrderGoods = CustomOrderGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();

        //如果单据在复核中 则记录历史
        $custom_order = CustomOrder::findone($id);
        if ($custom_order && $custom_order->custom_order_status >= 1) {
            $order_log = new OrderLog();
            $order_log->model = 'CustomOrder';
            $order_log->order_id = $id;
            $order_log->lable_name = '删除操作';
            $order_log->goods_id = $CustomOrderGoods->goods_id;
            $order_log->goods_name = $CustomOrderGoods->goods_name;
            $order_log->goods_sn = $CustomOrderGoods->goods_sn;
            $order_log->save(false);
        }

        $CustomOrderGoods->delete();

        message::result_json(1,'删除成功');
    }

    public function actionUpdateGoodsLabel($id){

        $data_type  = trim(Yii::$app->request->get('data_type'));
        $value  = trim(Yii::$app->request->get('value'));
        $id  = Yii::$app->request->get('id');
        $data_id  = Yii::$app->request->get('data_id');

        $CustomOrderGoods = CustomOrderGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        if($CustomOrderGoods){
            $origin_value = $CustomOrderGoods->$data_type;

            if ($origin_value == $value) {
                message::result_json(3,'没有变化');
            }

            $CustomOrderGoods->$data_type = $value;
            $CustomOrderGoods->save(false);

            //如果当局已经复核  则记录修改记录
            $custom_order = CustomOrder::findone($id);

            if ($custom_order && $custom_order->custom_order_status >= 1) {

                $order_log = new OrderLog();
                $order_log->model = 'CustomOrder';
                $order_log->order_id = $id;
                $order_log->lable_name = $data_type;
                $order_log->origin_value = $origin_value;
                $order_log->new_value = $value;
                $order_log->goods_id = $CustomOrderGoods->goods_id;
                $order_log->goods_name = $CustomOrderGoods->goods_name;
                $order_log->goods_sn = $CustomOrderGoods->goods_sn;
                $order_log->save(false);
            }

            $supplier_price_arr = ['consult'];
            $sale_price_arr = ['platform_rate','tranform_rate'];
            if (in_array($data_type, $supplier_price_arr)) {
                $value = $CustomOrderGoods->supplier_price * $value;
            }

            if (in_array($data_type, $sale_price_arr)) {
                $value = $CustomOrderGoods->sale_price * $value;
            }

            //根据公式计算产生变化的值
            //当前只计算当前列的值的变化
            $calculate_value = [];
            $calculate_value[] = ['label_name'=>'saleTotal','new_value'=>$CustomOrderGoods->saleTotal];
            $calculate_value[] = ['label_name'=>'supplierTotal','new_value'=>$CustomOrderGoods->supplierTotal];
            $calculate_value[] = ['label_name'=>'profit','new_value'=>$CustomOrderGoods->profit];
            $calculate_value[] = ['label_name'=>'profitTotal','new_value'=>$CustomOrderGoods->profitTotal];
            $calculate_value[] = ['label_name'=>'profitRate','new_value'=>$CustomOrderGoods->profitRate];
            $calculate_value[] = ['label_name'=>'finalCost','new_value'=>$CustomOrderGoods->finalCost];
            $calculate_value[] = ['label_name'=>'finalCostTotal','new_value'=>$CustomOrderGoods->finalCostTotal];
            $calculate_value[] = ['label_name'=>'faxPoint','new_value'=>$CustomOrderGoods->faxPoint];
            $calculate_value[] = ['label_name'=>'consultFee','new_value'=>$CustomOrderGoods->consultFee];


            message::result_json(1,'success',$value,$calculate_value);
        }else{
            message::result_json(2,'没有此记录');
        }
    }

    //生成 询价单
    //需要订单状态为已审核
    public function actionCreateAskPrice($id){

        $custom_order = CustomOrder::findone($id);
        if ($custom_order->is_create_ask_price == 1) {
            message::result_json(2,'单据已经生成对应询价单');
        }

        //检查是不是已经走完 审批流程
        if (!$this->if_approval_done($custom_order,'custom_order_status')) {
            message::result_json(2,'还未走完审批流程');
        }

        //得到全部商品
        // $custom_order_goods_list = (new \yii\db\Query())
        //                               ->select(['goods_id','number'])
        //                               ->from('custom_order_goods')
        //                               ->andwhere(['order_id' => $id])
        //                               ->andwhere(['is_self_sell'=>0])
        //                               ->all();

        $custom_order_goods_list = CustomOrderGoods::find()->where(['order_id'=>$id])->all();
        //$goods_id_list = ArrayHelper::map($custom_order_goods_list, 'goods_id', 'goods_id');
        if ($custom_order_goods_list) {
            //按照不同的供货商 进行分组
            $supplier_goods_list = [];
            foreach ($custom_order_goods_list as $key => $value) {
                $supplier_goods_list[$value->supplier_id][] = $value;
            }
            //按照供应商进行分组
            if ($supplier_goods_list) {

                //按照不对的供应商生成对应的询价单
                foreach ($supplier_goods_list as $key => $value) {
                    //得到供货商信息
                    $supplier_info = Supplier::findone($key);
                    //新建询价单头
                    $AskPriceOrder = new AskPriceOrder();
                    $AskPriceOrder->custom_order_id = $id;
                    $AskPriceOrder->order_sn = Common_fun::create_sn('app\common\models\AskPriceOrder',5);
                    $AskPriceOrder->supplier_id = $supplier_info->id;
                    $AskPriceOrder->supplier_name = $supplier_info->supplier_name;
                    $AskPriceOrder->ask_price_order_status = 0;
                    $AskPriceOrder->access_secrect = substr(md5(uniqid(rand(), TRUE)),0,4);
                    $AskPriceOrder->save(false);

                    //插入商品
                    $goods_id_list = ArrayHelper::map($value, 'goods_id', 'goods_id');
                    $goods_title =['order_id','goods_id','goods_name','goods_sn','isbn','number','market_price','sale_price','return_ask_price','return_number'];//数据键
                    $goods_list = (new \yii\db\Query())
                        ->select([$AskPriceOrder->id.' as `order_id`','goods_id','goods_name','goods_sn','isbn','number','market_price','sale_price','supplier_price as return_ask_price','number as return_number'])
                        ->from('custom_order_goods')
                        ->andwhere(['order_id'=>$id])
                        ->andwhere(['in','goods_id',$goods_id_list])
                        ->all();

                    $res= Yii::$app->db->createCommand()->batchInsert(AskPriceOrderGoods::tableName(), $goods_title, $goods_list)->execute();
                }

                //修改订单状态为 已生成询价单
                $custom_order->is_create_ask_price = 1;
                $custom_order->save(false);

                message::result_json(1,'生成成功！');

            }else{
                message::result_json(2,'未生成任何询价单');
            }
        }else{
            message::result_json(2,'未生成任何询价单');
        }
    }

    //设置询价结束
    //需要订单状态为生成询价单
    // public function actionAskOrderDone($id){
    //   //修改订单状态
    //   $CustomOrder = CustomOrder::findone($id);
    //   if ($CustomOrder->custom_order_status >= 3) {
    //       Message::result_json(2,'单据已询价结束!');
    //   }

    //   $CustomOrder->ask_order_done_user_id = Yii::$app->session['manage_user']['id'];
    //   $CustomOrder->ask_order_done_user_name = Yii::$app->session['manage_user']['admin_name'];
    //   $CustomOrder->ask_order_done_time = time();
    //   $CustomOrder->custom_order_status = 3;
    //   $CustomOrder->save(false);
    //   Message::result_json(1,'询价结束成功');

    // }

    //生成 销售单
    //需要订单状态为已审核create_sell_order
    public function actionCreateSellOrder($id){
        $goods_id_arr = Yii::$app->request->get('goods_id_arr');
        $custom_order = CustomOrder::findone($id);

        // if ($custom_order->is_create_sell_order >= 1) {
        //     message::result_json(2,'已经生成过销售单');
        // }

        //检查是不是已经走完 审批流程
        if (!$this->if_approval_done($custom_order,'custom_order_status')) {
            message::result_json(2,'还未走完审批流程');
        }

        $SellOrder = new SellOrder();
        $SellOrder->custom_id = $custom_order->custom_id;
        $SellOrder->custom_order_id = $id;
        $SellOrder->custom_name = $custom_order->custom_name;
        $SellOrder->order_sn = Common_fun::create_sn('app\common\models\SellOrder',5);
        $SellOrder->sell_order_status = 0;
        $SellOrder->save(false);

        //得到全部商品
        $insert_goods_list = (new \yii\db\Query())
            ->select([$SellOrder->id.' as `order_id`','goods_id','goods_name','goods_sn','isbn','number','market_price','sale_price','supplier_id','supplier_name','supplier_price','is_self_sell'])
            ->from('custom_order_goods')
            ->where(['order_id'=>$id])
            ->andwhere(['in','goods_id',$goods_id_arr])
            ->all();

        // foreach ($goods_list as $key => $value) {

        //   if (in_array($value['goods_id'], $goods_id_arr)) {
        //     $insert_goods_list[$value->goods_id] = $value;
        //   }
        // }

        $goods_title =['order_id','goods_id','goods_name','goods_sn','isbn','number','market_price','sale_price','supplier_id','supplier_name','supplier_price','is_self_sell'];//测试数据键
        $res= Yii::$app->db->createCommand()->batchInsert(SellOrderGoods::tableName(), $goods_title, $insert_goods_list)->execute();

        //修改订单状态为 已生成销售单
        $custom_order->is_create_sell_order = 1;
        $custom_order->save(false);

        message::result_json(1,'生成销售单成功!');
    }

    //单据修改历史
    public function actionRemendHistory($id){
        $this->layout = 'empty';
        $order_log = OrderLog::find()->where(['model'=>'CustomOrder','order_id'=>$id])->all();
        return $this->render('remend-history', ['order_log'=>$order_log]);
    }
    // 字段模块
    public function actionCreateVal($id)
    {
        $this->layout = 'empty';
        return $this->render('create-val', ['id' => $id]);
    }
    // 添加字段
    public function actionInsertVal($id)
    {

        $val = new Val();

        $data = Yii::$app->request->post();
        if ($data['id'] != '') {
            $val = Val::findone($data['id']);
        }
        if (empty($data['val_name'])||empty($data['val_name_en'])) {
            message::result_json(2, '内容不能为空');
        }
        $val->val_name = $data['val_name'];
        $val->val_name_en = $data['val_name_en'];
        $val->order_id = ($id);
        $val->add_user_id = Yii::$app->session['manage_user']['id'];
        $val->add_user_name = Yii::$app->session['manage_user']['admin_name'];
        $val->add_time = time();
        $val->save(false);
        Message::result_json(1, '添加成功');
    }
    

}
