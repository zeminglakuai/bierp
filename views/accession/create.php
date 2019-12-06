<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\config\sys_config;

?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>
<?= Html::jsFile('@web/ueditor/ueditor.config.js') ?>
<?= Html::jsFile('@web/ueditor/ueditor.all.min.js') ?>
<?= Html::jsFile('@web/ueditor/lang/zh-cn/zh-cn.js') ?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<form method="post" id="order_form" class="form-horizontal" enctype="multipart/form-data">
  <div class="tabs-container">
    <ul class="nav nav-tabs">
      <li class="active"> <a data-toggle="tab" href="#base_info">基本信息</a></li>
      <li> <a data-toggle="tab" href="#description">详情信息</a></li>
      <li> <a data-toggle="tab" href="#relative_file">相关文件</a></li>
    </ul>
    <div class="tab-content">
      <div id="base_info" class="tab-pane active">
        <div class="panel-body">
          <?= app\common\widgets\Input::widget(['label_name'=>'入职人','name'=>"Accession[user_id]",'value'=>$accession->user_id,'tips'=>'','id'=>'user_id','inneed'=>true]); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'入职时间','name'=>"Accession[accession_time]",'value'=>date('Y-m-d H:i',$accession->accession_time),'tips'=>'']); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'职位','name'=>"Accession[position]",'value'=>$accession->position,'tips'=>'']); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'备注','name'=>"Accession[remark]",'value'=>$accession->remark,'tips'=>'']); ?>
        </div>
      </div>
      <div id="description" class="tab-pane">
        <div class="panel-body">
          <Div class="row">
            <div class="col-xs-12"> 
              <script type="text/plain" id="editor" name="Accession[description]" style="height:300px;"><?= $accession->description?></script> 
            </div>
          </Div>
          <script type="text/javascript">var editor = UE.getEditor('editor');</script> 
        </div>
      </div>
      <div id="relative_file" class="tab-pane">
        <div class="panel-body">
           <?= app\common\widgets\FileList::widget(['model'=>$this->context->id,'file_list'=>$accession->files,'type'=>'contract']); ?>
        </div>
      </div>
    </div>
  </div>
</form>

<script type="text/javascript">
  $(document).ready(function() {
      $("#user_id").tokenInput("/<?=$this->context->id?>/token-user-search",
        {
          theme:'facebook', 
          hintText:'请输入要搜索的关键字',
          tokenLimit:1,
          <?php if($accession->user_id >= 1){ ?>prePopulate:[{id:'<?= $accession->user_id; ?>',name:'<?= $accession->real_name; ?>'}]<?php }?>
        }
      );
  });
</script>

<?= app\common\widgets\Submit::widget(['model'=>$accession,'model_name'=>$this->context->id,'form_name'=>'order_form']); ?>
<script type="text/javascript">