<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\Store;

use app\common\models\Role;
use app\common\config\lang_value_config;
?>

<?= app\common\widgets\OrderForm::widget(['form_data'=>[
                                                        ['type'=>'select','label_name'=>'仓库','name'=>'OtherExportOrder[store_id]','value'=>$export_order->store_id,'tips','id','init_value'=>ArrayHelper::map(Store::get_store(),'id','store_name'),],
                                                        ['type'=>'select','label_name'=>'出库类型','name'=>'OtherExportOrder[other_export_order_type]','value'=>$export_order->other_export_order_type,'tips','id','init_value'=>lang_value_config::$other_export_order_type,],
                                                        ['type'=>'text','label_name'=>'备注','name'=>'OtherExportOrder[remark]','value'=>$export_order->remark,'tips','id','init_value',],
                                                      ]
                                        ]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$supplier,'model_name'=>"other-export-order",'form_name'=>'order_form']); ?>
