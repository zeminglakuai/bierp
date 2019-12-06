<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="relate_file_list" id="relate_file_list_<?= $type?>">
  <?php if(isset($file_list)){
    foreach ($file_list as $key => $value) {
    ?>
      <div class="relate_file_item" id="custom_file_<?= $value->id ?>">
        <div class="relate_file_div" id="">
          <div class="relate_file_operate_bg">
          </div>
          <div class="relate_file_operate">
            <i class="fa fa-trash delete_file_<?= $type?>"  data-id="<?= $value->id ?>"></i>
          </div>
          <?php
            $postfix = substr($value->file_path, strrpos($value->file_path, '.')+1);
            $img_arr = ['bmp','jpeg','jpg','png','gif'];
            if (in_array(strtolower($postfix), $img_arr)) {
            ?>
              <a href="/<?= $value->file_path?>" target="_blank"><img src="<?= Yii::getAlias('@web/').$value->file_path ?>"></a>
            <?php
            }else{
          ?>
            <div class="" style="position:absolute;left:50%;margin-left:-50px;top:1em;z-index:999;font-size:4em;font-weight:900;">
              <?php
                echo substr($value->file_path, strrpos($value->file_path, '.')+1);
              ?>
            </div>
            <a href="/<?= $value->file_path?>" target="_blank"><img src="<?= Yii::getAlias('@web/').'img/bg.png'?>"></a>
          <?php
            }
          ?>
        </div>
        <div class="upload_file_desc">
          	<div class="input-group">
	            <input type="text" class="form-control" name="" id="desc_<?= $value->id ?>" placeholder="文件描述" value="<?= $value->file_desc?>">
	            <span class="input-group-btn">
	             	<button type="button" class="btn btn-primary update_file_desc_<?= $type?>" data-id="<?= $value->id ?>"> <i class="fa fa-refresh"></i></button>
	            </span>
        	</div>
        </div>
      </div>
    <?php
    }
  }?>
</div>
<div class="fl" style="width:20%;">
  <a href="javascript:void();" class="btn btn-danger" id="upload_new_file_<?= $type?>">点击添加文件</a>
</div>
<div class="cl"></div>
<script type="text/javascript">

$(".update_file_desc_<?= $type?>").click(function(){
	var file_id = $(this).attr('data-id');
  var desc = $("#desc_"+file_id).val();

	$.get('/<?= $model;?>/update-file-desc',{id:file_id,desc:desc,type:'<?= $type?>'},function(result){
    if (result.error == 1) {
      layer.msg(result.message);
    }else{
      layer.msg(result.message);
    }
	},'json');
});
 
$(".delete_file_<?= $type?>").click(function(){
  var file_id = $(this).attr('data-id');
  if (!confirm('确认删除该文件？')) {
    return false;
  };
  $.get('/<?= $model;?>/delete-file',{id:file_id},function(result){
    if (result.error == 1) {
      layer.msg(result.message);
      $("#custom_file_"+file_id).remove();
    }else{
      layer.msg(result.message);
    }
  },'json');
});

$("#upload_new_file_<?= $type?>").click(function(){
  var length = $("#relate_file_list_<?= $type?>").children().length;
  length++;
  var new_img = '<div class="relate_file_item" id="file_div_'+length+'">'+
  '<div class="relate_file_div" id="file_<?= $type?>_'+length+'">'+
  '<div class="relate_file_operate_bg">'+
  '</div>'+
  '<div class="relate_file_operate">'+
  '<i class="fa fa-trash" id="delete_file_<?= $type?>_'+length+'"></i>'+
  '</div>'+
  '</div>'+
  '<div class="upload_file_desc">'+
  '<input type="text" name="file_desc[]" class="form-control" placeholder="文件描述">'+
  '<input type="hidden" name="file_type[]" value="<?= $type?>">'+
  '</div>'+
  '<div class="upload_file_div">'+
  '<input type="file" name="file[]" class="file_btn" id="file_btn_<?= $type?>_'+length+'">'+
  '</div>'+
  '</div>';

  $("#relate_file_list_<?= $type?>").append(new_img);

  $('#file_btn_<?= $type?>_'+length).trigger('click'); 

  $('#file_btn_<?= $type?>_'+length).change(function(){
    show_file_icon('file_btn_<?= $type?>_'+length,'file_<?= $type?>_'+length);
  });

  $('#delete_file_<?= $type?>_'+length).click(function(){
    $('#file_div_'+length).remove();
  });

});

function show_file_icon(file_id,div){  

    //检验是否为图像文件  
    var file = document.getElementById(file_id).files[0];  
    if(!/image\/\w+/.test(file.type)){  
      $("#"+div).append('<img src="/img/file_icon/file.jpg" alt="" />');
    }else{
      var reader = new FileReader();  
      //将文件以Data URL形式读入页面  
      reader.readAsDataURL(file);  
      reader.onload=function(e){  
          $("#"+div).append('<img src="' + this.result +'" alt="" />');
      }
    }
}

</script>