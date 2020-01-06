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

<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
    <form id="order_form" class="form-horizontal m-t">
      <div class="form-group">
        <label class="col-sm-2 control-label">项目：</label>
        <div class="col-sm-9">
          <input type="text" id="custom" name="ExportOrder[custom_id]" class="form-control" value="<?= $export_order->custom_id ?>">
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label">备注：</label>
        <div class="col-sm-9" id="role_list">
          <input type="hidden" name="id" class="form-control" value="<?= $sell_order->id ?>">
          <input type="text" name="ExportOrder[remark]" class="form-control" value="<?= $export_order->remark ?>">
        </div>
      </div>
    </form>
  </div>
</div>

<?= app\common\widgets\Submit::widget(['model' => $supplier, 'model_name' => "export-order", 'form_name' => 'order_form']); ?>
<script type="text/javascript">
  $("#custom").tokenInput("<?= Url::to(['/export-order/token-custom-search']) ?>", {
    theme: 'facebook',
    hintText: '请输入要搜索的关键字',
    tokenLimit: 1
    <?php if ($export_order->custom_id >= 1) { ?>,
      prePopulate: [{
        id: '<?= $export_order->custom_id ?>',
        name: '<?= $export_order->custom_name ?>'
      }],
    <?php } ?>
  });
</script>