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
                      'url'=>Url::to(['theme/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'supplier_name','label_name'=>'主题名称'],
                                  
                                  ]
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\theme',
                      'model_name'=>'platform',
                      'title_arr'=>['id'=>1,'theme_name'=>0,'start_time'=>0,'end_time'=>0,'remark'=>0],
                      'search_allowed' => ['plat_name'=>2,'custom_id'=>1],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'js','action'=>'edit','icorn_name'=>'edit','confirm'=>0],
                                      'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete','icorn_name'=>'trash','confirm'=>1],
                                    ],
                      'scope'=>true,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            // 'create'=>['label_name'=>'添加主题','id'=>'create_plat','type'=>'js','url'=>Url::to(["theme/create"])],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>

