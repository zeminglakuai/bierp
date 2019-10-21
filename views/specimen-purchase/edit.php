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

<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>
 
<?= app\common\widgets\OrderForm::widget(['form_data'=>[['type'=>'select','label_name'=>'付款方式','name'=>'SpecimenPurchase[pay_method]','value'=>$specimen_purchase->pay_method,'tips','id','init_value'=>ArrayHelper::map(DictionaryValue::getValueList(4),'dictionary_value','dictionary_value'),],
                                                        ['type'=>'select','label_name'=>'配送方式','name'=>'SpecimenPurchase[shipping_method]','value'=>$specimen_purchase->pay_method,'tips','id','init_value'=>ArrayHelper::map(DictionaryValue::getValueList(5),'dictionary_value','dictionary_value'),],
                                                        ['type'=>'text','label_name'=>'备注','name'=>'SpecimenPurchase[remark]','value'=>$specimen_purchase->remark,'tips','id','init_value',],
                                                      ]
                                        ]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$specimen_purchase,'model_name'=>"specimen-purchase",'form_name'=>'order_form']); ?>
<script type="text/javascript">
  
</script>
 