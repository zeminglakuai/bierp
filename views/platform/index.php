<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->params['breadcrumbs'][] = $this->context->page_title;
?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<?= app\common\widgets\PageSearch::widget([
  'url' => Url::to(['platform/index']),
  'condition' => [
    ['type' => 'text', 'label' => 'supplier_name', 'label_name' => '项目名称'],
    ['type' => 'token_input', 'table_name' => 'custom_id', 'name_name' => 'custom_id', 'label' => 'custom_id', 'label_name' => '客户', 'token_url' => Url::to(['platform/token-custom-order'])],
  ]
])
?>

<?= app\common\widgets\DataList::widget([
  'model' => 'app\common\models\Platform',
  'model_name' => 'platform',
  'title_arr' => ['id' => 1, 'plat_name' => 0, 'hezuoxingshi' => 0, 'add_user_name' => 0, 'add_user_name' => 0, 'add_time' => 0],
  'search_allowed' => ['plat_name' => 2, 'custom_id' => 1],
  'opration' => [
    'edit' => ['lable_name' => '编辑', 'type' => 'js', 'action' => 'edit', 'icorn_name' => 'edit', 'confirm' => 0],
    'delete' => ['lable_name' => '删除', 'type' => 'js', 'action' => 'delete', 'icorn_name' => 'trash', 'confirm' => 1],
  ],
  'scope' => true,
])
?>

<?= app\common\widgets\OperateBar::widget([
  'create' => ['label_name' => '添加项目', 'id' => 'create_plat', 'type' => 'js', 'url' => Url::to(["platform/create"])],
  'refresh' => ['label_name' => '刷新', 'type' => 'js', 'id' => 'add_custom', 'icon' => 'plus'],
])
?>

