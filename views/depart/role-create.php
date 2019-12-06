<?php

use yii\helpers\Html;
use yii\helpers\Url;

use app\common\config\sys_config;
?>
<div class="ibox">
  <div class="ibox-content">
   <form id="role_form" method="post" class="form-horizontal" id="role_form" >
    <?= app\common\widgets\Input::widget(['label_name'=>'职位名称','name'=>"Role[role_name]",'value'=>$role->role_name,'tips'=>'']); ?>

    <div class="form-group">
        <label class="col-sm-2 control-label">职位类型</label>
        <div class="col-sm-9">
            <?= Html::dropDownList('Role[role_type]', $role->role_type, sys_config::$role_type,['prompt' => '请选择角色类型','class' => 'form-control','id'=>'role_type']) ?>
        </div>
    </div>
    <?= app\common\widgets\Input::widget(['label_name'=>'职位描述','name'=>"Role[role_desc]",'value'=>$role->role_desc,'tips'=>'']); ?>

    <input type="hidden" name="Role[depart_id]" id="depart_id" value="<?= isset($depart_id)?$depart_id:$role->depart_id ?>" />
    <?php if($role->id){
    ?>
      <input type="hidden" name="id" value="<?= $role->id?>" />
    <?php
    }?>
    </form>
 </div>
</div>
<?= app\common\widgets\Submit::widget(['model'=>$role,'model_name'=>"role",'form_name'=>'role_form','url'=>$role->id?Url::to(['/depart/update-role']):Url::to(['/depart/insert-role'])]); ?>
