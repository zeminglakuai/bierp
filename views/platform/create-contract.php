<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\common\config\lang_config;
use app\common\config\lang_value_config;


?> 
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>

<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>
<?= Html::cssFile('@web/css/plugins/iCheck/custom.css') ?>

<?= Html::jsFile('@web/js/plugins/iCheck/icheck.min.js') ?>

<script src="/js/bootstrap-datetimepicker.min.js"></script>
                            <script src="/js/bootstrap-datetimepicker.zh-CN.js"></script>
                            <link href="/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<div class="ibox">
  <div class="ibox-content">
    <form method="post" class="form-horizontal" id="data_form" enctype="multipart/form-data" action="platform/insert-contract">
    <div class="form-group">
        <label class="col-sm-2 control-label"> 合同名称</label>
        <div class="col-sm-9">
            <div class="input-group date " data-link-field="dtp_input2" >
                <input class="form-control" name="contract_name" size="16" value="<?=$contract->contract_name?>" type="text" placeholder="合同名称">
                <input  name="id" size="16" value="<?=$contract->id?>" type="hidden" >
            </div>
        </div>
    </div>
    <div class="form-group">
                                <label class="col-sm-2 control-label"> 合同开始时间</label>
                                <div class="col-sm-9">
                                    <div class="input-group date form_date15" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" name="start_time" size="16" value="<?=$contract->start_time?>" type="text" readonly="" placeholder="开始时间">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
							<script>
                                $('.form_date15').datetimepicker({
                                    language: 'zh-CN',
                                    weekStart: 1,
                                    todayBtn: 1,
                                    autoclose: 1,
                                    todayHighlight: 1,
                                    startView: 2,
                                    minView: 2,
                                    forceParse: 0
                                });
							</script>
							
                            <div class="form-group">
                                <label class="col-sm-2 control-label">合同结束时间</label>
                                <div class="col-sm-9">
                                    <div class="input-group date form_date16" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" name="end_time" size="16" value="<?=$contract->end_time?>" type="text" readonly="" placeholder="结束日期">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <script>
                                $('.form_date16').datetimepicker({
                                    language: 'zh-CN',
                                    weekStart: 1,
                                    todayBtn: 1,
                                    autoclose: 1,
                                    todayHighlight: 1,
                                    startView: 2,
                                    minView: 2,
                                    forceParse: 0
                                });
							</script>
    <?= app\common\widgets\FileList::widget(['model' => 'contract', 'file_list' => $contract->contractFile]); ?>
   
    </form>
  </div>
</div>

<?= app\common\widgets\Submit::widget(['model'=>$platform,'model_name'=>"platform_contact",'form_name'=>'data_form','defined_function'=>true]); ?>
<script type="text/javascript">

$("#add_platform_contact").click(function(){
    var index = parent.layer.getFrameIndex(window.name);
    var formData = new FormData($( "#data_form" )[0]);
    console.log(formData);
     $.ajax({  
          url: '<?= Url::to(["platform/insert-contract","id"=>$id])?>' ,  
          type: 'POST',  
          data: formData,
          dataType:'json',
          async: false,  
          cache: false,  
          contentType: false,  
          processData: false,  
          
          success: function (result) {  
            if(result.error == 1){
            //    parent.location.href= '<?= Url::to(["platform/edit","id"=>$id,"present_panel"=>"contact"])?>';
            parent.layer.close(index);
            }else{
               
              layer.msg(result.message);
            } 
          },
          error: function (result) {  
              layer.msg('发生错误');
          }
     });
});

$("#edit_platform_contact").click(function(){
    var index = parent.layer.getFrameIndex(window.name);
    var formData = new FormData($( "#data_form" )[0]);
     $.ajax({  
          url: '<?= Url::to(["platform/update-platform","id"=>$id,"platform_id"=>$platform->id])?>',
          type: 'POST',  
          data: formData,
          dataType:'json',
          async: false,  
          cache: false,  
          contentType: false,  
          processData: false,  
          success: function (result) {  
            if(result.error == 1){
            //    parent.location.href= '<?= Url::to(["platform/edit","id"=>$id,"present_panel"=>"platform"])?>';
            parent.layer.close(index);
            }else{
              layer.msg(result.message);
            } 
          },
          error: function (result) {  
              layer.msg('发生错误');
          }
     });
});
$(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});

</script>