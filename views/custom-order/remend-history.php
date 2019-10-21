<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

?>
<div style="background-color:#fff;">
<table class="table">
<thead>
  <tr>
    <th>商品名称</th>
    <th>型号</th>
    <th>修改项</th>
    <th>原值</th>
    <th>新值</th>
    <th>时间</th>
    <th>修改人</th>
  </tr>
</thead>

<tbody>
  <?php foreach ($order_log as $key => $value) {
  ?>
  <tr>
    <td><?= $value->goods_name ?></td>
    <td><?= $value->goods_sn ?></td>
    <td><?= $value->lable_name ?></td>
    <td><?= $value->origin_value ?></td>
    <td><?= $value->new_value ?></td>
    <td><?= date('Y-m-d H:i',$value->add_time) ?></td>
    <td><?= $value->add_user_name ?></td>
  </tr>
  <?php
  }?>
</tbody>
</table>

</div>
