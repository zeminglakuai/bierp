<?php

use yii\helpers\Html;
use yii\helpers\Url;

use app\common\config\sys_config;
?>

<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
    <form id="custom_form" method="post" class="form-horizontal" enctype="multipart/form-data">
      <div class="form-group">
        <label class="col-sm-2 control-label">用户名</label>
        <div class="col-sm-9">
          <?= $admin->admin_name?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">电话</label>
        <div class="col-sm-9">
          <?= $admin->tel?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">真实姓名</label>
        <div class="col-sm-9">
          <?= $admin->real_name?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">所在部门</label>
        <div class="col-sm-9">
          <?= $admin->depart->depart_name?>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label">职位</label>
        <div class="col-sm-9">
          <?= $admin->role->role_name?>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label">角色类型</label>
        <div class="col-sm-9">
          <?= sys_config::$role_type[$admin->role->role_type]?>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label">最后登录时间</label>
        <div class="col-sm-9">
          <?= $admin->last_login?>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label">最后登录IP</label>
        <div class="col-sm-9">
          <?= $admin->last_ip?>
        </div>
      </div>

    </form>
  </div>
</div>
 
 
