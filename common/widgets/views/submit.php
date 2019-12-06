<?php
use yii\helpers\Url;
?>

<div class="row" style="padding:10px 0px;margin-bottom:5em;">
  <div class="form-group">
      <label class="col-sm-2 control-label"></label>
      <div class="col-sm-10">
        <?php if(isset($model) && $model){
        ?>
		    <input type="hidden" name="id" value="<?= $model->id?>" >
        <button class="btn btn-danger" id="edit_<?= $model_name?>"><i class="icon-save"></i>保存</button>
        <?php
        }else{
        ?>
        <button class="btn btn-danger" id="add_<?= $model_name?>"><i class="icon-save"></i>添加</button>
        <?php
        }?>
      </div>
  </div>
</div>
<script>
<?php if(!$defined_function){
?>
  <?php if(isset($model) && $model){
  ?>
  $("#edit_<?= $model_name?>").click(function(){
     var index = parent.layer.getFrameIndex(window.name);
     var formData = new FormData($("#<?= $form_name?>")[0]);
     var update_url = create_url('<?= $url?$url:Url::to(["/".$model_name."/update"])?>');
       $.ajax({  
            url: update_url<?= $model->id || $model['id']?'+"id='.($model->id?$model->id:$model['id']).'"':''?> ,
            type: 'POST',  
            data: formData,
            dataType:'json',
            async: true,  
            cache: false,
            contentType:false,
            processData: false,
            success: function (result) {
              if(result.error == 1){
                <?php
                if ($if_has_parent) {
                ?>
                parent.layer.msg(result.message);
                parent.layer.close(index);
                <?php
                }else{
                ?>
                layer.msg(result.message);
                <?php
                }
                ?>

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
  $("#add_<?= $model_name?>").click(function(){
      var formData = new FormData($( "#<?= $form_name?>" )[0]);
       $.ajax({
            url: '<?= $url?$url:Url::to(["/".$model_name."/insert"])?>' ,  
            type: 'POST',  
            data: formData,
            dataType:'json',
            async: true,  
            cache: false,
            contentType:false,
            processData: false,  
            success: function (result) {  
              if(result.error == 1){
                <?php
                if ($if_has_parent) {
                ?>
                parent.layer.msg(result.message);
                parent.location.reload();
                <?php
                }else{
                ?>
                layer.msg(result.message);
                <?php
                }
                ?>
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
<?php
}?>
</script>