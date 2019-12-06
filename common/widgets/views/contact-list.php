<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\common\models\admin; 
?>
<table class="table">
<thead>
	<tr>
		<th>姓名</th>
		<th>手机号</th>
		<?php foreach ($filed_arr as $key => $value) {
		?>
			<th><?= $value->dictionary_value?></th>
		<?php
		}?>
		<th>操作</th>
	</tr>
</thead>
<tbody>
	<?php foreach ($contact_list as $key => $value) {
	?>
	<tr>
		<td><span class="<?= $value->is_active?'':'unactive'?>"><?= $value->name?></span></td>
		<td><span class="<?= $value->is_active?'':'unactive'?>"><?= $value->tel?></span></td>
		<?php
			foreach ($value->extendInfo as $kk => $vv) {
				$extend_info[$vv->filed_id] = $vv->filed_value;
			}

		 	foreach ($filed_arr as $key => $vv) {
		?>
			<td><?= $extend_info[$vv->id]?></td>
		<?php
		}?>
		<td>
			<A  class="btn btn-xs btn-primary edit_contact" href="javascript:void();" origin-id="<?= $main_body->id?>" data-id="<?= $value->id?>"><span class="glyphicon glyphicon-edit"></span> 编辑</A>
            <A  class="btn btn-xs btn-danger delete_contact" href="javascript:void();" origin-id="<?= $main_body->id?>" data-id="<?= $value->id?>"><span class="glyphicon glyphicon-trash"></span> 删除</A>
		</td>
	</tr>
	<?php
	}?>
</tbody>
</table>