<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\Depart;
use app\common\models\Role;
use app\common\config\sys_config;
use app\common\models\Store;
?>

<?= app\common\widgets\OrderForm::widget(['form_data'=>[['type'=>'select','label_name'=>'收货仓库','name'=>'PurchaseOrder[store_id]','init_value'=>ArrayHelper::map(Store::get_store(),'id','store_name'),],
                                                        ['type'=>'text','label_name'=>'备注','name'=>'SellOrder[remark]','value'=>$sell_order->remark,'tips','id','init_value',],
                                                      ]
                                        ]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$sell_order,'model_name'=>"sell-order",'form_name'=>'order_form']); ?>