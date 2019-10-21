<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\ApprovalProcess;

$this->params['breadcrumbs'][] = $this->context->page_title;
?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['ask-price/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'order_sn','label_name'=>'单号'],
                                  ['type'=>'text','label'=>'supplier_name','label_name'=>'供货商名称'],
                                  ['type'=>'select','label'=>'daifa_order_status','label_name'=>'审核状态','value_arr'=>ApprovalProcess::getApprovalValue($this->context->id)],
                                  ]
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\DaifaOrder',
                      'model_name'=>$this->context->id,
                      'title_arr'=>['id'=>1, 'order_sn'=>0,'supplier_name'=>0,'add_user_name'=>0,'add_time'=>0,'status_name'=>0,'goodsNumber'=>0],
                      'search_allowed' => $this->context->search_allowed,
                      'opration' => ['add'=>['lable_name'=>'添加','type'=>'link','action'=>'add','icorn_name'=>'add','confirm'=>0],                                                           'edit'=>['lable_name'=>'编辑','type'=>'link','action'=>'view','icorn_name'=>'edit','confirm'=>0],
                                      'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete','icorn_name'=>'trash','confirm'=>1],
                                    ],
                      'scope'=>$this->context->scope,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
    'create'=>['label_name'=>'添加单据','id'=>'add','type'=>'js','url'=>Url::to(["daifa-order/add"])],
        'export'=>['label_name'=>'导出','module_name'=>$this->context->id,'type'=>'title','id'=>$import_order->id],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>
