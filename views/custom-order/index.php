<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\common\models\ApprovalProcess;
use app\common\models\Contact;
use app\common\config\lang_value_config;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = '客户方案列表';
?>

<?= app\common\widgets\PageSearch::widget([
  'url' => Url::to(['custom-order/index']),
  'condition' => [
    ['type' => 'text', 'label' => 'order_sn', 'label_name' => '单号'],
    ['type' => 'text', 'label' => 'order_name', 'label_name' => '名称'],

    ['type' => 'select', 'label' => 'custom_order_status', 'label_name' => '状态', 'value_arr' => ApprovalProcess::getApprovalValue($this->context->id)],
  ]
])
?>

<?= app\common\widgets\DataList::widget([
  'model' => 'app\common\models\CustomOrder',
  'model_name' => 'custom-order',
  'title_arr' => $this->context->title_arr,
  'search_allowed' => $this->context->search_allowed,
  'opration' => [
    'edit' => ['lable_name' => '编辑', 'type' => 'link', 'action' => 'view', 'icorn_name' => 'edit', 'confirm' => 0],
    'delete' => ['lable_name' => '删除', 'type' => 'js', 'action' => 'delete', 'icorn_name' => 'trash', 'confirm' => 1]
  ],
  'scope' => $this->context->scope,
])
?>

<?= app\common\widgets\OperateBar::widget([
  'create' => ['label_name' => '添加客户方案', 'id' => 'create_custom_order', 'type' => 'js', 'url' => Url::to(["custom-order/create"])],
  'export' => ['label_name' => '导出', 'module_name' => 'custom-order', 'type' => 'title'],
  'refresh' => ['label_name' => '刷新', 'type' => 'js', 'id' => 'add_custom', 'icon' => 'plus'],
])
?>