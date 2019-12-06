<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\common\models\ApprovalProcess;
use app\common\config\lang_value_config;


$this->params['breadcrumbs'][] = $this->context->page_title;
?>
<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['receipt/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'order_sn','label_name'=>'单号'],
                                  ['type'=>'text','label'=>'recieve_name','label_name'=>'客户'],
                                  ['type'=>'select','label'=>'payment_status','label_name'=>'单据状态','value_arr'=>ApprovalProcess::getApprovalValue($this->context->id)], 
                                  ]
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\Receipt',
                      'model_name'=>'receipt',
                      'title_arr'=>['id'=>1,'order_sn'=>0,'relate_order_sn'=>0,'fee'=>0,'custom_name'=>0,'add_user_name'=>0,'add_time'=>0,'remain_time'=>0,'admit_time'=>0,'receipt_status'=>1,],
                      'search_allowed' => ['order_sn'=>2,'custom_name'=>2,'receipt_status'=>1,'order_id'=>2],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'js','action'=>'view','icorn_name'=>'edit','confirm'=>0],
                                      'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete','icorn_name'=>'trash','confirm'=>1]
                                    ],
                      'scope'=>true,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加'.$this->context->page_title,'id'=>'create_receipt','type'=>'js','url'=>Url::to(["receipt/create"])],
                                            'export'=>['label_name'=>'导出','id'=>'export','type'=>'js','sublist'=>[['label_name'=>'导出找货单','id'=>'1'],['label_name'=>'导出找货单','id'=>'2'],['label_name'=>'导出找货单','id'=>'3']]],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>