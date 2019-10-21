<?php

use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\modules\admin\config\config;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = $admin_user->admin_name.'修改密码';
$this->params['breadcrumbs'][] = ['label' => '管理员列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<form action="<?= Url::to(['/admin/lock/update-pass','id'=>$admin_user->id]);?>" name="" method="post">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
    <tr>
      <th width="10%">账户名称</th>
      <td><?= $admin_user->admin_name?></td>
    </tr>
    <tr>
      <th>新密码：</th>
      <td><input type="text" name="password" /></td>
    </tr>
    <tr>
      <th>确认密码:</th>
      <td><input type="text" name="c_password" /></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="submit" class="btn btn-danger btn-sm tooltip-error" value="修改密码"/></td>
    </tr>
  </table>
</form>
