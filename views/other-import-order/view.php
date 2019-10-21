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
$this->params['breadcrumbs'][] = $other_import_order->order_sn;
?>

<?= app\common\widgets\OrderTitle::widget([
                      'model'=>'app\common\models\OtherImportOrder',
                      'id'=>$other_import_order->id,
                      'model_name'=>'other-import-order',
                      'label_arr' => ['custom_name'=>'',
                                      'store_name'=>'',
                                      'remark'=>''
                                      ],
                      'status_label' => 'other_import_order_status',
                      ])
?>

<?= app\common\widgets\GoodsList::widget([
                      'model'=>'app\common\models\OtherImportOrderGoods',
                      'order_id'=>$other_import_order->id,
                      'model_name'=>'other-import-order',
                      'init_condition'=>$init_condition,
                      'title_arr'=>[
                                    'goods_name'=>['sort_able'=>0,'edit_able'=>0,'width'=>'15%'],
                                    'goods_sn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],
                                    'isbn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'market_price'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'number'=>['sort_able'=>1,'edit_able'=>0,'width'=>'5%','total'=>true],
                                    'goods_store_info'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],
                                    'store_code'=>['sort_able'=>0,'edit_able'=>1,'width'=>'10%'],
                                    'return_type'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'deal_type'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%','type'=>'select','init_value'=>lang_value_config::$deal_type,'tips'=>'退回仓库，需要检查商品完好程度，无法入库，则直接报损出库。'],
                                    ],
                      'update_label_url' => Url::to(['other-import-order/update-goods-label']),
                      'opration' => [['lable_name'=>'删除','type'=>'js','action'=>'delete-goods','icorn_name'=>'trash','confirm'=>1]],
                      'present_action'=>'view',
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
      'create'=>['label_name'=>'添加商品','id'=>'create_other_import_order_goods','type'=>'js','url'=>Url::to(["other-import-order/create-goods","order_id"=>$other_import_order->id])],
      'admit_process'=>['controller'=>$this->context->id,'url'=>Url::to(['/'.$this->context->id.'/admit','id'=>$other_import_order->id]),'model'=>$other_import_order,'status_label'=>'other_import_order_status'],
      'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
      'other_btn'=>[],
      ])
?>
 