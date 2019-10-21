<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\Depart;
use app\common\models\Store;
use app\common\config\sys_config;
?>

<form id="depart_form">
  <table class="table">
  	<tbody>
    <tr>
      <th width="12%">部门名称</th>
      <td><input type="text" name="Depart[depart_name]" class="form-control" /></td>
    </tr>
    <tr>
      <th>父部门</th>
      <td><?= Depart::get_depart_select('Depart[parent_id]') ?></td>
    </tr>
    <tr>
      <th>部门类型</th>
      <td><?= Html::dropDownList('Depart[type]', 0, sys_config::$depart_type,['prompt' => '请选择部门类型','class' => 'form-control','id'=>'depart_type']) ?></td>
    </tr>
    <tr style="display:none;" id="show_store_list">
      <th>所属仓库</th>
      <td><?= Html::dropDownList('Depart[store_id]', $depart->store_id, ArrayHelper::map(Store::find()->asArray()->all(), 'id', 'store_name'),['prompt' => '请选择仓库','class' => 'form-control']) ?></td>
    </tr>    
    <tr>
      <th>备注</th>
      <td><input type="text" name="Depart[depart_desc]" class="form-control"></textarea>
      </td>
    </tr>
    </tbody>
  </table>
</form>
</div>
<div class=" " style="padding:10px 50px;">
<button class="btn btn-danger" id="add_depart"><i class="icon-save"></i>添加</button>
</div>
 

<script>
$("#add_depart").click(function(){
	var param = $("#depart_form").serialize();
	$.post("<?= Url::to(['depart/add-depart'])?>",param,function(result){
		if(result.error == 1){
			layer.alert(result.message);
			parent.location.reload();
		}else{
			layer.alert(result.message);
		}
	},'json');
});


$("#depart_type").change(function(){
	var selected_id = $(this).val();
	if(selected_id == 3){
		$("#show_store_list").show();
	}else{
		$("#show_store_list").hide();
	}
});





</script>
 