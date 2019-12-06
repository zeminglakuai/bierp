<?php

use app\common\models\Platform;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\Depart;
use app\common\models\Store;
use app\common\models\Project;
use app\common\models\Role;
use app\common\config\sys_config;
use app\common\models\DictionaryValue;
?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>

<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
    <form id="order_form" class="form-horizontal m-t">
      <?= app\common\widgets\Input::widget(['label_name'=>'收货人','name'=>"Purchase[consignee]",'value'=>$purchase->consignee,'tips'=>'']); ?>
      <?= app\common\widgets\Input::widget(['label_name'=>'联系方式','name'=>"Purchase[consignee_tel]",'value'=>$purchase->consignee_tel,'tips'=>'']); ?>
      <?= app\common\widgets\Input::widget(['label_name'=>'地址','name'=>"Purchase[address]",'value'=>$purchase->address,'tips'=>'']); ?>
      <input type="hidden" name="Purchase[purchase_type]" value="6" />
      <input type="hidden" name="Purchase[fid]" value="<?=$order_id;?>" />
    </form>
  </div>
</div>
<?= app\common\widgets\Submit::widget(['model'=>$purchase,'model_name'=>"purchase",'form_name'=>'order_form']); ?>
<script type="text/javascript">
$("#supplier_id").tokenInput("<?= Url::to(['/purchase/search-supplier'])?>",
  {
    theme:'facebook', 
    hintText:'请输入要搜索的关键字',
    tokenLimit:1
  }
);
</script>
 