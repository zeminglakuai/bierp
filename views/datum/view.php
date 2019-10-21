<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\config\sys_config;
use app\common\models\Depart;

?>

<?= Html::jsFile('@web/js/bootstrap.min.js') ?>

<?= Html::jsFile('@web/ueditor/ueditor.config.js') ?>
<?= Html::jsFile('@web/ueditor/ueditor.all.min.js') ?>
<?= Html::jsFile('@web/ueditor/lang/zh-cn/zh-cn.js') ?>

<?= Html::jsFile('@web/js/select2.min.js') ?>
<?= Html::cssFile('@web/css/select2.min.css') ?>

<div class="tabs-container">
  <form id="data_form" method="get" class="form-horizontal">
  <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 基本信息</a></li>
      <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false">合同内容</a></li>
      <li class=""><a data-toggle="tab" href="#tab-3" aria-expanded="false">附件</a></li>
  </ul>
  <div class="tab-content">
      <div id="tab-1" class="tab-pane active">
          <div class="panel-body">
            <?= app\common\widgets\Input::widget(['label_name'=>'合同名称','name'=>"Datum[datum_name]",'value'=>$datum->datum_name,'tips'=>'']); ?>
<!--
              <div class="form-group">
                <label class="col-sm-2 control-label">分类:</label>
                <div class="col-sm-9">
                  <Select  name="Datum[cat_id]" class="form-control" >
                    <option value="0">分类</option>
                    <?= $cat_list?>
                  </Select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">权限范围:</label>
                <div class="col-sm-9">
                  <?php $datum_arr = explode(',', $datum->scope);?>
                  <?= Depart::get_depart_select('scope[]',$datum_arr);?>
                </div>
            </div>
            <script type="text/javascript">
              $("#depart_select_list").select2();
            </script>
-->
          </div>

      </div>
      <div id="tab-2" class="tab-pane">
          <div class="panel-body">
            <script type="text/plain" id="editor" name="Datum[content]" style="height:300px;"><?= $datum->content?></script> 
            <script type="text/javascript">var editor = UE.getEditor('editor');</script> 
          </div>
      </div>
      <div id="tab-3" class="tab-pane">
          <div class="panel-body">
            <?= app\common\widgets\FileList::widget(['model'=>'datum','file_list'=>$datum->datumFile]); ?>
             
          </div>
      </div>
  </div>
  </form>
  </div>
</div>
<?= app\common\widgets\Submit::widget(['model'=>$datum,'model_name'=>"datum",'form_name'=>'data_form']); ?>
 