<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\Depart;
use app\common\models\Store;
use app\common\models\Role;
use app\common\config\sys_config;
use app\common\models\DictionaryValue;
?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>


<?= app\common\widgets\OrderForm::widget(['form_data'=>[
                    ['type'=>'text','label_name'=>'供货商','name'=>'SpecimenPurchase[supplier_id]','value'=>$specimen_purchase->supplier_id,'tips','id'=>'supplier_id',],
                    ['type'=>'select','label_name'=>'支付方式','name'=>"SpecimenPurchase[pay_method]",'value'=>$specimen_purchase->pay_method,'init_value'=>ArrayHelper::map(DictionaryValue::getValueList(4),'dictionary_value','dictionary_value'),'tips'=>''],
                    ['type'=>'select','label_name'=>'配送方式','name'=>"SpecimenPurchase[shipping_method]",'value'=>$specimen_purchase->shipping_method,'init_value'=>ArrayHelper::map(DictionaryValue::getValueList(5),'dictionary_value','dictionary_value'),'tips'=>''],
                    ['type'=>'radio','label_name'=>'是否退还','name'=>"SpecimenPurchase[if_return]",'value'=>$specimen_purchase->if_return,'tips'=>'','id'=>'if_return','init_value'=>[['value'=>1,'label_name'=>'是'],['value'=>0,'label_name'=>'否']]],
                    ['type'=>'data_picker','label_name'=>'提醒退还时间','name'=>"SpecimenPurchase[remind_return_time]",'value'=>$specimen_purchase->remind_return_time,'tips'=>'','id'=>'remind_return_time'],
                    ['type'=>'text','label_name'=>'备注','name'=>'SpecimenPurchase[remark]','value'=>$specimen_purchase->remark,'tips','id','init_value',],
                   ]
                  ]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$specimen_purchase,'model_name'=>"specimen-purchase",'form_name'=>'order_form']); ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<?= Html::cssFile('@web/css/plugins/iCheck/custom.css') ?>
<?= Html::jsFile('@web/js/plugins/iCheck/icheck.min.js') ?>

<script type="text/javascript">
$("#supplier_id").tokenInput("<?= Url::to(['/specimen-purchase/search-supplier'])?>",
  {
    theme:'facebook', 
    hintText:'请输入要搜索的关键字',
    tokenLimit:1
  }
);
$(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
</script>
 