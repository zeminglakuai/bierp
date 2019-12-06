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
$this->params['breadcrumbs'][] = $other_export_order->order_sn;
?>

<?= app\common\widgets\OrderTitle::widget([
                      'model'=>'app\common\models\OtherExportOrder',
                      'id'=>$id,
                      'model_name'=>'other-export-order',
                      'label_arr' => [
                                      'remark'=>''
                                      ],
                      'status_label' => 'other_export_order_status',
                      ])
?>

<?= app\common\widgets\GoodsList::widget([
                      'model'=>'app\common\models\OtherExportOrderGoods',
                      'order_id'=>$id,
                      'model_name'=>'other-export-order',
                      'init_condition'=>[['order_id'=>$other_export_order->id]],
                      'title_arr'=>['id'=>['sort_able'=>1,'edit_able'=>0,'width'=>'4%'],
                                    'goods_name'=>['sort_able'=>0,'edit_able'=>0,'width'=>'15%'],
                                    'goods_sn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],
                                    'isbn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'market_price'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'number'=>['sort_able'=>1,'edit_able'=>0,'width'=>'5%','total'=>true],
                                    'store_code'=>['sort_able'=>0,'edit_able'=>1,'width'=>'5%'],                                  
                                    ],
                      'update_label_url' => Url::to(['other-export-order/update-goods-label']),
                      'opration' => [['lable_name'=>'删除','type'=>'js','action'=>'delete-goods','icorn_name'=>'trash','confirm'=>1]],
                      'present_action'=>'view',
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
      'create'=>['label_name'=>'添加商品','id'=>'create_other_order_goods','type'=>'js','url'=>Url::to(["other-export-order/create-goods","order_id"=>$other_export_order->id])],
      'export'=>['label_name'=>'导出','id'=>'export','type'=>'js','sublist'=>[['label_name'=>'导出找货单','id'=>'1']]],
      'admit_process'=>['controller'=>$this->context->id,'url'=>Url::to(['/'.$this->context->id.'/admit','id'=>$other_export_order->id]),'model'=>$other_export_order,'status_label'=>'other_export_order_status'],
      'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
      'backup'=>['label_name'=>'返回','url'=>'/other-export-order'],
      ])
?>

