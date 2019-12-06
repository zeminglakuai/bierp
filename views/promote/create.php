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
<form id="custom_order_form" class="form-horizontal m-t">
<div class="form-group">
    <label class="col-sm-2 control-label">单据名称：</label>
    <div class="col-sm-9">
        <input type="text" name="CustomOrder[order_name]" class="form-control" value="<?= $custom_order->order_name?>" />
        
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">客户：</label>
    <div class="col-sm-9">
          <input type="text" id="custom" name="CustomOrder[custom_id]" class="form-control">
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">备注：</label>
    <div class="col-sm-9" id="role_list">
        <input type="hidden" name="id" class="form-control" value="<?= $custom_order->id?>">
        <input type="text" name="CustomOrder[remark]" class="form-control" value="<?= $custom_order->remark?>">
    </div>
</div>
</form>
  </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label"></label>
    <div class="col-sm-9" id="role_list">
      <?php if(isset($custom_order->id)){
      ?>
      <button class="btn btn-danger" id="edit_custom_order"><i class="icon-save"></i>编辑</button>
      <?php
      }else{
      ?>
      <button class="btn btn-danger" id="add_custom_order"><i class="icon-save"></i>添加</button>
      <?php
      }?>
    </div>
</div>


<script>
<?php if(isset($custom_order->id)){
?>
$("#edit_custom_order").click(function(){
    var param = $("#custom_order_form").serialize();
    $.post("<?= Url::to(['custom-order/edit'])?>",param,function(result){
        if(result.error == 1){
            parent.layer.msg(result.message);
            parent.location.reload();
        }else{
            layer.alert(result.message);
        }
    },'json');
});
<?php
}else{
?>
$("#add_custom_order").click(function(){
    var param = $("#custom_order_form").serialize();
    $.post("<?= Url::to(['custom-order/insert'])?>",param,function(result){
        if(result.error == 1){
            layer.msg(result.message);
            parent.location.reload();
        }else{
            layer.msg(result.message);
        }
    },'json');
});
<?php
}?>
  

$("#custom").tokenInput("<?= Url::to(['/custom-order/token-custom-search'])?>",
  {
    theme:'facebook', 
    hintText:'请输入要搜索的关键字',
    tokenLimit:1
  }
);
  
</script>
 