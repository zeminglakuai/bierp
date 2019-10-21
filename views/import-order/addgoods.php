<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\common\config\lang_config;
use app\common\config\lang_value_config;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->params['breadcrumbs'][] = ['label'=>$this->context->page_title.'列表','url'=>['index']];
//$this->params['breadcrumbs'][] = $import_order->order_sn;
?>



<?= app\common\widgets\GoodsList::widget([
    'model'=>'app\common\models\ImportOrderGoods',
//    'order_id'=>$import_order->id,
    'model_name'=>'import-order',
//    'init_condition'=>$init_condition,
    'title_arr'=>[
        'goods_name'=>['sort_able'=>0,'edit_able'=>0,'width'=>'15%'],
        'goods_sn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],
        'isbn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
        'goods_store_info'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],
        'real_number'=>['sort_able'=>1,'edit_able'=>1,'width'=>'10%','total'=>true],
    ],
    'update_label_url' => Url::to(['import-order/update-goods-label']),
    'opration' => [['lable_name'=>'删除','type'=>'js','action'=>'delete-goods','icorn_name'=>'trash','confirm'=>1]],
    'present_action'=>'view',
])
?>

<?= app\common\widgets\OperateBar::widget([
    'create'=>['label_name'=>'添加商品','id'=>'create_sell_order_goods','type'=>'js','url'=>Url::to(["import-order/create-goods"])],
//    'admit_process'=>['controller'=>$this->context->id,'url'=>Url::to(['/'.$this->context->id.'/admit','id'=>$import_order->id]),'model'=>$import_order,'status_label'=>'import_order_status'],
    'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
    'other_btn'=>[],
])
?>
