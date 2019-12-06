<?php

use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\modules\admin\config\config;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = $admin_user->admin_name.'权限编辑';
$this->params['breadcrumbs'][] = ['label' => '管理员列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<form action="<?= Url::to(['/admin/lock/act-privilege']);?>" name="" method="post">
  <table  class="table table-striped table-bordered table-hover">
    <?php foreach(config::$privilege_desc as $kk => $vv){
?>
    <tr>
      <th width="10%"><?= $nav_list[$kk]['name']?></th>
      <td><div class="row">
          <?php foreach($vv as $kkk => $vvv){
		?>
          <div class="col-xs-1">
		  	<label for="<?= $kk.'-'.$kkk?>">
			<Div class="">
            <input type="checkbox" name="<?= $kk.'['.$kkk.']'?>" id="<?= $kk.'-'.$kkk?>" <?php if(isset($admin_user->priv_arr[$kk]) && in_array($kkk,$admin_user->priv_arr[$kk]))echo 'checked = "true"'?>/>
            <?= $vvv['name']?>
			</Div>
			</label>
          </div>
          <?php
		}?>
        </div></td>
    </tr>
    <?php
}?>
  </table>
<div class=" " style="padding:10px 50px;">
		<input type="hidden" name="r" value="admin/lock/act-privilege" />
		<input type="hidden" name="id" value="<?=$admin_user->id?>" />
		<button class="btn btn-danger"><i class="icon-save"></i>保存</button>
</div>
</form>
