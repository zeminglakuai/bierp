<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\common\config\sys_config;
 
$this->params['breadcrumbs'][] = '仓库列表';
?>
<div class="ibox">
  <div class="ibox-content">
<table class="table">
  <thead>
  <tr>
    <th width="5%">ID</th>
    <th width="20%" align="center">仓库名称</th>
    <th width="15%" align="center">地址</th>
    <th width="12%" align="center">联系人</th>
    <th width="12%" align="center">手机</th>
    <th width="22%" align="center">备注</th>
	<th align="center">操作</th>
  </tr>
</thead>
<tbody>
  <?php foreach($store_list as $kk => $vv){?>
  <tr>
    <td><?= $vv['id']?></td>
    <td><span class="store_edit" data-type="store_name" data-id="<?= $vv['id']?>"><?= $vv['store_name'] ?></span> <span class="glyphicon glyphicon-cog" style="display: none;"> </span> </td>
    <td><span class="store_edit" data-type="address" data-id="<?= $vv['id']?>"><?= $vv['address'] ?></span> <span class="glyphicon glyphicon-cog" style="display: none;"> </span> </td>
    <td><span class="store_edit" data-type="contact" data-id="<?= $vv['id']?>"><?= empty($vv['contact'])?'&nbsp;&nbsp;&nbsp;&nbsp;':$vv['contact'] ?>  </span> <span class="glyphicon glyphicon-cog" style="display: none;"> </span> </td>
    <td><span class="supplier_edit" data-type="tel" data-id="<?= $vv['id']?>"><?= empty($vv['tel'])?'&nbsp;&nbsp;&nbsp;&nbsp;':$vv['tel'] ?>  </span> <span class="glyphicon glyphicon-cog" style="display: none;"> </span> </td>
    <td><span class="store_edit" data-type="desc" data-id="<?= $vv['id']?>"><?= $vv['desc'] ?></span> <span class="glyphicon glyphicon-cog" style="display: none;"> </span> </td>
    <td>
    <button class="btn btn-xs btn-primary edit_store" data-id="<?= $vv['id']?>"><span class="glyphicon glyphicon-edit "></span> 编辑</A></button> 
    <A  class="btn btn-xs btn-danger" href="<?= Url::to(['/store/delete','id'=>$vv['id']])?>"><span class="fa fa-times"></span> 删除</A></td>
  </tr>
  <?php }?>
</tbody>
</table>
</div>
</div>
 
<div class="oprate_bar">
  <div class="row">
    <div class="col-sm-1"><button type="button" id="add_store" class="btn btn-warning btn-sm"><i class="fa fa-plus"></i> 新建仓库</button></div>
    <div class="col-sm-10"></div>
    <div class="col-sm-1"><button type="button" id="refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i> 刷新</button></div>
  </div>
</div>
<script>
$("#add_store").click(function(){
  //页面层
  layer.open({
    type: 2,
    title:'添加仓库',
    //skin: 'layui-layer-rim', //加上边框
    area: ['720px', '500px'], //宽高
    maxmin: true,
    content: '<?= Url::to(["/store/create"])?>'
  });
});

$(".edit_store").click(function(){
  var id = $(this).attr('data-id');
  //页面层
  layer.open({
    type: 2,
    title:'编辑用户',
    //skin: 'layui-layer-rim', //加上边框
    area: ['50%', '70%'], //宽高
    maxmin: true,
    content: '<?= Url::to(["/store/view"])?>?id='+id
  });
});
 

</script>
