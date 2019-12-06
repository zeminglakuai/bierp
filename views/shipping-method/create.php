<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\Depart;
use app\common\models\Role;
use app\common\models\DictionaryValue;
use app\common\config\sys_config;
?>

<?= app\common\widgets\OrderForm::widget(['form_data'=>[
        ['type'=>'select','label_name'=>'快递方式','name'=>'ShippingMethodConfig[shipping_id]','value'=>$shipping_config->shipping_id,'tips','id'=>'supplier','init_value'=>ArrayHelper::map(DictionaryValue::find()->where(['dictionary_id'=>7])->all(), 'id', 'dictionary_value'),],
        ['type'=>'text','label_name'=>'区域','name'=>'ShippingMethodConfig[area_desc]','value'=>$shipping_config->area_desc,'tips','id'=>'supplier','init_value',],
        ['type'=>'text','label_name'=>'首重金额','name'=>'ShippingMethodConfig[basic_price]','value'=>$shipping_config->basic_price,'tips','id'=>'supplier','init_value',],
        ['type'=>'text','label_name'=>'续重(每千克)','name'=>'ShippingMethodConfig[per_kg_price]','value'=>$shipping_config->per_kg_price,'tips','id','init_value',],
      ]
]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$shipping_config,'model_name'=>"shipping-method",'form_name'=>'order_form']); ?>
