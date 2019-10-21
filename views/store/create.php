<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<form id="store_form">
  <table class="table">
	<tbody>
    <tr>
      <th width="12%">仓库名称</th>
      <td><input type="text" name="Store[store_name]" class="form-control"  value="<?= $store->store_name?>" /></td>
    </tr>
    <tr>
      <th>地址</th>
      <td><input type="text" name="Store[address]" class="form-control" value="<?= $store->address?>"  /></td>
    </tr>
    <tr>
      <th>联系人</th>
      <td><input type="text" name="Store[contact]" class="form-control" value="<?= $store->contact?>"  /></td>
    </tr>
    <tr>
      <th>手机号</th>
      <td><input type="text" name="Store[tel]" class="form-control" value="<?= $store->tel?>"  /></td>
    </tr>   
    <tr>
      <th>备注</th>
      <td>
      <input type="hidden" name="id" class="form-control" value="<?= $store->id?>">
      <input type="text" name="Store[desc]" class="form-control" value="<?= $store->desc?>"  />
      </td>
    </tr>
    </tbody>
  </table>
</form>
  <div class=" " style="padding:10px 50px;">
    <?php if(isset($store->id)){
    ?>
    <button class="btn btn-danger" id="edit_store"><i class="icon-save"></i>编辑</button>
    <?php
    }else{
    ?>
    <button class="btn btn-danger" id="add_store"><i class="icon-save"></i>添加</button>
    <?php
    }?>
  </div>

<script>
$("#add_store").click(function(){
	var param = $("#store_form").serialize();
	$.post("<?= Url::to(['store/add-store'])?>",param,function(result){
		if(result.error == 1){
			layer.alert(result.message);
			parent.location.reload();
		}else{
			layer.alert(result.message);
		}
	},'json');
});

$("#edit_store").click(function(){

  var param = $("#store_form").serialize();
  $.post("<?= Url::to(['store/edit-store'])?>",param,function(result){
    if(result.error == 1){
      layer.msg(result.message);
      parent.location.reload();
    }else{
      layer.alert(result.message);
    }
  },'json');
});



</script>