<?php

use app\common\models\Platform;
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

<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
    <form id="order_form" class="form-horizontal m-t">
      <?= app\common\widgets\Input::widget(['label_name'=>'项目名称','name'=>"Purchase[order_name]",'value'=>$purchase->order_name,'tips'=>'']); ?>
      <?= app\common\widgets\Input::widget(['label_name'=>'供货商','name'=>"Purchase[supplier_id]",'value'=>$purchase->supplier_id,'tips'=>'','id'=>'supplier_id']); ?>
      <?= app\common\widgets\Select::widget(['label_name'=>'支付方式','name'=>"Purchase[pay_method]",'value'=>$purchase->pay_method,'init_value'=>ArrayHelper::map(DictionaryValue::getValueList(4),'dictionary_value','dictionary_value'),'tips'=>'']); ?>
      <?= app\common\widgets\Select::widget(['label_name'=>'配送方式','name'=>"Purchase[shipping_method]",'value'=>$purchase->shipping_method,'init_value'=>ArrayHelper::map(DictionaryValue::getValueList(5),'dictionary_value','dictionary_value'),'tips'=>'']); ?>

        <?= app\common\widgets\Input::widget(['label_name'=>'平台备注','name'=>"Purchase[platform_beizhu]",'value'=>$purchase->platform_beizhu,'tips'=>'']); ?>

        <div class="form-group">
            <label class="col-sm-2 control-label"> 付款方式</label>
            <div class="col-sm-9">
                <select class="form-control" name="Purchase[pay_type]">
                    <option value="">请选择付款方式</option>
                    <option value="2">一次性付款</option>
                    <option value="1">多次付款</option>
                    <option value="3">月结商户</option>
                </select>    </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label"> 采购类型</label>
            <div class="col-sm-9">
                <select class="form-control" name="Purchase[purchase_type]">
                    <option value="">请选择付款方式</option>
                    <option value="1">项目采购</option>
                    <option value="2">样品采购</option>
                    <option value="3">非经营性采购</option>
                    <option value="4">配料物流采购</option>
                </select>    </div>
        </div>
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
 