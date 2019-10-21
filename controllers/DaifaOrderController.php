<?php

namespace app\controllers;

use app\common\models\Supplier;
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
use app\common\models\DaifaOrder;
use app\common\models\DaifaOrderGoods;

use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class DaifaOrderController extends BaseController
{
    public $page_title = '代发单据';
    
    public $title_model = 'app\common\models\DaifaOrder';
    public $detail_model = 'app\common\models\DaifaOrderGoods';
    public $status_label = 'daifa_order_status';

    public $scope = 'true';
    public $search_allowed = ['order_sn'=>2,'supplier_id'=>1,'supplier_name'=>2,'custom_order_id'=>1];


    public function beforeAction($action)
    {
      parent::beforeAction($action);
      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','admit','insert-goods','delete-goods','update-goods-label'];
      $admit_allow_arr = ['edit','view'];

      $scope_model = 'app\common\models\DaifaOrder';
      $status_label = 'daifa_order_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }
 
    //删除
    //代发订单如果可以删除 则会出现很麻烦的问题
    // public function actionDelete($id){
    //   $DaifaOrder = DaifaOrder::findone($id);
    //   if ($DaifaOrder) {
    //     $DaifaOrder->delete();
    //     message::result_json(1,'删除成功');
    //   }else{
    //     message::result_json(1,'数据错误');
    //   }
    // }

    public function actionView($id){
        $DaifaOrder = DaifaOrder::findone($id);
        return $this->render('view', ['daifa_order'=>$DaifaOrder,'id'=>$id]);
    }

    public function actionEdit($id){
        $this->layout = 'empty';
        $DaifaOrder = DaifaOrder::findone($id);
        return $this->render('edit', ['daifa_order'=>$DaifaOrder,'id'=>$id]);
    }

    public function actionUpdate($id){
        $DaifaOrder = DaifaOrder::findone($id);
        $DaifaOrder->load(Yii::$app->request->post());
        unset($DaifaOrder->order_sn);
        $DaifaOrder->save(false);
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
    //创建代发单据
    public function actionAdd(){
        $this->layout = 'empty';
        $list=Yii::$app->db->createCommand("SELECT * FROM custom order by id desc")->queryAll();
        $supplierlist=Yii::$app->db->createCommand("SELECT id,supplier_name FROM supplier order by id desc")->queryAll();
        $shiplist=Yii::$app->db->createCommand("SELECT * FROM dictionary_value where dictionary_id=7 order by id desc")->queryAll();
        return $this->render('add',['list'=>$list,'shiplist'=>$shiplist,'supplierlist'=>$supplierlist]);
    }

    public function actionInsert(){
        $DaifaOrder=new DaifaOrder();
        $DaifaOrder->load(Yii::$app->request->post());
        if (!$DaifaOrder->custom_id) {
            Message::result_json(2,'客户不能为空');
        }

        $custom = Custom::findone($DaifaOrder->custom_id);
        if (!$custom) {
            Message::result_json(2,'客户不存在，请重新选择');
        }else{
            $DaifaOrder->custom_name = $custom->custom_name;
        }

        $DictionaryData = Yii::$app->db->createCommand("SELECT dictionary_value FROM dictionary_value where id=".$DaifaOrder->shipping_method)->queryAll();
        $DaifaOrder->shipping_method=$DictionaryData[0]['dictionary_value'];
        $DaifaOrder->order_sn = Common_fun::create_sn('app\common\models\ExportOrder',5);
        $DaifaOrder->add_time = time();
        $supplier=Supplier::getDataOne($DaifaOrder->supplier_id);
        $DaifaOrder->supplier_name=$supplier['supplier_name'];
        $DaifaOrder->add_user_id = Yii::$app->session['manage_user']['id'];
        $DaifaOrder->add_user_name = Yii::$app->session['manage_user']['admin_name'];
        $DaifaOrder->save(false);


        Message::result_json(1,'添加成功');



    }

    public function actionUpdateGoodsLabel($id){
        $data_type  = trim(Yii::$app->request->get('data_type'));
        $value  = trim(Yii::$app->request->get('value'));
        $id  = Yii::$app->request->get('id');
        $data_id  = Yii::$app->request->get('data_id');

        if ($data_type = 'send_number') {
          $DaifaOrderGoods = DaifaOrderGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
          if($DaifaOrderGoods){
            $DaifaOrderGoods->send_number = $value;
            $DaifaOrderGoods->save(false);

            message::result_json(1,'修改成功',$value,[["label_name"=>"send_number","new_value"=>$value]]);
          }else{
            message::result_json(2,'没有此记录');
          }
        }else{
          message::result_json(2,'参数错误');
        }
    }
    public function actionInsertGoods(){
        $goods_id = Yii::$app->request->get('goods_id',0);
        $order_id = Yii::$app->request->get('order_id',0);
        $search_data = Yii::$app->request->get('search_data',0);
        if (!$order_id) {
            message::result_json(2,'数据错误');
        }else{
            $order = DaifaOrder::findone($order_id);
        }

        $add_goods_error = [];
        if ($goods_id) {
            if (is_array($goods_id)) {

                foreach ($goods_id as $key => $value) {
                    $goods = Goods::findone($value);
                    if ($goods) {
                        //插入数据
                        $DaifaOrderGoods = new DaifaOrderGoods();
                        $add_goods_result = $DaifaOrderGoods->AddGoods($order_id,$goods);
                        if (!$add_goods_result) {
                            $add_goods_error[] = $DaifaOrderGoods->add_goods_error;
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
                $DaifaOrderGoods = new DaifaOrderGoods();
                $add_goods_result = $DaifaOrderGoods->AddGoods($order_id,$goods);

                if (!$add_goods_result) {
                    $add_goods_error = $DaifaOrderGoods->add_goods_error;
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

}
