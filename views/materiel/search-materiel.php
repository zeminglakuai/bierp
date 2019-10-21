<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

?>
<?= Html::cssFile('@web/css/plugins/iCheck/custom.css') ?>
<?= Html::jsFile('@web/js/plugins/iCheck/icheck.min.js') ?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
    <form action="<?= $url?>" method="get" class="form-horizontal">
      <div class="row">

        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">物料名称</label>
            <div class="col-sm-9">
              <input type="text" name="goods_name" class="form-control" value="<?= $search_data['materiel_name']?>" placeholder="物料名称"/>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-11"> </div>
        <div class="col-sm-1">
          <input type="hidden" name="order_id" value="<?= $order_id?$order_id:$search_data['order_id']?>" />
          <input type="submit" class="btn btn-primary btn-sm" value="搜索"/>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
    <form name="" id="goods_list_form">
      <table class="table">
        <thead>
        <tr>
          <th width="5%">
            <div class="checkbox i-checks">
                <label><input type="checkbox" id="check_all" value=""></label>
            </div>
          </th>
          <th width="5%">ID</th>
          <th width="30%">物料名称</th>
          <th width="13%">用途</th>
          <th width="13%">单位</th>
          <th width="13%">单价</th>
          <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($goods_list as $kk => $vv){ ?>
        <tr>
          <td>
            <div class="checkbox i-checks">
                <label><input type="checkbox" class="goods_ids" name="goods_id[]" value="<?= $vv['id']?>" ></label>
            </div>
          </td>
          <td><?= $vv['id']?></td>
          <td><?= $vv['materiel_name']?></td>
          <td><?= $vv['use_to']?></td>
          <td><?= $vv['unit']?></td>
          <td><?= $vv['materiel_price']?></td>
          <td>
      	<a href="javascript:insert_into_order(<?= $vv['id'] ?>)">选定</a>
        </tr>
        <?php }?>
      </tbody>
      </table>
    </form>
  </div>
</div>

<div class="oprate_bar">
  <div class="row">
    <div class="col-sm-10">
    <button type="button" id="insert_goods" class="btn btn-primary btn-sm" disabled="disabled"><i class="fa fa-plus"></i> 选中添加</button>
    </div>
  </div>
</div>

<script>
var id = '<?= $order_id?$order_id:$search_data['order_id']?>';

function insert_into_order(goods_id){
	$.get('<?= $insert_url?>',{id:id,goods_id:goods_id},function(result){
		if(result.error == 1){
			 layer.msg(result.message);
		}else{
			 layer.msg(result.message);
		}
	},'json')
}

$("#insert_goods").click(function(){
  var goods_id = $("#goods_list_form").serialize();
  goods_id = goods_id+'&id='+id;
  $.get('<?= $insert_url?>',goods_id,function(result){
    if(result.error == 1){
      var message = result.message;
      var retrun_message = '';
      for(var o in message){  
        retrun_message = retrun_message+message[o]+"<br>";
      }

      layer.alert(retrun_message, {
        skin: 'layui-layer'
        ,closeBtn: 0
      })
    }else{
       layer.msg(result.message);
    }
  },'json')
});

 


$("#check_all").on('ifChecked',function(event){

  $(".goods_ids").iCheck('check');
  set_insert_true();
});

$("#check_all").on('ifUnchecked',function(event){
  $(".goods_ids").iCheck('uncheck');
  set_insert_true();
});

$(".goods_ids").on('ifUnchecked',function(event){
  set_insert_true();
})

$(".goods_ids").on('ifChecked',function(event){
  set_insert_true();
})


function set_insert_true(){
  //检查是不是有选中数据
  var goods_ids = $("#goods_list_form").serialize();
  if(goods_ids !== ''){
    //设置插入按钮可点击
    $("#insert_goods").prop('disabled',false);
  }else{
    $("#insert_goods").prop('disabled',true);
  }
}

$(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
</script>