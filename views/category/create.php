<?php
use yii\helpers\Html;
use yii\helpers\Url;

use app\admin\config\config;

?>
<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
    <form action="" id="cate_form" method="post" class="form-horizontal">
      <div class="form-group">
          <label class="col-sm-2 control-label">上级分类</label>
          <div class="col-sm-9">
              <select name="cat_id" class="form-control">
                <option value="0">上级分类</option>
                  <?= $cat_select_list?>
              </select>
          </div>
      </div>
      <div class="form-group">
          <label class="col-sm-2 control-label">分类名称</label>
          <div class="col-sm-9">
              <input type="text" name="cat_name" class="form-control" value="<?= $cate->cat_name?>"  placeholder = '分类名称'/>
          </div>
      </div>
    </form>
  </div>
    <div class="row" style="padding:10px 0px;">
      <div class="form-group">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-10">
          <?php if(isset($cate) && $cate){
          ?>
          <input type="hidden" name="id" value="<?= $cate->cat_id?>" >
          <button class="btn btn-danger" id="edit_cate"><i class="icon-save"></i>保存</button>
          <?php
          }else{
          ?>
          <button class="btn btn-danger" id="add_cate"><i class="icon-save"></i>添加</button>
          <?php
          }?>
        </div>
    </div>
</div>

<script>
  $("#add_cate").click(function(){
      var param = $("#cate_form").serialize();
      $.post("<?= Url::to(['category/add'])?>",param,function(result){
          if(result.error == 1){
              parent.layer.msg(result.message);
              parent.location.reload();
          }else{
              layer.msg(result.message);
          }
      },'json');
  });

  $("#edit_cate").click(function(){

      var param = $("#cate_form").serialize();
      $.post("<?= Url::to(['category/update','id'=>$cate->cat_id])?>",param,function(result){
          if(result.error == 1){
              parent.layer.msg(result.message);
              parent.location.reload();
          }else{
              layer.msg(result.message);
          }
      },'json');
  });


</script>