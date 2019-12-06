<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;
use app\common\config\sys_config;

$this->title = '数据字典';
$this->params['breadcrumbs'][] = '数据字典';
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\Dictionary',
                      'model_name'=>$this->context->id,
                      'title_arr'=>['id'=>1,'dictionary_desc'=>0],
                      'page_size'=>20,
                      'opration' => ['edit'=>['lable_name'=>'查看','type'=>'link','action'=>'view','icorn_name'=>'edit','confirm'=>0],
                                    ],
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>
