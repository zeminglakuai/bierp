<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\common\models\Depart;
use app\common\config\lang_config;
use app\common\config\lang_value_config;

?> 

<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
    <form method="post" class="form-horizontal" id="data_form" enctype="multipart/form-data">
      <?= app\common\widgets\Input::widget(['label_name'=>'计划名称','name'=>"Mession[mession_name]",'value'=>$mession->mession_name,'tips'=>'']); ?>
      <div class="form-group">
          <label class="col-sm-2 control-label">部门</label>
          <div class="col-sm-9">
              <?= Depart::get_depart_select('Mession[mession_depart_id]')?>
          </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">计划年份</label>
        <div class="col-sm-9">
            <?php
              $year_arr = ['2017'=>'2017','2018'=>'2018','2019'=>'2019','2020'=>'2020'];
            ?>
            <?= Html::dropDownList('Mession[year]', $mession->year, $year_arr,['prompt' => '请选择计划年份','class' => 'form-control']) ?>
        </div>
      </div>
      <?= app\common\widgets\Input::widget(['label_name'=>'备注','name'=>"Mession[remark]",'value'=>$mession->remark,'tips'=>'']); ?>
    </form>
  </div>
</div>
<?= app\common\widgets\Submit::widget(['model'=>$mession,'model_name'=>"mession",'form_name'=>'data_form']); ?>
