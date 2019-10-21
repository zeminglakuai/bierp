<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\Depart;
use app\common\models\Role;
use app\common\models\Store;
use app\common\config\sys_config;
use app\common\models\DictionaryValue;
?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>

<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
    <form id="order_form" class="form-horizontal m-t">
      <?= app\common\widgets\Input::widget(['label_name'=>'供货商','name'=>"MaterielPurchase[supplier_id]",'value'=>$materiel_purchase->supplier_id,'tips'=>'','id'=>'supplier_id']); ?>
      <?= app\common\widgets\Select::widget(['label_name'=>'支付方式','name'=>"MaterielPurchase[pay_method]",'value'=>$materiel_purchase->pay_method,'init_value'=>ArrayHelper::map(DictionaryValue::getValueList(4),'dictionary_value','dictionary_value'),'tips'=>'']); ?>
      <?= app\common\widgets\Select::widget(['label_name'=>'配送方式','name'=>"MaterielPurchase[shipping_method]",'value'=>$materiel_purchase->shipping_method,'init_value'=>ArrayHelper::map(DictionaryValue::getValueList(5),'dictionary_value','dictionary_value'),'tips'=>'']); ?>
      <?= app\common\widgets\Select::widget(['label_name'=>'仓库','name'=>"MaterielPurchase[store_id]",'value'=>$materiel_purchase->store_id,'init_value'=>ArrayHelper::map(Store::find()->all(),'id','store_name'),'tips'=>'']); ?>
      <?= app\common\widgets\Input::widget(['label_name'=>'备注','name'=>"MaterielPurchase[remark]",'value'=>$materiel_purchase->remark,'tips'=>'']); ?>
    </form>
  </div>
</div>
<?= app\common\widgets\Submit::widget(['model'=>$materiel_purchase,'model_name'=>"materiel-purchase",'form_name'=>'order_form']); ?>
<script type="text/javascript">
$("#supplier_id").tokenInput("<?= Url::to(['/materiel-purchase/search-supplier'])?>",
  {
    theme:'facebook', 
    hintText:'请输入要搜索的关键字',
    tokenLimit:1
  }
);
</script>
 