<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?= Html::jsFile('@web/js/uploadPreview.js') ?>

<?= app\common\widgets\OrderForm::widget(['form_data'=>[
        ['type'=>'text','label_name'=>'PPT模板名称','name'=>'ExportPptTable[template_name]','value'=>$export_ppt_table->template_name,'tips','init_value',],
        ['type'=>'text','label_name'=>'PPT模板标题','name'=>'ExportPptTable[page_title]','value'=>$export_ppt_table->page_title,'tips','id'=>'',],
        ['type'=>'image','label_name'=>'PPT模板封面','name'=>'ExportPptTable[page_face]','value'=>$export_ppt_table->page_face,'tips','id'=>'page_face','init_value',],
        ['type'=>'image','label_name'=>'PPT模板封底','name'=>'ExportPptTable[page_back]','value'=>$export_ppt_table->page_back,'tips','id'=>'page_back','init_value',],
        ['type'=>'hidden','label_name'=>'PPT模板封底','name'=>'ExportPptTable[module_name]','value'=>$module?$module:($export_ppt_table->module_name?$export_ppt_table->module_name:''),'tips','id'=>'page_back','init_value',],
      ]
]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$export_ppt_table,'model_name'=>"export-ppt-template",'form_name'=>'order_form','defined_function'=>true]); ?>

<script>

  var index = parent.layer.getFrameIndex(window.name);
  $("#add_export-ppt-template").click(function(){
    var formData = new FormData($( "#order_form" )[0]);
     $.ajax({
          url: '<?= $url?$url:Url::to(["/export-ppt-template/insert"])?>' ,  
          type: 'POST',  
          data: formData,
          dataType:'json',
          async: true,  
          cache: false,  
          contentType: false,  
          processData: false,  
          success: function (result) {
            if(result.error == 1){
              var module = '<?= $module?>';
              parent.layer.msg(result.message);
              //重载模板列表
              var parent_frame = parent.$("#template_list");

              parent.$.get('<?= Url::to(['/export-ppt-template/template-list'])?>',{module:module},function(result){
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
  })

  $("#edit_export-ppt-template").click(function(){
    var formData = new FormData($( "#order_form" )[0]);
     $.ajax({
          url: '<?= $url?$url:Url::to(["/export-ppt-template/update","id"=>$export_ppt_table->id])?>' ,  
          type: 'POST',  
          data: formData,
          dataType:'json',
          async: true,  
          cache: false,  
          contentType: false,  
          processData: false,  
          success: function (result) {  
            if(result.error == 1){
              var module = '<?= $export_ppt_table->module_name ?>';
              parent.layer.msg(result.message);
              //重载模板列表
              var parent_frame = parent.$("#template_list");

              parent.$.get('<?= Url::to(['/export-ppt-template/template-list'])?>',{module:module},function(result){
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
  })


  $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
</script>