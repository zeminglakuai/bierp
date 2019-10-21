<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\Depart;
use app\common\models\SellOrder;
use app\common\models\Role;
use app\common\config\sys_config;
?>
 
<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
<form id="order_form" class="form-horizontal m-t">
<div class="form-group">
    <label class="col-sm-2 control-label">供货商编辑URl</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" value="<?= Yii::$app->request->hostInfo?>:<?= Yii::$app->request->port?>/supplier-price/?id=<?= $ask_price_order->id ?>">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">供货商编辑密码</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" value="<?= $ask_price_order->access_secrect?>">
        
    </div>
</div>
 
</form>
  </div>
</div>
 
 