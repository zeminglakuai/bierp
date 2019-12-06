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
$this->params['breadcrumbs'][] = $sell_order_return->order_sn;
?>

<?= app\common\widgets\OrderTitle::widget([
                      'model'=>'app\common\models\SellOrderReturn',
                      'id'=>$id,
                      'model_name'=>'sell-order-return',
                      'label_arr' => ['custom&custom_name'=>['link'=>true,'id'=>'custom','url'=>Url::to(["custom/view"])],
                                      'sellOrder&order_sn'=>['link'=>true,'id'=>'sellOrder','url'=>Url::to(["sell-order/view"])],
                                      'return_reason'=>'',
                                      'consignee'=>'',
                                      'tel'=>'',
                                      'address'=>'',
                                      'store_name'=>'',
                                      'remark'=>''
                                      ],
                      'status_label' => 'sell_order_return_status',
                      ])
?>


<?= app\common\widgets\GoodsList::widget([
                      'model'=>'app\common\models\SellOrderReturnGoods',
                      'order_id'=>$id,
                      'model_name'=>$this->context->id,
                      'init_condition'=>[['order_id'=>$sell_order_return->id]],
                      'title_arr'=>['id'=>['sort_able'=>1,'edit_able'=>0,'width'=>'4%'],
                                    'goods_name'=>['sort_able'=>0,'edit_able'=>0,'width'=>'15%'],
                                    'goods_sn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],
                                    'market_price'=>['sort_able'=>0,'edit_able'=>0,'width'=>'5%'],
                                    'sale_price'=>['sort_able'=>1,'edit_able'=>0,'width'=>'5%'],
                                    'number'=>['sort_able'=>1,'edit_able'=>0,'width'=>'5%','total'=>true],
                                    'goods_store_info'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'return_price'=>['sort_able'=>1,'edit_able'=>1,'width'=>'6%','total'=>true],
                                    'return_number'=>['sort_able'=>1,'edit_able'=>1,'width'=>'6%','total'=>true],
                                    'return_type'=>['sort_able'=>1,'edit_able'=>0,'width'=>'9%','type'=>'select','init_value'=>lang_value_config::$return_type],
                                    'note'=>['sort_able'=>0,'edit_able'=>1,'width'=>'13%'],
                                    ],
                      'update_label_url' => Url::to(['sell-order-return/update-goods-label']),
                      'opration' => [['lable_name'=>'删除','type'=>'js','action'=>'delete-goods','icorn_name'=>'trash','confirm'=>1]],
                      'present_action'=>'view',
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'export'=>['label_name'=>'导出','module_name'=>$this->context->id,'type'=>'detail','id'=>$sell_order_return->id],
                                            'admit_process'=>['controller'=>$this->context->id,'url'=>Url::to(['/'.$this->context->id.'/admit','id'=>$sell_order_return->id]),'model'=>$sell_order_return,'status_label'=>'sell_order_return_status'],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>
