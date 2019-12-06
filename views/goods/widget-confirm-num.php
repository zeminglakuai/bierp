<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>待审核商品数量 <small></small></h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-refresh"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content">
       <?= $confirm_num ?> 
    </div>
</div>
