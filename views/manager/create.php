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
<form id="user_form" class="form-horizontal m-t">
<div class="form-group">
    <label class="col-sm-2 control-label">用户姓名：</label>
    <div class="col-sm-9">
        <input type="text" name="Admin[real_name]" class="form-control" value="<?= $admin->real_name?>" />
      	
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">登录用户名：</label>
    <div class="col-sm-9">
         <input type="text" name="Admin[admin_name]" class="form-control" value="<?= $admin->admin_name?>" />
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">手机号：</label>
    <div class="col-sm-9">
        <input type="text" name="Admin[tel]" class="form-control" value="<?= $admin->tel?>" />
      		<span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 可以使用手机号登录</span>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">部门：</label>
    <div class="col-sm-9" id="depart_list">
        <?= Depart::get_depart_select('Admin[depart_id]',$admin->depart_id) ?>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">角色：</label>
    <div class="col-sm-9" id="role_list">
         <?= Role::get_role_select('Admin[role_id]',$admin->role_id) ?>
    </div>
</div>
  

<div class="form-group">
    <label class="col-sm-2 control-label">登录密码：</label>
    <div class="col-sm-9">
        <input type="text" name="Admin[password]" class="form-control" />
        <?php if(isset($admin->id)){ ?>
        <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 填写密码则为修改密码，不填写不修改</span>
        <?php } ?>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">备注：</label>
    <div class="col-sm-9" id="role_list">
        <input type="hidden" name="id" class="form-control" value="<?= $admin->id?>">
        <input type="text" name="Admin[desc]" class="form-control" value="<?= $admin->desc?>">
    </div>
</div>
</form>
    </div>
</div>
<div class=" " style="padding:10px 50px;">
<?php if(isset($admin->id)){
?>
<button class="btn btn-danger" id="edit_admin"><i class="icon-save"></i>编辑</button>

<?php
}else{
?>
<button class="btn btn-danger" id="add_admin"><i class="icon-save"></i>添加</button>
<?php
}?>
</div>


<script>
<?php if(isset($admin->id)){
?>
$("#edit_admin").click(function(){
    var param = $("#user_form").serialize();
    $.post("<?= Url::to(['manager/edit-admin'])?>",param,function(result){
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
$("#add_admin").click(function(){
    var param = $("#user_form").serialize();
    $.post("<?= Url::to(['manager/add-admin'])?>",param,function(result){
        if(result.error == 1){
            layer.alert(result.message);
            parent.location.reload();
        }else{
            layer.alert(result.message);
        }
    },'json');
});
<?php
}?>


//得到部门下 角色列表
$("#depart_list > select").change(function(){
	var depart_id = $(this).val();
	$.get("<?= Url::to(['/manager/get-role-list'])?>",{depart_id:depart_id},function(result){
		if(result.error == 1){
			$("#role_list").html(result.content.role_list_select);
            if (result.content.depart_type == 5) {
                $("#user_custom").show();
            };
            if (result.content.depart_type == 6) {
                $("#user_supplier").show();
            };            
		}else{
			$("#role_list").html('');
			layer.msg(result.message);
		}
	},'json')
});


</script>
 