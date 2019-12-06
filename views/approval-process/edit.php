<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\common\config\sys_config;

?>
<?= Html::cssFile('@web/css/plugins/iCheck/custom.css') ?>
<?= Html::jsFile('@web/js/plugins/iCheck/icheck.min.js') ?>
<?= app\common\widgets\OrderForm::widget(['form_data'=>[['type'=>'text','label_name'=>'复核按钮名称','name'=>'process_name','value'=>$process_data_arr['process_name'],'tips','id','init_value','inneed'=>true],
                                                        ['type'=>'select','label_name'=>'复核部门类型','name'=>'scope_depart_type','value'=>$process_data_arr['scope_depart_type'],'inneed'=>true,'tips'=>'进行此复核操作，需要哪个部门，默认请选择当前部门','id','init_value'=>sys_config::$approval_depart_type,],
                                                        ['type'=>'text','label_name'=>'复核过名称','name'=>'processed_name','value'=>$process_data_arr['processed_name'],'inneed'=>true,'tips',],
                                                        ['type'=>'text','label_name'=>'提示','name'=>'tips','value'=>$process_data_arr['tips'],'tips','id','init_value',],
                                                        ['type'=>'radio','label_name'=>'允许修改单据','name'=>'allow_amend_order','value'=>$process_data_arr['allow_amend_order'],'tips','id','init_value'=>[['value'=>1,'label_name'=>'是'],['value'=>0,'label_name'=>'否']],],
                                                        ['type'=>'hidden','label_name'=>'label_name','name'=>'module','value'=>$module,'tips','id','init_value',],
                                                      ]
                                        ]);
?>

 
<div class="row" style="padding:10px 0px;margin-bottom:5em;">
  <div class="form-group">
      <label class="col-sm-2 control-label"></label>
      <div class="col-sm-10">
        <?php if(isset($process_data_arr) && $process_data_arr){
        ?>
        <input type="hidden" name="id" value="<?= $id?>" >
        <button class="btn btn-danger" id="edit_approval-process"><i class="icon-save"></i>保存</button>
        <?php
        }else{
        ?>
        <button class="btn btn-danger" id="add_approval-process"><i class="icon-save"></i>添加</button>
        <?php
        }?>
      </div>
  </div>
</div>

<script>
  var index = parent.layer.getFrameIndex(window.name);
  <?php if(isset($process_data_arr) && $process_data_arr){
  ?>
  $("#edit_approval-process").click(function(){

     var formData = new FormData($("#order_form")[0]);
     var update_url = create_url('<?= $url?$url:Url::to(["/approval-process/update"])?>');
       $.ajax({  
            url: update_url+'id=<?= $id?>',
            type: 'POST',  
            data: formData,
            dataType:'json',
            async: false,  
            cache: false,  
            contentType: false,
            processData: false,
            success: function (result) {
              if(result.error == 1){
                var module = '<?= $module?>';
                parent.layer.msg(result.message);
                
                //重载模板列表
                var parent_frame = parent.$("#template_list");

                parent.$.get('<?= Url::to(['/approval-process/process-list'])?>',{module:module},function(result){
                  parent_frame.html(result.content);
                },'json');

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
  <?php
  }else{
  ?>
  $("#add_approval-process").click(function(){
      var formData = new FormData($( "#order_form" )[0]);
       $.ajax({  
            url: '<?= $url?$url:Url::to(["/approval-process/insert"])?>' ,  
            type: 'POST',  
            data: formData,
            dataType:'json',
            async: false,  
            cache: false,  
            contentType: false,  
            processData: false,  
            success: function (result) {  
              if(result.error == 1){
                var module = '<?= $module?>';
                parent.layer.msg(result.message);
                

                //重载模板列表
                var parent_frame = parent.$("#template_list");
                parent.$.get('<?= Url::to(['/approval-process/process-list'])?>',{module:module},function(result){
                  parent_frame.html(result.content);
                },'json');

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
  <?php
  }?>
 
  $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
 
</script>
 