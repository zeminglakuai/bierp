<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;

use app\common\config\lang_value_config;

?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\Payment',
                      'model_name'=>'purchase',
                      'init_condition'=>[['order_id'=>$id,'model'=>'Purchase']],
                      'title_arr'=>['id'=>0,'order_sn'=>0,'relate_order_sn'=>0,'order_total'=>0,'supplier_name'=>0,'fee'=>0,'remain_time'=>0,'payment_status'=>0,'add_time'=>0,'add_user_name'=>0],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'js','action'=>'edit-payment','icorn_name'=>'edit','confirm'=>0],
                                      'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete-payment','icorn_name'=>'trash','confirm'=>1]
                                    ],
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加付款单','id'=>'create_payment','type'=>'js','url'=>Url::to(["purchase/create-payment","id"=>$id])],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>