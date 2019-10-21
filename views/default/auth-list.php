<?php

use yii\helpers\Html;
use yii\helpers\Url;

use app\common\config\sys_config;

?>
<body class="gray-bg">
<div class="middle-box loginscreen" style="width:100%;max-width:800px;">
  <div class="ibox">
  <div class="ibox-title">
    <h5>选择登录账号</h5>
    <div class="ibox-tools">
      <a href="<?= Url::to(['default/select-auth'])?>" class="btn btn-xs btn-primary"><i class="fa fa-files-o"></i> 使用当前账户登录</a>
    </div>
  </div>
  <div class="ibox-content" style="padding-bottom:10px;">
    <table class="table">
      <thead><tr><th>授权人</th><th>有效时间</th><th>操作时间</th><th>操作</th></tr></thead>
      <tbody>
        <?php if(count($auth_list) > 0){
          foreach ($auth_list as $key => $value) {
        ?>
          <tr>
            <td><?= $value->fromUser->admin_name?></td>
            <td><?= date('Y-m-d H:i:s',$value->expire)?></td>
            <td><?= date('Y-m-d H:i:s',$value->add_time) ?></td>
            <td><a href="<?= Url::to(['default/select-auth','id'=>$value->id])?>" class="btn btn-samll">使用该账号登录</a></td>
          </tr>
        <?php
          }
        }else{
        ?>
          <tr colspan="3"></tr>
        <?php
        }?>
      </tbody>
    </table>
  </div>
</div>
</div>
</body>