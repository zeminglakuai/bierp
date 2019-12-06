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

<div class="row">
  <div class="col-sm-12">
    <div class="tabs-container">
      <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 基本信息</a>
        </li>
      </ul>
      <form method="post" class="form-horizontal" id="data_form" enctype="multipart/form-data">
      <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
              <?= app\common\widgets\Input::widget(['label_name'=>'收货人姓名','name'=>"Consignee[consignee]",'value'=>$consignee->consignee,'tips'=>'']); ?>
              <?= app\common\widgets\Input::widget(['label_name'=>'收货地址','name'=>"Consignee[address]",'value'=>$consignee->address,'tips'=>'']); ?>
              <?= app\common\widgets\Input::widget(['label_name'=>'手机号','name'=>"Consignee[tel]",'value'=>$consignee->tel,'tips'=>'']); ?>
              <?= app\common\widgets\Input::widget(['label_name'=>'备注','name'=>"Consignee[remark]",'value'=>$consignee->remark,'tips'=>'']); ?>
            </div>
        </div>
      </div>
    </form>
    </div>
  </div>
</div>

<?= app\common\widgets\Submit::widget(['model'=>$consignee,'model_name'=>"consignee",'form_name'=>'data_form','url'=>$consignee?Url::to(['supplier/update-consignee']):Url::to(['supplier/insert-consignee']),'defined_function'=>true]); ?>

<script type="text/javascript">
$("#add_consignee").click(function(){
    var formData = new FormData($( "#data_form" )[0]);
     $.ajax({  
          url: '<?= Url::to(["custom/insert-consignee","id"=>$id])?>' ,  
          type: 'POST',  
          data: formData,
          dataType:'json',
          async: false,  
          cache: false,  
          contentType: false,  
          processData: false,  
          success: function (result) {  
            if(result.error == 1){
               parent.location.href= '<?= Url::to(["custom/edit","id"=>$id,"present_panel"=>"consignee"])?>';
            }else{
              layer.msg(result.message);
            } 
          },
          error: function (result) {  
              layer.msg('发生错误');
          }
     });
});

$("#edit_consignee").click(function(){
    var formData = new FormData($( "#data_form" )[0]);
     $.ajax({  
          url: '<?= Url::to(["custom/update-consignee","id"=>$id,"consignee_id"=>$consignee->id])?>',
          type: 'POST',  
          data: formData,
          dataType:'json',
          async: false,  
          cache: false,  
          contentType: false,  
          processData: false,  
          success: function (result) {  
            if(result.error == 1){
               parent.location.href= '<?= Url::to(["custom/edit","id"=>$id,"present_panel"=>"consignee"])?>';
            }else{
              layer.msg(result.message);
            } 
          },
          error: function (result) {  
              layer.msg('发生错误');
          }
     });
});



</script>


