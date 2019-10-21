<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\GoodsAuth;
use app\common\models\GoodsAuthGoods;
use app\common\models\Supplier;
use app\common\models\Goods;
use app\common\models\FileInfo;
use app\common\models\UploadForm;
use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class GoodsAuthController extends BaseController
{
    public $title_model = 'app\common\models\GoodsAuth';
    public $detail_model = 'app\common\models\GoodsAuthGoods';
    public $status_label = 'goods_auth_status';
    public function beforeAction($action)
    {
      parent::beforeAction($action);

      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','admit','insert-goods','delete-goods','update-goods-label'];
      $admit_allow_arr = ['edit','view'];

      $scope_model = 'app\common\models\GoodsAuth';
      $status_label = 'goods_auth_status';

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
      $GoodsAuth = GoodsAuth::findone($id);
      $GoodsAuth->delete();
      message::result_json(1,'删除成功');
    }

    //添加
    public function actionInsert(){
        $GoodsAuth = new GoodsAuth();
        $GoodsAuth->load(Yii::$app->request->post());
 

        if (!$GoodsAuth->supplier_id) {
          Message::result_json(2,'请选择供货商');
        }else{
          $supplier = Supplier::findone($GoodsAuth->supplier_id);
          if ($supplier) {
            $GoodsAuth->supplier_name = $supplier->supplier_name;
          }else{
            Message::result_json(2,'供货商不存在，重新选择');
          }
        }
        $GoodsAuth->expire_time = strtotime($GoodsAuth->expire_time);
        $GoodsAuth->save(false);


        $upload_result = UploadForm::upload_files();
        if ($upload_result && is_array($upload_result)) {
          if (count($upload_result['file']) > 0) {
            foreach ($upload_result['file'] as $key => $value) {
              $FileInfo = new FileInfo();
              $FileInfo->belong_id = $GoodsAuth->id;
              $FileInfo->file_path = $value['file_name'];
              $FileInfo->file_desc = $value['file_desc'];

              $FileInfo->save(false);
            }
          }
        }

        if (isset($upload_result['error']) && count($upload_result['error']) > 0) {
          $upload_info = '';
          foreach ($upload_result['error'] as $key => $value) {
            $upload_info .=  $value.'<br>';
          }
        }

        Message::result_json(2,'添加成功'.$upload_info); 
    }

    public function actionEdit($id){
        $GoodsAuth = GoodsAuth::findone($id);
        return $this->render('edit', ['goods_auth'=>$GoodsAuth,'id'=>$id]);
    }

    public function actionView($id){
        $GoodsAuth = GoodsAuth::findone($id);
        return $this->render('view', ['goods_auth'=>$GoodsAuth,'id'=>$id]);
    }

    public function actionUpdate($id){

    }    

    public function actionAdmit($id,$process_status){
      //修改订单状态
      $GoodsAuth = GoodsAuth::findone($id);

      $this->base_admit($GoodsAuth,'goods_auth_status',$process_status);

      Message::result_json(1,'复核成功');
    }

    public function actionInsertGoods(){
      $goods_id = Yii::$app->request->get('goods_id',0);
      $order_id = Yii::$app->request->get('order_id',0);
      $search_data = Yii::$app->request->get('search_data',0);

      if (!$order_id) {
        message::result_json(2,'数据错误');
      }else{
        $order = GoodsAuth::findone($order_id);
      }

      $add_goods_error = [];
      if ($goods_id) {
          if (is_array($goods_id)) {

            foreach ($goods_id as $key => $value) {
              $goods = Goods::findone($value);
              if ($goods) {
                //插入数据
                $GoodsAuthGoods = new GoodsAuthGoods();
                $add_goods_result = $GoodsAuthGoods->AddGoods($order_id,$goods);
                if (!$add_goods_result) {
                    $add_goods_error[] = $GoodsAuthGoods->add_goods_error;
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
            $GoodsAuthGoods = new GoodsAuthGoods();
            $add_goods_result = $GoodsAuthGoods->AddGoods($order_id,$goods);

            if (!$add_goods_result) {
                $add_goods_error = $GoodsAuthGoods->add_goods_error;
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
        $GoodsAuthGoods = GoodsAuthGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        $GoodsAuthGoods->delete();

        message::result_json(1,'删除成功');
    }

    public function actionUpdateGoodsLabel(){
        $data_type  = trim(Yii::$app->request->get('data_type'));
        $value  = trim(Yii::$app->request->get('value'));
        $id  = Yii::$app->request->get('id');
        $data_id  = Yii::$app->request->get('data_id');

        $CustomOrderGoods = GoodsAuthGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        if($CustomOrderGoods){
          $CustomOrderGoods->$data_type = $value;
          $CustomOrderGoods->save(false);
          message::result_json(1,'chengg',$value);
        }else{
          message::result_json(2,'没有此记录');
        }
    }
 

}
