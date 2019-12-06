<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Depart;
use app\common\models\Mession;

use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class MessionController extends BaseController
{

    public $page_title = '任务下达';
    public $title_model = 'app\common\models\Mession';
    public $status_label = 'mession_status';

    public function beforeAction($action)
    {
      parent::beforeAction($action);

      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','admit',];
      $admit_allow_arr = ['edit'];

      $scope_model = 'app\common\models\Mession';
      $status_label = 'mession_status';

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
      $Mession = Mession::findone($id);
      $Mession->delete();
      message::result_json(1,'删除成功');
    }

    //添加
    public function actionInsert(){
        $Mession = new Mession();
        $Mession->load(Yii::$app->request->post());

        if (!$Mession->mession_name) {
          Message::result_json(2,'请填写计划名称');
        }

        if (!$Mession->mession_depart_id) {
          Message::result_json(2,'请选择部门');
        }

        $mession_depart = Depart::findone($Mession->mession_depart_id);
        if (!$mession_depart) {
          Message::result_json(2,'部门不存在，请重新选择');
        }else{
          $Mession->mession_depart_name = $mession_depart->depart_name;
        }
        $Mession->mession_status = 0;
        $Mession->save(false);
        Message::result_json(1,'添加成功');
    }

    public function actionView($id){
        $Mession = Mession::findone($id);
        if (strlen($Mession->mession_data) < 15) {
            $Mession->mession_data = ['1'=>['name'=>'一月','desc'=>''],
                                      '2'=>['name'=>'二月','desc'=>''],
                                      '3'=>['name'=>'三月','desc'=>''],
                                      '4'=>['name'=>'四月','desc'=>''],
                                      '5'=>['name'=>'五月','desc'=>''],
                                      '6'=>['name'=>'六月','desc'=>''],
                                      '7'=>['name'=>'气月','desc'=>''],
                                      '8'=>['name'=>'八月','desc'=>''],
                                      '9'=>['name'=>'九月','desc'=>''],
                                      '10'=>['name'=>'十月','desc'=>''],
                                      '11'=>['name'=>'十一月','desc'=>''],
                                      '12'=>['name'=>'十二月','desc'=>''],
            ];
        }else{
          $Mession->mession_data = unserialize($Mession->mession_data);

        }

        return $this->render('view', ['mession'=>$Mession,'id'=>$id]);
    }

    public function actionEdit($id){
        $Purchase = Purchase::findone($id);
        return $this->render('edit', ['purchase'=>$Purchase,'id'=>$id]);
    }

    public function actionUpdate($id){

    }    

    public function actionAdmit($id){
      //修改订单状态
      $Purchase = Purchase::findone($id);
      if ($Purchase->custom_order_status >= 1) {
          Message::result_json(2,'单据已复核!');
      }
      $Purchase->admit_user_id = Yii::$app->session['manage_user']['id'];
      $Purchase->admit_user_name = Yii::$app->session['manage_user']['admin_name'];
      $Purchase->admit_time = time();
      $Purchase->custom_order_status = 1;
      $Purchase->save(false);

      //复合就生成入库单  等待收获入库
      

      Message::result_json(1,'复核成功');

    }

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

        $CustomOrderGoods = CustomOrderGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        if($CustomOrderGoods){
          $CustomOrderGoods->$data_type = $value;
          $CustomOrderGoods->save(false);
          message::result_json(1,'chengg',$value);
        }else{
          message::result_json(2,'没有此记录');
        }
    }

    //生成 出库单
    public function actionCreateOutport($id){

    }

    //生成 采购单
    public function actionCreatePurchaseOrder($id){

    }

}
