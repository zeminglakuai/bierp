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


$this->params['breadcrumbs'][] = ['label'=>'商品授权','url'=>['index']];
$this->params['breadcrumbs'][] = $goods_auth->supplier_name;
?>

<?= app\common\widgets\OrderTitle::widget([
                      'model'=>'app\common\models\GoodsAuth',
                      'id'=>$id,
                      'model_name'=>'goods-auth',                      
                      'label_arr' => ['supplier_name'=>'',
                                      'authFile&file_desc'=>['link'=>true,'id'=>'auth-file','url'=>Url::to(["sell-contract/view"])],
                                      'remark'=>''
                                      ],
                      'status_label' => 'goods_auth_status',
                      ])
?>

<?= app\common\widgets\GoodsList::widget([
                      'model'=>'app\common\models\GoodsAuthGoods',
                      'order_id'=>$id,
                      'model_name'=>'goods-auth',
                      'init_condition'=>[['auth_id'=>$goods_auth->id]],
                      'title_arr'=>['id'=>['sort_able'=>1,'edit_able'=>0,'width'=>'4%'],
                                    'goods_name'=>['sort_able'=>0,'edit_able'=>0,'width'=>'15%'],
                                    'goods_sn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],
                                    'isbn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'market_price'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    ],
                      'opration' => [['lable_name'=>'删除','type'=>'js','action'=>'delete-goods','icorn_name'=>'trash','confirm'=>1]]
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加商品','id'=>'create_goods_auth_goods','type'=>'js','url'=>Url::to(["goods-auth/create-goods","order_id"=>$goods_auth->id])],
                                            'admit_process'=>['controller'=>$this->context->id,'url'=>Url::to(['/'.$this->context->id.'/admit','id'=>$goods_auth->id]),'model'=>$goods_auth,'status_label'=>'goods_auth_status'],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>