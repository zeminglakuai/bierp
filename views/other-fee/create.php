<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\Depart;
use app\common\models\Role;
use app\common\models\Store;
use app\common\models\DictionaryValue;
use app\common\config\sys_config;

?>

<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>


<?= app\common\widgets\OrderForm::widget(['form_data'=>[
        ['type'=>'text','label_name'=>'费用金额','name'=>'OtherFee[fee]','value'=>$other_fee->fee,'tips'],
        ['type'=>'select','label_name'=>'费用类型','name'=>'OtherFee[fee_ttype_name]','value'=>$other_fee->fee_ttype_name,'tips','id','init_value'=>ArrayHelper::map(DictionaryValue::getValueList(12),'dictionary_value','dictionary_value')],
        ['type'=>'text','label_name'=>'费用用途','name'=>'OtherFee[fee_couse]','value'=>$other_fee->fee_couse,'tips'],
        ['type'=>'select','label_name'=>'发票类型','name'=>'OtherFee[invoice_type]','value'=>$other_fee->invoice_type,'tips','init_value'=>ArrayHelper::map(DictionaryValue::getValueList(10),'dictionary_value','dictionary_value')],
        ['type'=>'token_input','label_name'=>'相关客户','name'=>'OtherFee[custom_id]','id'=>'custom_id','value'=>$other_fee->custom_id,'table_name'=>'custom','name_name'=>'custom_name','label'=>'custom_id','token_url'=>Url::to(['/'.$this->context->id.'/token-custom-search'])],
        ['type'=>'text','label_name'=>'备注','name'=>'OtherFee[remark]','value'=>$other_fee->remark,'tips','id','init_value',],
      ]
]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$other_fee,'model_name'=>$this->context->id,'form_name'=>'order_form']); ?>


<?php
if($other_fee->id){
?>
	
<?php
}
?>
