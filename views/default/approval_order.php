<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\common\config\sys_config;
?>

<div class="ibox">
  <div class="ibox-title">
     <?= $nav_name?> 
    <div class="ibox-tools">
        <a class="collapse-link">
            <i class="fa fa-chevron-up"></i>
        </a>
        <a class="close-link">
            <i class="fa fa-refresh"></i>
        </a>
    </div>
  </div>
  <div class="ibox-content" style="padding-bottom:10px;">
     <?php
      foreach ($order_status as $key => $value) {
    ?>
      <?= $value['process_name']?>(<?= $value['count']?>)<br>

     <?php
      }
     ?>
  </div>
</div>

<script type="text/javascript">
  
</script>