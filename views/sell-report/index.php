<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\DictionaryValue;
use app\common\models\ApprovalProcess;
use app\common\models\Depart;

$this->params['breadcrumbs'][] = $this->context->page_title;
?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['/'.$this->context->id.'/index']),
                      'condition'=>[
                                  ['type'=>'select','label'=>'year','label_name'=>'查询年份','value_arr'=>ApprovalProcess::getApprovalValue($this->context->id)],
                                  ['type'=>'select','label'=>'month','label_name'=>'查询月份','value_arr'=>ApprovalProcess::getApprovalValue($this->context->id)],
                                  ['type'=>'select','label'=>'depart_id','label_name'=>'部门','value_arr'=>ArrayHelper::map(Depart::findall(),'depart_id','depart_name')],
                                  ]
                      ])
?>

 
<?= app\common\widgets\OperateBar::widget([
                                             
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>
