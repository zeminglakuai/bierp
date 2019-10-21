<?php

print_r($pages);


?>

<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
	<table class="table dataTable">
		<thead>
			<tr>
			<th width="5%" align="center"class="<?php if($sortby == 'id'){if($order==4){echo 'sorting_asc';}else{echo 'sorting_desc';}}else{echo 'sorting';}?> user_sort" data-type="id">ID</th>
			<th width="25%" align="center">供货商名称</th>
			<th width="10%" align="center">简称</th>
			<th width="10%" align="center">联系人</th>
			<th width="13%" align="center">手机</th>
			<th width="13%" align="center">电话</th>
			<th width="13%" align="center">一件代发</th>
			<th align="center">操作</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($supp_list as $kk => $vv){?>
			<tr>
			<td><?= $vv['id']?></td>
			<td><span class="supplier_edit" data-type="supplier_name" data-id="<?= $vv['id']?>"><?= $vv['supplier_name'] ?></span> </span> </td>
			<td><span class="supplier_edit" data-type="simple_name" data-id="<?= $vv['id']?>"><?= $vv['simple_name'] ?>  </span></span> </td>
			<td><span class="supplier_edit" data-type="contact" data-id="<?= $vv['id']?>"><?= empty($vv['contact'])?'&nbsp;&nbsp;&nbsp;&nbsp;':$vv['contact'] ?>  </span></td>
			<td><span class="supplier_edit" data-type="tel" data-id="<?= $vv['id']?>"><?= empty($vv['tel'])?'&nbsp;&nbsp;&nbsp;&nbsp;':$vv['tel'] ?>  </span></td>
			<td><span class="supplier_edit" data-type="guhua" data-id="<?= $vv['id']?>"><?= empty($vv['guhua'])?'&nbsp;&nbsp;&nbsp;&nbsp;':$vv['guhua'] ?></span></td>
			<td><?= sys_config::$YON[$vv['daifa']]?> </td>
			<td>
			<A href="<?= Url::to(['/supplier/view-supplier','id'=>$vv['id']])?>"><span class="glyphicon glyphicon-edit"></span> 查看</A>
			<A href="<?= Url::to(['/supplier/delete-supplier','id'=>$vv['id']])?>"><span class="glyphicon glyphicon-rabbish"></span> 删除</A></td>
			</tr>
		<?php }?>
		</tbody>
	</table>
  </div>
</div>
<div class="row">
<div class="col-sm-6"></div>
<div class="col-sm-5" align="right">
  <?php
  // echo LinkPager::widget([
  //     'pagination' => $pages,
  // ]);
  ?>
</div>
</div>