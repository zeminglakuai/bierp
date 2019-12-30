<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\ShippingMethodConfig;
use app\common\models\DictionaryValue;
use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class ShippingMethodController extends BaseController
{
  public $page_title = '配送方案';
  public function beforeAction($action)
  {
    parent::beforeAction($action);

    //检查当前单据用户是不是有操作权限
    $admited_allow_arr = ['edit', 'update', 'delete', 'price'];

    $scope_model = 'app\common\models\ShippingMethodConfig';
    $status_label = 'shipping_method_status';

    if ($this->user_scope($action, $need_privi_arr, $admited_allow_arr, $scope_model, $status_label)) {
      return true;
    } else {
      return false;
    }
  }

  //删除
  public function actionDelete($id)
  {
    $ShippingMethodConfig = ShippingMethodConfig::findone($id);
    if ($ShippingMethodConfig) {
      $ShippingMethodConfig->delete();
      message::result_json(1, '删除成功');
    } else {
      message::result_json(2, '数据不存在');
    }
  }

  //添加询价单
  public function actionInsert()
  {
    $ShippingMethodConfig = new ShippingMethodConfig();
    $ShippingMethodConfig->load(Yii::$app->request->post());

    if (!$ShippingMethodConfig->shipping_id) {
      Message::result_json(2, '配送方式不能为空');
    } else {
      $shipping_value = DictionaryValue::findone($ShippingMethodConfig->shipping_id);
      $ShippingMethodConfig->shipping_name = $shipping_value->dictionary_value;
    }


    $ShippingMethodConfig->shipping_method_status = 0;

    $ShippingMethodConfig->save(false);
    Message::result_json(1, '添加成功');
  }

  public function actionEdit($id)
  {
    $this->layout = 'empty';
    $ShippingMethodConfig = ShippingMethodConfig::findone($id);
    return $this->render('create', ['shipping_config' => $ShippingMethodConfig, 'id' => $id]);
  }
  public function actionPrice($id)
  {
    $this->layout = 'empty';
    $ShippingMethodConfig = ShippingMethodConfig::findone($id);
    $ship_price = Yii::$app->db->createCommand("select * from ship_price where ship_id=" . $id)->queryOne();

    return $this->render('price', ['shipping_config' => $ShippingMethodConfig, 'id' => $id, 'ship_price' => $ship_price]);
  }
  public function actionUpdate($id)
  {
    $ShippingMethodConfig = ShippingMethodConfig::findone($id);
    $ShippingMethodConfig->load(Yii::$app->request->post());

    if (!$ShippingMethodConfig->shipping_id) {
      Message::result_json(2, '配送方式不能为空');
    } else {
      $shipping_value = DictionaryValue::findone($ShippingMethodConfig->shipping_id);
      $ShippingMethodConfig->shipping_name = $shipping_value->dictionary_value;
    }

    $ShippingMethodConfig->shipping_method_status = 0;

    $ShippingMethodConfig->save(false);
    Message::result_json(1, '编辑成功');
  }

  public function actionAdmit($id)
  {
    //修改订单状态
    $AskPriceOrder = AskPriceOrder::findone($id);
    if ($AskPriceOrder->ask_price_order_status >= 2) {
      Message::result_json(2, '单据已复核!');
    }

    Message::result_json(1, '复核成功');
  }
}
