<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

?>

<?= Html::jsFile('@web/ueditor/ueditor.config.js') ?>
<?= Html::jsFile('@web/ueditor/ueditor.all.min.js') ?>
<?= Html::jsFile('@web/ueditor/lang/zh-cn/zh-cn.js') ?>

<form action="" id="help_form">
  <script type="text/plain" id="editor" name="Help[content]" style="height:400px;"><?= $help->content?></script> 
  <script type="text/javascript">var editor = UE.getEditor('editor');</script>
  <input type="hidden" name="Help[module]" value="<?= $module?>">
</form>
<div class="row" style="padding:10px 0px;">
  <div class="form-group">
      <label class="col-sm-1 control-label"></label>
      <div class="col-sm-10">
        <button class="btn btn-danger" id="edit_help"><i class="icon-save"></i>编辑</button>
      </div>
  </div>
</div>
<script>
$("#edit_help").click(function(){
   var formData = new FormData($("#help_form")[0]);
   var update_url = create_url('<?= Url::to(["help/update","module"=>$module])?>');
     $.ajax({  
          url: update_url,  
          type: 'POST',  
          data: formData,
          dataType:'json',
          async: false,  
          cache: false,  
          contentType: false,
          processData: false,
          success: function (result) {
            if(result.error == 1){
              layer.msg(result.message);
            }else{
              layer.msg(result.message);
            } 
          },  
          error: function (result) {  
              layer.msg('发生错误');
          }
     });
});
</script>