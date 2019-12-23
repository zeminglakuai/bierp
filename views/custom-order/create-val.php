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
      <?= app\common\widgets\Input::widget(['label_name' => '字段名', 'name' => "val_name", 'value' => $val->name, 'tips' => '']); ?>
      <?= app\common\widgets\Input::widget(['label_name' => '字段英文名', 'name' => "val_name_en", 'value' => $val->val_name_en, 'tips' => '']); ?>
      <?= app\common\widgets\Input::widget(['label_name' => '字段值', 'name' => "val", 'value' => $val->val, 'tips' => '']); ?>
    </form>
  </div>
</div>

<?= app\common\widgets\Submit::widget(['model' => $contact, 'model_name' => "supplier_contact", 'form_name' => 'data_form', 'defined_function' => true]); ?>
<script type="text/javascript">
  $("#add_supplier_contact").click(function() {
    var formData = new FormData($("#data_form")[0]);
    $.ajax({
      url: '<?= Url::to(["/custom-order/insert-val", "id" => $id]) ?>',
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