<?php

use yii\helpers\Html;
use yii\helpers\Url;

use app\common\config\sys_config;

?>

<?= Html::jsFile('@web/js/bootstrap-datetimepicker.min.js') ?>
<?= Html::jsFile('@web/js/bootstrap-datetimepicker.zh-CN.js') ?>
<?= Html::cssFile('@web/css/bootstrap-datetimepicker.min.css') ?>

<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
    <form  class="form-horizontal" name="auth_form" id="auth_form" method="post">
     <div class="form-group">
        <label class="col-sm-2 control-label">授权用户</label>
        <div class="col-sm-9">
          <input type="text" name="user_name" class="form-control" />
        </div>
      </div>
      <div class="form-group">
          <label class="col-sm-2 control-label">授权结束时间</label>
          <div class="col-sm-9">
            <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd hh:ii:00" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd HH:ii:ss">
              <input class="form-control" name="end_time" size="16" type="text"  readonly="" placeholder="授权结束时间">
              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>
      </div>
    </form>
  </div>
  <div class="row" style="padding:1em;">
    <div class="form-group">
          <label class="col-sm-2 control-label"></label>
          <div class="col-sm-9">
            <button type="submit" id="user_auth" class="btn btn-danger">提交</button>
          </div>
      </div>
  </div>
</div>
<div class="ibox">
  <div class="ibox-title">授权记录</div>
  <div class="ibox-content" style="padding-bottom:10px;">
    <table class="table">
      <thead><tr><th>授权给</th><th>有效时间</th><th>操作时间</th></tr></thead>
      <tbody>
        <?php if($auth_log){
          foreach ($auth_log as $key => $value) {
        ?>
          <tr><td><?= $value->toUser->admin_name?></td><td><?= date('Y-m-d H:i:s',$value->expire)?></td><td><?= date('Y-m-d H:i:s',$value->add_time) ?></td></tr>
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


    <script>
    $('.form_date').datetimepicker({
        language:  'zh-CN',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 0,
        forceParse: 0
      });
    </script>
<script type="text/javascript">
  var index = parent.layer.getFrameIndex(window.name);
  $("#user_auth").click(function(){
    var param = $("#auth_form").serialize();
    console.log(param);
    $.post("<?= Url::to(['default/act-user-auth'])?>",param,function(result){
        if(result.error == 1){
            parent.layer.msg(result.message);
            parent.layer.close(index);
        }else{
            layer.msg(result.message);
        }
    },'json');
  });
</script>
