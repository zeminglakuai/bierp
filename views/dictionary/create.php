<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;
use app\common\config\sys_config;

 
?>
<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
    <div class="row">
        <form method="post" class="form-horizontal" id="data_form"  enctype="multipart/form-data">
            <?= app\common\widgets\Input::widget(['label_name'=>'字典值','name'=>"DictionaryValue[dictionary_value]",'value'=>$dictionary_value->dictionary_value,'tips'=>'']); ?>
            <input type="hidden" name="DictionaryValue[dictionary_id]" value="<?= $id?>" />
            <input type="hidden" name="id" value="<?= $dictionary_value->id ?>" />
            
        </form>
    </div>
  </div>
</div>
<?= app\common\widgets\Submit::widget(['model'=>$dictionary_value,'model_name'=>"dictionary_value",'form_name'=>'data_form','defined_function'=>true]); ?>

<script>
<?php if(isset($dictionary_value->id)){
?>
$("#edit_dictionary_value").click(function(){
   var index = parent.layer.getFrameIndex(window.name);
   var formData = new FormData($("#data_form")[0]);
   var update_url = create_url('<?= $url?$url:Url::to(["/dictionary/update-value"])?>');
     $.ajax({  
          url: update_url+'id='+<?= $dictionary_value->id?> ,  
          type: 'POST',  
          data: formData,
          dataType:'json',
          async: false,  
          cache: false,  
          contentType: false,
          processData: false,
          success: function (result) {
            if(result.error == 1){
              parent.layer.msg(result.message);
              parent.layer.close(index);
            }else{
              layer.msg(result.message);
            } 
          },  
          error: function (result) {  
              layer.msg('发生错误');
          }
     });
});
<?php
}else{
?>
$("#add_dictionary_value").click(function(){
    var formData = new FormData($( "#data_form" )[0]);
    var insert_url = create_url('<?= $url?$url:Url::to(["/dictionary/insert-value","id"=>$id])?>');
     $.ajax({  
          url: insert_url,  
          type: 'POST',  
          data: formData,
          dataType:'json',
          async: false,  
          cache: false,  
          contentType: false,  
          processData: false,  
          success: function (result) {  
            if(result.error == 1){
              parent.layer.msg(result.message);
              parent.location.reload();
            }else{
              layer.msg(result.message);
            } 
          },  
          error: function (result) {  
              layer.msg('发生错误');
          }
     });
});
<?php
}?>
</script>
