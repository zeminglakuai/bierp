<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="ibox">
  <div class="ibox-content">
    <form class="form-horizontal" id="template_form">
      <?= app\common\widgets\Input::widget(['label_name'=>'模板名称','name'=>"ExportTable[template_name]",'tips'=>'']); ?>
      <input type="hidden" name="ExportTable[module_name]" value="<?= $module?>" />
      <input type="hidden" name="ExportTable[type]" value="<?= $type?>" />
    </form>
    </div>
  </div>
<?= app\common\widgets\Submit::widget(['model_name'=>"export-template",'form_name'=>'template_form','defined_function'=>'true']); ?>

<script type="text/javascript">
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
</script>