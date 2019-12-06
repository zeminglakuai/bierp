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
$this->params['breadcrumbs'][] = $daifa_order->order_sn;
?>

<?= app\common\widgets\OrderTitle::widget([
                      'model'=>'app\common\models\DaifaOrder',
                      'controller_id'=>$this->context->id,
                      'id'=>$id,
                      'model_name'=>$this->context->id,
                      'label_arr' => ['supplier_name'=>'',
//                                      'sellOrder&order_sn'=>['link'=>true,'id'=>'sellOrder','url'=>Url::to(["sell-order/view",'id'=>$daifa_order->sell_order_id])],
                          'custom_name'=>['link'=>true,'id'=>'custom','url'=>Url::to(["custom/view"])],
                          'shipping_method'=>'',
                          'shipping_fee'=>'',
                          'materiel_fee'=>'',
                          /*  'consignee'=>'',
                            'tel'=>'',
                            'address'=>['col'=>6],*/
                          'shipping_code'=>'',
                                      'remark'=>''
                                      ],
                      'status_label' => $this->context->status_label,
                      ])
?>

<?= app\common\widgets\GoodsList::widget([
                      'model'=>'app\common\models\DaifaOrderGoods',
                      'order_id'=>$id,
                      'model_name'=>'ask-price',
                      'init_condition'=>[['order_id'=>$id]],
                      'title_arr'=>['id'=>['sort_able'=>1,'edit_able'=>0,'width'=>'4%'],
                                    'goods_name'=>['sort_able'=>0,'edit_able'=>0,'width'=>'18%'],
                                    'goods_sn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],
                                    'isbn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'market_price'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'sale_price'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'num'=>['sort_able'=>1,'edit_able'=>0,'width'=>'5%'],
                                    'send_number'=>['sort_able'=>0,'edit_able'=>1,'width'=>'8%'],
                                    ],
                      'update_label_url' => Url::to(['/'.$this->context->id.'/update-goods-label']),
                      'opration' => [['lable_name'=>'删除','type'=>'js','action'=>'delete-goods','icorn_name'=>'trash','confirm'=>1]]
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
    'create'=>['label_name'=>'添加商品','id'=>'create_sell_order_goods','type'=>'js','url'=>Url::to(["daifa-order/create-goods","order_id"=>$daifa_order->id,"type"=>1])],

    'export'=>['label_name'=>'导出','module_name'=>$this->context->id,'type'=>'detail','id'=>$daifa_order->id],
                                            'admit_process'=>['controller'=>$this->context->id,'url'=>Url::to(['/'.$this->context->id.'/admit','id'=>$daifa_order->id]),'model'=>$daifa_order,'status_label'=>$this->context->status_label],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            'backup'=>['label_name'=>'返回','url'=>Url::to(['/daifa-order/']),],
                                            'other_btn'=>[],
                                            ])
?>

 