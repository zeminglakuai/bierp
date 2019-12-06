<?php
use yii\helpers\Html;
use yii\helpers\Url;

use app\common\config\lang_config;
?>
<?= Html::cssFile('@web/css/plugins/iCheck/custom.css') ?>
<?= Html::jsFile('@web/js/plugins/iCheck/icheck.min.js') ?>
<div class="ibox">
  <div class="ibox-content">
    <form class="form-horizontal" id="template_form">
      <?= app\common\widgets\Input::widget(['label_name'=>'模板名称','name'=>"ExportTable[template_name]",'tips'=>'','value'=>$export_table->template_name]); ?>
      <input type="hidden" name="ExportTable[module_name]" value="<?= $module?$module:$export_table->module_name ?>" />
      <input type="hidden" name="ExportTable[type]" value="<?= $type?$type:$export_table->type?>" />
      <?php if($title_label){
      ?>
      <div class="form-group">
        <label class="col-sm-2 control-label">表头字段</label>
        <div class="col-sm-10">
          <div class="row">
          <?php foreach ($title_label as $key => $value) {
          ?>
          <div class="col-sm-3">
            <div class="checkbox i-checks">
                <label><input type="checkbox" name="title[<?= $key?>]" value="<?= isset(lang_config::${$key})?lang_config::${$key}:$value ?>" <?= isset($export_table->data['title'][$key])?'checked=""':($export_table?'':'checked=""')?>> <i></i> <?= isset(lang_config::${$key})?lang_config::${$key}:$value?> </label>
            </div>
          </div>
          <?php
          }?>
          </div>
        </div>
      </div>
      <?php
      }?>

      <?php 
 
      if($detail_label){
      ?>
      <div class="form-group">
        <label class="col-sm-2 control-label">表头字段</label>
        <div class="col-sm-10">
          <div class="row">
          <?php foreach ($detail_label as $key => $value) {
          ?>
          <div class="col-sm-3">
            <div class="checkbox i-checks">
                <label><input type="checkbox" name="detail[<?= $key?>]" value="<?= isset(lang_config::${$key})?lang_config::${$key}:$value ?>"  <?= isset($export_table->data['detail'][$key])?'checked=""':($export_table?'':'checked=""')?> > <i></i> <?= isset(lang_config::${$key})?lang_config::${$key}:$value?> </label>
            </div>
          </div>
          <?php
          }?>
          </div>
        </div>
      </div>
      <?php
 
      }?>
    </form>
  </div>
</div>

<?= app\common\widgets\Submit::widget(['model'=>$export_table,'model_name'=>"export-template",'form_name'=>'template_form','defined_function'=>true]); ?>

<script>

  var index = parent.layer.getFrameIndex(window.name);
  $("#add_export-template").click(function(){
    var formData = new FormData($( "#template_form" )[0]);
     $.ajax({
          url: '<?= $url?$url:Url::to(["/export-template/insert"])?>' ,  
          type: 'POST',  
          data: formData,
          dataType:'json',
          async: true,  
          cache: false,  
          contentType: false,  
          processData: false,  
          success: function (result) {  
            if(result.error == 1){
              var module = '<?= $module?>';
              parent.layer.msg(result.message);
              //重载模板列表
              var parent_frame = parent.$("#template_list");

              parent.$.get('<?= Url::to(['/export-template/template-list'])?>',{module:module},function(result){
                parent_frame.html(result.content);
              },'json');
              parent.layer.close(index);
            }else{
              layer.msg(result.message);
            } 
          },  
          error: function (result) {  
              layer.msg('发生错误');
          }
     });
  })

  $("#edit_export-template").click(function(){
    var formData = new FormData($( "#template_form" )[0]);
     $.ajax({
          url: '<?= $url?$url:Url::to(["/export-template/update","id"=>$export_table->id])?>' ,  
          type: 'POST',  
          data: formData,
          dataType:'json',
          async: true,  
          cache: false,  
          contentType: false,  
          processData: false,  
          success: function (result) {  
            if(result.error == 1){
              var module = '<?= $export_table->module_name ?>';
              parent.layer.msg(result.message);
              //重载模板列表
              var parent_frame = parent.$("#template_list");

              parent.$.get('<?= Url::to(['/export-template/template-list'])?>',{module:module},function(result){
                parent_frame.html(result.content);
              },'json');
              parent.layer.close(index);
            }else{
              layer.msg(result.message);
            } 
          },  
          error: function (result) {  
              layer.msg('发生错误');
          }
     });
  })


  $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
</script>