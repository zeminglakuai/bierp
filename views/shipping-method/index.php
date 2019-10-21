<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\common\config\lang_value_config;


$this->params['breadcrumbs'][] = $this->context->page_title;
?>
<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['shipping-method/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'shipping_name','label_name'=>'配送方式'],
                                  ['type'=>'text','label'=>'area_desc','label_name'=>'区域描述'],                                  
                                  ]
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\ShippingMethodConfig',
                      'model_name'=>'shipping-method',
                      'title_arr'=>['id'=>1,'shipping_name'=>1,'area_desc'=>0,'basic_price'=>1,'per_kg_price'=>1,'add_user_name'=>0,'add_time'=>0],
                      'search_allowed' => ['shipping_name'=>2,'area_desc'=>2,],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'js','action'=>'edit','icorn_name'=>'edit','confirm'=>0],
                                      'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete','icorn_name'=>'trash','confirm'=>1]
                                    ],
                      'scope'=>true,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加'.$this->context->page_title,'id'=>'create_shipping','type'=>'js','url'=>Url::to(["shipping-method/create"])],
                                            'export'=>['label_name'=>'导出','id'=>'export','type'=>'js','sublist'=>[['label_name'=>'导出找货单','id'=>'1']]],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>