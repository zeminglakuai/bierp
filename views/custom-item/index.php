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
                      'url'=>Url::to(['ask-price/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'item_name','label_name'=>'项目名称'],
                                  ['type'=>'text','label'=>'custom_name','label_name'=>'客户名称'],
                                  ]
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\CustomItem',
                      'model_name'=>$this->context->id,
                      'title_arr'=>['id'=>1,'item_name'=>0,'add_user_name'=>0,'depart_name'=>0,'add_time'=>0,],
                      'search_allowed' => ['item_name'=>2,'custom_name'=>2],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'link','action'=>'edit','icorn_name'=>'edit','confirm'=>0],
                                     'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete','icorn_name'=>'trash','confirm'=>1],
                                    ],
                      'scope'=>true,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加'.$this->context->page_title,'id'=>'create_stock_lock','type'=>'js','url'=>Url::to(['/'.$this->context->id."/create"])],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>
