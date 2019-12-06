<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\Depart;
use app\common\models\Role;
use app\common\config\sys_config;
?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>

<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<?= app\common\widgets\OrderForm::widget(['form_data'=>[
        ['type'=>'text','label_name'=>'供货商','name'=>'Payment[supplier_id]','value'=>$payment->supplier_id,'tips','id'=>'supplier','init_value',],
        ['type'=>'text','label_name'=>'金额','name'=>'Payment[fee]','value'=>$payment->fee,'tips','id'=>'supplier','init_value',],
        ['type'=>'text','label_name'=>'备注','name'=>'Payment[remark]','value'=>$payment->remark,'tips','id','init_value',],
      ]
]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$payment,'model_name'=>"sell-order",'form_name'=>'order_form']); ?>
<script type="text/javascript">
$("#supplier").tokenInput("<?= Url::to(['/payment/token-supplier-search'])?>",
  {
    theme:'facebook', 
    hintText:'请输入要搜索的关键字',
    tokenLimit:1
  }
);
  
</script>
 