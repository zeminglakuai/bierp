<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;

use app\common\models\CustomOrder;
use app\common\models\CustomOrderGoods;

use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class AdjustStorController extends BaseController
{
    public $page_title = '调仓单';
    public $title_model = 'app\common\models\ExportOrder';
    public $detail_model = 'app\common\models\ExportOrderGoods';
    public $status_label = 'export_order_status';

    public function beforeAction($action)
    {
      parent::beforeAction($action);

      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','admit','insert-goods','delete-goods','update-goods-label'];
      $admit_allow_arr = ['edit'];

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

        $ExportOrder->order_sn = Common_fun::create_sn('app\common\models\ExportOrder',5);
        
        $ExportOrder->save(false);
        Message::result_json(1,'添加成功');
    }

    public function actionView($id){
        $ExportOrder = ExportOrder::findone($id);
        return $this->render('view', ['export_order'=>$ExportOrder,'id'=>$id]);
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

    public function actionAdmit($id){
      //修改订单状态
      $ExportOrder = ExportOrder::findone($id);
      if ($ExportOrder->impoert_order_status >= 1) {
          Message::result_json(2,'单据已复核!');
      }

      //符合成功  增加库存 和 批次

      $ExportOrder->admit_user_id = Yii::$app->session['manage_user']['id'];
      $ExportOrder->admit_user_name = Yii::$app->session['manage_user']['admin_name'];
      $ExportOrder->admit_time = time();
      $ExportOrder->export_order_status = 1;
      $ExportOrder->save(false);


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

      if ($search_data) {

      }

      message::result_json(2,'数据错误');
    }

    public function actionDeleteGoods($id){
        $data_id = Yii::$app->request->get('data_id');
        $ExportOrderGoods = ExportOrderGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        $ExportOrderGoods->delete();

        message::result_json(1,'删除成功');
    }

    public function actionUpdateGoodsLabel(){
        $data_type  = trim(Yii::$app->request->get('data_type'));
        $value  = trim(Yii::$app->request->get('value'));
        $id  = Yii::$app->request->get('id');
        $data_id  = Yii::$app->request->get('data_id');

        $ExportOrderGoods = ExportOrderGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        if($ExportOrderGoods){
          $ExportOrderGoods->$data_type = $value;
          $ExportOrderGoods->save(false);
          message::result_json(1,'chengg',$value);
        }else{
          message::result_json(2,'没有此记录');
        }
    }
}
