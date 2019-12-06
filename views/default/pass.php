<?php

use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\modules\admin\config\config;

?>
<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
    <form  class="form-horizontal" name="change_pass_form" id="change_pass_form" method="post">
     <div class="form-group">
        <label class="col-sm-2 control-label">账户名称</label>
        <div class="col-sm-9">
          <?= $admin_user->admin_name?>
        </div>
      </div>
      <div class="form-group">
          <label class="col-sm-2 control-label">新密码</label>
          <div class="col-sm-9">
            <input type="text" name="password" class="form-control" />
          </div>
      </div>
      <div class="form-group">
          <label class="col-sm-2 control-label">确认密码</label>
          <div class="col-sm-9">
            <input type="text" name="c_password" class="form-control"/>
          </div>
      </div>
    </form>
  </div>
  <div class="row" style="padding:1em;">
    <div class="form-group">
          <label class="col-sm-2 control-label"></label>
          <div class="col-sm-9">
            <button type="submit" id="change_pass" class="btn btn-danger">提交</button>
          </div>
      </div>
  </div>
</div>

<script type="text/javascript">
  var index = parent.layer.getFrameIndex(window.name);
  $("#change_pass").click(function(){
    var param = $("#change_pass_form").serialize();
    $.post("<?= Url::to(['default/update-pass'])?>",param,function(result){
        if(result.error == 1){
            parent.layer.msg(result.message);
            parent.layer.close(index);
        }else{
            layer.msg(result.message);
        }
    },'json');
  });
</script>
