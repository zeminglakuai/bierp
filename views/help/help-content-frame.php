<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

?>
<div class="ibox float-e-margins">
  <div class="ibox-title">
    <h5>帮助文档</h5>
  </div>
  <div class="ibox-content">
    <iframe src="<?= Url::to(['/help/content','module'=>$module])?>" style="width:100%;height:580px;border:none;"></iframe>
  </div>
</div>