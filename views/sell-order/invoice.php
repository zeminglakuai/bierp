<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;

use app\common\config\lang_value_config;

?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\SellInvoice',
                      'model_name'=>'sell-order',
                      'init_condition'=>[['order_id'=>$id,'model'=>'SellOrder']],
                      'title_arr'=>['id'=>0,'order_sn'=>0,'relate_order_sn'=>0,'custom_name'=>0,'fee'=>0,'remain_time'=>0,'sell_invoice_status'=>0,'add_time'=>0,'add_user_name'=>0],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'js','action'=>'edit-invoice','icorn_name'=>'edit','confirm'=>0,'id_name'=>'invoice_id'],
                                      'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete-invoice','icorn_name'=>'trash','confirm'=>1,'id_name'=>'invoice_id']
                                    ],
                      ]);
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加发票','id'=>'create_receipt','type'=>'js','url'=>Url::to(["sell-order/create-invoice","id"=>$id])],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ]);
?>