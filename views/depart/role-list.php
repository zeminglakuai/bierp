<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\common\models\Depart;
use app\common\models\Store;

use app\common\config\sys_config;
?>
<div class="ibox float-e-margins">
  <div class="ibox-title">
     部门信息 
    <div class="ibox-tools">
      <button class="btn btn-xs btn-primary"><i class="fa fa-files-o"></i> 复制部门</button>
      <button class="btn btn-xs btn-danger" id="delete_depart"><i class="fa fa-times"></i> 删除部门</button>
    </div>
  </div>
  <div class="ibox-content form-horizontal">
  <form id="depart_form">
    <div class="form-group">
        <label class="col-sm-2 control-label">部门名称</label>
        <div class="col-sm-10">
              <input type="text" name="Depart[depart_name]" class="input input-sm form-control" value="<?= $depart->depart_name?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">上级部门</label>
        <div class="col-sm-10">
             <?= Depart::get_depart_select('Depart[parent_id]',$depart->parent_id) ?>
        </div>
    </div>
     
    <div class="form-group mgt10">
        <label class="col-sm-2 control-label">部门类型</label>
        <div class="col-sm-10">
              <?= Html::dropDownList('Depart[type]', $depart->type, sys_config::$depart_type,['class' => 'form-control','id'=>'depart_type']) ?> 
        </div>
    </div>
    
    <div class="form-group" <?php if($depart->type <> 3){?> style="display:none;"<?php }?> id="show_store_list">
        <label class="col-sm-2 control-label">所属仓库</label>
        <div class="col-sm-10">
        	<?= Html::dropDownList('Depart[store_id]', $depart->store_id, ArrayHelper::map(Store::find()->asArray()->all(), 'id', 'store_name'),['' => '请选择仓库','class' => 'form-control']) ?>
        </div>
    </div>    

    <div class="form-group">
        <label class="col-sm-2 control-label">部门描述</label>
        <div class="col-sm-10">
        	<input type="hidden" name="id" value="<?= $depart->id?>">
              <input type="text" name="Depart[depart_desc]" class="input input-sm form-control" value="<?= $depart->depart_desc?>">
        </div>
    </div>
  </form>   
    <div class="form-group">
        <label class="col-sm-2 "> </label>
        <div class="col-sm-10">
          <button class="btn btn-primary" id="update_depart">修改</button>
        </div>
    </div>   
  </div>
</div>
<script>
$("#update_depart").click(function(){
	var param = $("#depart_form").serialize();
	$.post("<?= Url::to(['depart/update-depart'])?>",param,function(result){
		if(result.error == 1){
			layer.msg(result.message);
			$.get('<?= Url::to(['/depart/role-list'])?>',{id:<?= $depart->id?>},function(result){
				  $("#depart_oprate").html(result.content)
			},'json');
		}else{
			layer.msg(result.message);
		}
	},'json');
});
 
</script>

<div class="ibox float-e-margins">
  <div class="ibox-title">
     职位列表 
    <div class="ibox-tools">
      <button class="btn btn-xs btn-primary" id="add_role"><i class="fa fa-plus"></i> 添加职位</button>
    </div>
  </div>
  <div class="ibox-content">
      <table class="table">
        <thead>
        <tr>
          <th width="20%">职位名</th>
          <th width="15%">职位类型</th>
          <th width="30%">描述</th>
          <th width="8%">人数</th>
          <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php 
          foreach($role_list as $kk => $vv){
        ?>
          <tr>
          <td><?= $vv->role_name?></td>
          <td><?= sys_config::$role_type[$vv->role_type]?></td>
          <td><?= $vv->role_desc?></td>
          <td><?= $vv->getRoleUserNumber()?></td>
          <td>
            <button class="btn btn-xs btn-primary edit_privi"  role-id="<?= $vv->id?>"><i class="fa fa-edit"> </i> 分配权限</button>
            <button class="btn btn-xs btn-primary edit_role"  role-id="<?= $vv->id?>"><i class="fa fa-edit"> </i> 编辑</button>
            <button class="btn btn-xs btn-danger delete_role"  role-id="<?= $vv->id?>"><i class="fa fa-times"> </i> 删除</button>
          </td>
        </tr>
        <?php
          }
        ?>
        </tbody>
      </table>
  </div>
</div>
<script>
$("#add_role").click(function(){
  //页面层
  layer.open({
    type: 2,
    title:'添加职位',
    //skin: 'layui-layer-rim', //加上边框
    area: ['80%', '50%'], //宽高
    maxmin: true,
    content: "<?= Url::to(['/depart/create-role','id'=>$depart->id])?>"
  });
});

$(".edit_role").click(function(){
  var role_id = $(this).attr('role-id');
  var url = create_url('<?= Url::to(["/depart/edit-role"])?>');
  //页面层
  layer.open({
    type: 2,
    title:'编辑职位',
    area: ['80%', '50%'], //宽高
    maxmin: true,
    content: url+"id="+role_id
  });
});

$("#delete_depart").click(function(){
	layer.confirm('确定删除部门？', {
	  btn: ['是','取消'] //按钮
	}, function(){
		 $.get('<?= Url::to(['/depart/delete-depart','id'=>$depart->id])?>',function(result){
		  if(result.error == 1){
				layer.alert(result.message, {
				  skin: 'layui-layer-rim' //样式类名
				  ,closeBtn: 0
				}, function(){
				  location.reload();
				});
		  }else{
			layer.msg(result.message);
		  }
		},'json');
	});
	
	
})

$(".edit_privi").click(function(){
	var role_id = $(this).attr('role-id');
  //页面层
  var index = layer.open({
    type: 2,
    title:'编辑权限',
    //skin: 'layui-layer-rim', //加上边框
    area: ['90%', '90%'], //宽高
    maxmin: true,
    content: "<?= Url::to(['/depart/edit-privi'])?>"+"?id="+role_id
  });
  layer.full(index);
});

$(".delete_role").click(function(){
	var role_id = $(this).attr('role-id');
	layer.confirm('确定删除角色？', {
	  btn: ['是','取消'] //按钮
	}, function(){
	  $.get('<?= Url::to(['/depart/delete-role'])?>',{id:role_id},function(result){
		  if(result.error == 1){
				layer.msg(result.message)
				$.get('<?= Url::to(['/depart/role-list'])?>',{id:<?= $depart->id?>},function(result){
					  $("#depart_oprate").html(result.content)
				},'json');
		  }else{
			layer.msg(result.message);
		  }
		},'json');
	});
})

$("#depart_type").change(function(){
	var selected_id = $(this).val();
	if(selected_id == 3){
		$("#show_store_list").show();
	}else{
		$("#show_store_list").hide();
	}
});

</script>
 