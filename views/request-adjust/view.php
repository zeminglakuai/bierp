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


$this->params['breadcrumbs'][] = ['label'=>$this->context->page_title,'url'=>['index']];
$this->params['breadcrumbs'][] = $request_adjust_order->order_sn;
?>

<?= app\common\widgets\OrderTitle::widget([
                      'model'=>'app\common\models\RequestAdjustOrder',
                      'id'=>$id,
                      'model_name'=>$this->context->id,                      
                      'label_arr' => ['to_store_name'=>'',
                                      'from_store_name'=>'',
                                      'consignee'=>'',
                                      'tel'=>'',
                                      'address'=>'',
                                      'remark'=>''
                                      ],
                      'status_label' => 'request_adjust_order_status',
                      ])
?>

<?= app\common\widgets\GoodsList::widget([
                      'model'=>'app\common\models\RequestAdjustOrderGoods',
                      'order_id'=>$id,
                      'model_name'=>'sell-order',
                      'init_condition'=>[['order_id'=>$request_adjust_order->id]],
                      'title_arr'=>['id'=>['sort_able'=>1,'edit_able'=>0,'width'=>'4%'],
                                    'goods_name'=>['sort_able'=>0,'edit_able'=>0,'width'=>'15%'],
                                    'goods_sn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],
                                    'isbn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'market_price'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],

                                    'number'=>['sort_able'=>1,'edit_able'=>1,'width'=>'5%','total'=>true],
//                                    'goods_store_info'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],
                                    ],
                      'update_label_url' => Url::to(['/'.$this->context->id.'/update-goods-label']),
                      'opration' => [['lable_name'=>'删除','type'=>'js','action'=>'delete-goods','icorn_name'=>'trash','confirm'=>1]],
                      'present_action'=>'view',
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
      'create'=>['label_name'=>'添加商品','id'=>'create_sell_order_goods','type'=>'js','url'=>Url::to(["/".$this->context->id."/create-goods","order_id"=>$request_adjust_order->id])],
      'export'=>['label_name'=>'导出','module_name'=>$this->context->id,'type'=>'detail','id'=>$request_adjust_order->id],
      'admit_process'=>['controller'=>$this->context->id,'url'=>Url::to(['/'.$this->context->id.'/admit','id'=>$request_adjust_order->id]),'model'=>$request_adjust_order,'status_label'=>'request_adjust_order_status'],
      'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
      'backup'=>['label_name'=>'返回','url'=>Url::to(['/custom-order/']),],
      ])
?>
 
