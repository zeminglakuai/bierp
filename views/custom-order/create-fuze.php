<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\common\config\lang_config;
use app\common\config\lang_value_config;


?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>

<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>
<?= Html::cssFile('@web/css/plugins/iCheck/custom.css') ?>

<?= Html::jsFile('@web/js/plugins/iCheck/icheck.min.js') ?>

<div class="ibox">
  <div class="ibox-content">
    <form method="post" class="form-horizontal" id="data_form" enctype="multipart/form-data">
      <div class="form-group">
        <label class="col-sm-2 control-label">选择交接人</label>
        <div class="col-sm-9">
          <select name="fuze_id" class="form-control">
            <?php
            if (isset($list)) {
              foreach ($list as $key => $value) { ?>
                <option value="<?= $value['id'] ?>" <?php if ($value['id'] == $data['fuze_id']) {
                                                      echo 'selected';
                                                    } ?>><?= $value['admin_name'] ?></option>
            <?php  }
            }
            ?>
          </select>
        </div>
      </div>
      <input type="hidden" name="old_fuze_id" value=" <?= $data['fuze_id'] ?>" />
      <?= app\common\widgets\Input::widget(['label_name' => '备注', 'name' => "remark", 'value' => $val->remark, 'tips' => '']); ?>
    </form>
  </div>
</div> <?= app\common\widgets\Submit::widget(['model' => $contact, 'model_name' => "supplier_contact", 'form_name' => 'data_form', 'defined_function' => true]); ?> <script type="text/javascript">
  $("#add_supplier_contact").click(function() {
    var formData = new FormData($("#data_form")[0]);
    $.ajax({

      url: '<?= Url::to(["/custom-order/insert-fuze", "id" => $id]) ?>',
      type: 'POST',
      data: formData,
      dataType: 'json',
      async: false,
      cache: false,
      contentType: false,
      processData: false,
      success: function(result) {
        if (result.error == 1) {
          parent.location.href = '<?= Url::to(["/custom-order/view", "id" => $id]) ?>';
        } else {
          layer.msg(result.message);
        }
      },
      error: function(result) {
        layer.msg('发生错误');
      }
    });
  });

  /* $("#edit_supplier_contact").click(function(){
  var formData = new FormData($( "#data_form" )[0]);
  $.ajax({
  url: '<?= Url::to(["custom/update-contact", "id" => $id, "contact_id" => $contact->id]) ?>',
  type: 'POST',
  data: formData,
  dataType:'json',
  async: false,
  cache: false,
  contentType: false,
  processData: false,
  success: function (result) {
  if(result.error == 1){
  parent.location.href= '<?= Url::to(["custom/edit", "id" => $id, "present_panel" => "contact"]) ?>';
  }else{
  layer.msg(result.message);
  }
  },
  error: function (result) {
  layer.msg('发生错误');
  }
  });
  }); */
  $(document).ready(function() {
    $(".i-checks").iCheck({
      checkboxClass: "icheckbox_square-green",
      radioClass: "iradio_square-green",
    })
  });
</script>