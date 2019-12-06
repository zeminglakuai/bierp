<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\DictionaryValue;
use app\common\models\ApprovalProcess;

$this->params['breadcrumbs'][] = $this->context->page_title;
?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['/'.$this->context->id.'/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'order_sn','label_name'=>'单号'],
                                  ['type'=>'text','label'=>'add_user_name','label_name'=>'添加用户'],
                                  ['type'=>'select','label'=>'materiel_request_status','label_name'=>'审核状态','value_arr'=>ApprovalProcess::getApprovalValue($this->context->id)],
                                  ]
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\MaterielRequest',
                      'model_name'=>$this->context->id,
                      'title_arr'=>['id'=>1,'order_sn'=>0,'total'=>0,'add_user_name'=>0,'depart_name'=>0,'add_time'=>0,'status_name'=>0,],
                      'search_allowed' => ['order_sn'=>2,'add_user_name'=>2,'materiel_request_status'=>1],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'link','action'=>'view','icorn_name'=>'edit','confirm'=>0],
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
