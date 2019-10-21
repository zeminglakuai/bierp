<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;

use app\common\config\lang_value_config;

?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\Receipt',
                      'model_name'=>'sell-order',
                      'init_condition'=>[['order_id'=>$id,'model'=>'SellOrder']],
                      'title_arr'=>['id'=>0,'order_sn'=>0,'relate_order_sn'=>0,'custom_name'=>0,'fee'=>0,'remain_time'=>0,'receipt_status'=>0,'add_time'=>0,'add_user_name'=>0],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'js','action'=>'edit-receipt','icorn_name'=>'edit','confirm'=>0],
                                      'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete-receipt','icorn_name'=>'trash','confirm'=>1]
                                    ],
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加收款单','id'=>'create_receipt','type'=>'js','url'=>Url::to(["sell-order/create-receipt","id"=>$id])],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>