<?php
use yii\helpers\Html;
?>

<div class="i-box">
  <div class="ibox-content">
    <div class="row">
      <div class="col-sm-3"></div>
      <div class="col-sm-6">
        <div class="panel <?php if($type == 'notic'){?> panel-primary <?php }else{?> panel-danger<?php }?>">
            <div class="panel-heading">
              系统提示
            </div>
            <div class="panel-body">
                <p class="success"><?php if($type == 'notic'){?> <i class=""></i> <?php }else{?> <i class="fa fa-warning"></i><?php }?><?= $message?></p>
            </div>
            <div class="panel-footer">
              <?php foreach($links as $kk => $vv){?>
              <a href="<?= $vv['link_url']?>">
              <?= $vv['link_name']?>
              </a>
              <?php }?>
            </div>
        </div>
        </div>
    </div>
  </div>
</div> 