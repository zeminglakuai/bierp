<?php

use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use app\modules\admin\config\config;
use app\common\models\Depart;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = '管理员日志';
$this->params['breadcrumbs'][] = ['label'=>'管理员列表','url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ibox">
  <div class="ibox-content">
    <form action="<?= Url::to(['/manager/index'])?>" method="get">
     <div class="row">
        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">用户名</label>
            <div class="col-sm-9">
              <input type="text" name="admin_name"  class="form-control" value="<?= $admin_name?>"/>
            </div>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">手机号</label>
            <div class="col-sm-9">
              <input type="text" name="tel"  class="form-control" value="<?= $admin_name?>"/>
            </div>
          </div>
        </div>        
        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">部门 </label>
            <div class="col-sm-9">
              <?= Depart::get_depart_select('depart_id',$depart_id) ?>
            </div>
          </div>
        </div>        
        <div class="col-sm-3">
          <input type="submit" class="btn btn-danger btn-sm tooltip-error" value="搜索"/><input type="hidden" name="r" value="admin/lock/index"/>
        </div>        
    </div>
    </form>
  </div>
</div>
<div class="ibox">
  <div class="ibox-content">
<table class="table table-hover">
<thead>
  <tr>
    <th width="8%">管理员ID</th>
    <th width="10%">管理员帐号</th>
    <th width="10%">模型</th>
	<th width="8%">操作</th>
	<th width="10%">时间</th>
	<th>参数</th>
  </tr>
  </thead>
  <tbody>
  <?php if($log_list){?>
  
  <?php foreach($log_list as $kk => $vv){?>
  <tr>
    <td><?= $vv['id']?></td>
    <td><?= $vv['admin_name']?></td>
	<td><?= $vv['model']?></td>
    <td><?= $vv['action']?></td>
	<td><?= date('Y-m-d H:i:s',$vv['add_time'])?></td>
	<td><?php foreach($vv['param'] as $kk => $vv){
	if(is_array($vv)){
		foreach($vv as $kkk => $vvv){
			echo $kkk.'=>'.$vvv.' ';
		}
	}else{
		echo $kk.'=>'.$vv.' ';}
	}
	 
	 
	 ?></td>
  </tr>
  <?php } }else{?>
  	<tr>
    <td colspan="7" align="center"> 当前条件下没有数据</td>
  </tr>
  <?php }?>
  </tbody>
</table>
  </div>
</div>
<?php
echo LinkPager::widget([
    'pagination' => $pages,
]);
?>

