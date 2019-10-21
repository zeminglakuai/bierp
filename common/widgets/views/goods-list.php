<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use app\common\models\Supplier;
use app\common\config\lang_config;
use app\common\config\lang_value_config;
use app\includes\Common_fun;
?>

<?= Html::cssFile('@web/css/plugins/iCheck/custom.css') ?>
<?= Html::cssFile('@web/css/plugins/switchery/switchery.css') ?>
<?= Html::jsFile('@web/js/plugins/iCheck/icheck.min.js') ?>

<?php 
	$perent_model = new $model;
	$model_attr = $perent_model->attributeLabels();

?>
<div style="overflow:auto;width:100%;margin-bottom:5em;" id="goods_list_block">
	<div class="ibox" style="<?= $width?'width:'.$width.'px':''?>">
	  <div class="ibox-content" style="padding:10px 5px 5px 10px;">
	  	<form name="" id="goods_list_form">
		<table class="table table-hover dataTable">
			<thead id="goods_list_thead" style="<?= $width?'width:'.$width.'px':''?>">
				<tr>
				<th width="50px;">
					<div class="checkbox i-checks">
		                <label><input type="checkbox" id="check_all" value=""></label>
		            </div>
				</th>
				<?php 
				$is_exited_switch = 0;
				foreach($title_arr as $kk => $vv){
					if (isset($vv['type']) && $vv['type'] == 'switch') {
						$is_exited_switch = 1;
					}
				?>
				<th align="center" class="<?
					if($vv['sort_able']){
						 if($search_data['sortby'] == $kk){
						 	if($search_data['order'] == 4){
						 		echo 'sorting_asc data_sort';
						 	}else{
						 		echo 'sorting_desc data_sort';
						 	}
						 }else{
						 	echo 'sorting data_sort';
						 }
					}?>" data-type="<?= $kk?>" <?= isset($vv['width'])?'width="'.$vv['width'].'" style="width:'.$vv['width'].'"':''; ?>>
					<?= isset($vv['tips'])?'<span class="table_th_tips" data-toggle="tooltip" data-placement="top" data-original-title="'.$vv["tips"].'">':'' ?>
					<?= isset(lang_config::${$kk})?lang_config::${$kk}:$model_attr[$kk] ?>
					<?= isset($vv['requir'])?'<span style="color:red;">*</span>':'' ?>
					<?= isset($vv['tips'])?'</span>':'' ?>
				</th>
				<?php
				}?>
				<th align="center">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php if($data_list){
				$index = 1;

			foreach($data_list as $kk => $vv){

			?>
				<tr id="goods_row_<?= $vv->id?>">

				<td>
				<div class="checkbox i-checks">
	                <label>
	                	<input type="checkbox" class="goods_ids" name="goods_id[]" value="<?= isset($vv->goods_id)?$vv->goods_id:$vv->id?>" >
						<?= $index?>
	                </label>
	            </div>
				</td>
				<?php foreach($title_arr as $kkk => $vvv){
					if (isset($vvv['total']) || isset($vvv['average'])) {
						if (!isset($$kkk)) {
							$$kkk = 0;
						}
						$$kkk += $vv->$kkk;
					}
				?>
				<td>
				<div id="<?= $kkk.'_'.$vv->id ?>" title="<?= isset($title_arr[$kkk]['tips'])?$title_arr[$kkk]['tips']:'' ?>">	
					<?php 

					if ($vvv['edit_able'] && !$vvv['type']) {
						$data_type = isset($vvv['lable_name'])?$vvv['lable_name']:$kkk;
						echo '<div class="lable_edit" data-id="'.$vv->id.'" data-type="'.$data_type.'">';
					}
					if ($vvv['link']) {
						$id_label = $vvv['link']['id'];
						echo '<a data-id="'.$vv->$id_label.'" data-url="'.$vvv['link']['url'].'" class="view_label_content">';
					}

					if (strpos($kkk, 'is_') === 0) {
						if ($vvv['type'] == 'switch') {
						?>
						<input type="checkbox" class="js-switch" <?= $vv->$kkk==1?'checked':''?> data-url="<?= $vvv['switch_url']?>" data-type="<?= $kkk ?>" data-id="<?= $vv->id ?>" id="<?= $kkk ?>_<?= $vv->id ?>" />
						<?php
						}else{
							echo $vv->$kkk == 1?'是':'否';
						}
					}elseif(strpos($kkk, '_time') > 0){
						echo date('Y-m-d H:i:s',$vv->$kkk);
					}elseif($kkk ==  'goods_store_info'){
						$store_info = Common_fun::get_goods_store($vv->goods_id);
						if ($store_info) {
							echo Html::dropDownList('store_id', isset($vv->store_id)?$vv->store_id:0, Common_fun::get_goods_store($vv->goods_id),['class' => 'form-control goods_stor_info','id'=>'goods_store_info','style'=>"padding:0px;"]);
						}else{
							echo 'None';
						}
					}elseif($kkk ==  'store_codes'){
						if (@$store_codes = unserialize($vv->$kkk)) {
							foreach ($store_codes as $key => $value) {
								 echo $value['store_code'].'/'.$value['number'].'<br>';
							}
						}else{
							echo 'None';
						}
					}elseif(strpos($kkk, '_type') > 0){
						if ($vvv['type'] == 'select') {
					?>
						<select class="goods_list_change form-control" style="padding:0px;" data-type="<?= $kkk?>" data-id="<?= $vv->id?>">
							<?php foreach($vvv['init_value'] as $init_kk => $init_vv){
							?>
								<option value="<?= $init_kk?>" <?= $vv->$kkk == $init_kk?'selected':'' ?> ><?= $init_vv?></option>
							<?php
							}?>
						</select>
					<?php
						}else{
							$lang_value = lang_value_config::$$kkk;
							echo isset($lang_value[$vv->$kkk])?$lang_value[$vv->$kkk]:$vv->$kkk;
						}
					}
					else{
						echo $vv->$kkk;
					}

					if ($vvv['link']) {
						echo '</a>';
					}
					if ($vvv['edit_able']) {
						echo '</div>';
					}

					?>
				 </div>
				</td>
				<?php
				}?>
			
				<td>
				<?php
					foreach ($opration as $key => $value) {
						if ($value['confirm']) {
						?>
							<A class="delete_goods" data-id="<?= $vv->id?>" action="<?= $value['action'] ?>" href="javascript:void();"  >
								<span class="glyphicon glyphicon-<?= $value['icorn_name']?>"></span>
								<?= $value['lable_name']; ?>
							</A>
						<?php
						}else{
						?>
							<A href="<?= $value['type'] == 'link'?Url::to(['/'.$model_name_lower.'/'.$value['action'],'id'=>$vv['id']]):'javascript:void();' ?>" <?php if($value['type'] == 'js'){echo 'class="delete_'.$model_name_lower.'" data-id="'.$vv['id'].'"';} ?> >
								<span class="glyphicon glyphicon-<?= $value['icorn_name']?>"></span>
								<?= $value['lable_name']; ?>
							</A>
						<?php
						}
					?>
					<?php
				}
				?>
			</td>
				</tr>
			<?php 
				$index++;
			}?>
			<?php }else{
			?>
			<tr>
			<td colspan="<?= count($title_arr)+2 ?>" align="center">单据下没有数据</td>
			</tr>
			<?php  
			}?>


			<tr>
			<td>
			</td>

			<?php foreach($title_arr as $kk => $vv){?>

				<td>
				<div class="<?= $kk ?>_total">
				<?php
				if (isset($vv['total'])) {
					echo $$kk;
				}

				if (isset($vv['average'])) {
					echo round($$kk/($index-1),2);
				}

				?>
				</div>
				</td>
			<?php
			}?>

			<td>
			</td>
			</tr>
			</tbody>
		</table>
		</form>
	  </div>
	</div>
</div> 

<script type="text/javascript">

	$("#check_all").on('ifChecked',function(event){
	  $(".goods_ids").iCheck('check');
	});

	$("#check_all").on('ifUnchecked',function(event){
	  $(".goods_ids").iCheck('uncheck');
	});

	$('.goods_ids').on('ifChecked', function(event){
	  	$(this).parent().parent().parent().parent().parent().css('background-color','#f5f5f5')
	});
	$('.goods_ids').on('ifUnchecked', function(event){
	  	$(this).parent().parent().parent().parent().parent().css('background-color','')
	});

	$(".data_sort").click(function(){
		var sortby = $(this).attr('data-type');
		var order = $(this).attr("class") == 'sorting_desc data_sort'?'SORT_ASC':'SORT_DESC';
		<?php
			if ($present_action) {
				$url_arr = ["/".$model_name."/".$present_action];
			}else{
				$url_arr = ["/".$model_name."/edit"];
			}
			foreach (Yii::$app->request->get() as $key => $value) {
				if (array_key_exists($key, $title_arr)) {
					$url_arr = array_merge($url_arr,[$key=>$value]);
				}
				$url_arr = array_merge($url_arr,['id'=>Yii::$app->request->get('id')]);
			}
		?>
		var to_url = '<?= Url::to($url_arr)?>';
		to_url = create_url(to_url);
		window.location.href= to_url+'sortby='+sortby+'&order='+order;
	});

	$(".delete_goods").click(function(){
		if(confirm('要删除该记录吗？')){
			var id = '<?= Yii::$app->request->get("id")?>';
			var data_id = $(this).attr('data-id');
			var action = $(this).attr('action');
			$.get("<?= Url::to(['/'.$model_name_lower]) ?>/"+action,{id:id, data_id:data_id},function(result){
				if(result.error == 1){
					$("#goods_row_"+data_id).remove();
				}else{
					layer.msg(result.message);
				}
			},'json');
		}
	});

	$(".lable_edit").hover(
		function(){
			$(this).addClass('lable_edit_over');
		},function(){
			$(this).removeClass('lable_edit_over');
		}
	);

	$(".goods_list_change").change(function(){
		var id = '<?= Yii::$app->request->get("id")?>';
		var value = $(this).val();
		var data_id = $(this).attr("data-id");		
		var data_type = $(this).attr("data-type");
		var target = $(this);
		$.get('<?= $update_label_url ?>',{value: value, id:id, data_id: data_id, data_type:data_type},function(result){
							if(result.error == 1){
								layer.msg(result.message);
							}else{
								layer.msg(result.message,function(){});
							}
						},'json');
	})


	$(".lable_edit").click(
		function(){
			var id = '<?= Yii::$app->request->get("id")?>';
			var data_id = $(this).attr("data-id");
			var data_type = $(this).attr("data-type");
			var target = $(this);
			if($(this).children("input").length > 0){
			}else{
				var ima_code = $(this).text();
				var input_html = '<input type="text" value="'+ima_code+'" style="width:100%;" class="edit_input" />'; 
				$(this).html(input_html);
				$('.edit_input').focus();
				$('.edit_input').select();
			}

			function calculate_value(calculate_value){
				for(var i in calculate_value){
					var inner_div = $("#"+calculate_value[i].label_name+'_'+data_id).children();
					console.log(inner_div);
					if (inner_div.length > 0) {
						inner_div.html(calculate_value[i].new_value);
					}else{
						$("#"+calculate_value[i].label_name+'_'+data_id).html(calculate_value[i].new_value);
					}
			    }
			}
			
			$('.edit_input').blur(function(){
				var value = $(this).val();
				if(value == ima_code){
			  		target.html(ima_code);
			  		return false;
			  	}
				$.get('<?= $update_label_url ?>',{value: value, id:id, data_id: data_id, data_type:data_type},function(result){
					if(result.error == 1){
						target.html(result.content);
						if (result.calculate_value) {
							calculate_value(result.calculate_value);
						};
					}else if(result.error == 3){
						target.html(ima_code);
					}else{
						target.html(ima_code);
						layer.msg(result.message,function(){});
					}
				},'json');
			});

			$('.edit_input').keydown(function(event){
			  if(event.keyCode == 13) {
			  	event.stopPropagation();
			  	event.preventDefault();
			  	var value = $(this).val();
			  	if(value == ima_code){
			  		target.html(ima_code);
			  		return false;
			  	}
				$.get('<?= $update_label_url ?>',{value: value, id:id, data_id: data_id, data_type:data_type},function(result){
					if(result.error == 1){
						target.html(result.content);
						if (result.calculate_value) {
							calculate_value(result.calculate_value);
						};
					}else if(result.error == 3){
						target.html(ima_code);
					}else{
						target.html(ima_code);
						layer.msg(result.message,function(){});
					}
				},'json');
			  }
			});
	 
		}
	);
	
	$(".view_label_content").click(function(){
		var view_url = $(this).attr('data-url');
		var view_id = $(this).attr('data-id');		
	    layer.open({
	      type: 2,
	      title:'查看',
	      area: ['90%', '90%'], //宽高
	      maxmin: true,
	      content: view_url+'?id='+view_id
	    });
	})

  	$(function () { $("[data-toggle='tooltip']").tooltip(); });

	$(document).ready(function(){

		$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})
	}
	);
</script>

<script type="text/javascript">
var ori_xx = 0
var ori_yy = 0
var move_goods_list = function(e){
	var new_xx = e.originalEvent.x || e.originalEvent.layerX || 0;
    var new_yy = e.originalEvent.y || e.originalEvent.layerY || 0;

	var move_px_x = (new_xx - ori_xx)*2;
	var move_px_y = (new_yy - ori_yy)*2;
	if (ori_xx > 0 && e.data.type == 'x') {
		$("#goods_list_block").scrollLeft($("#goods_list_block").scrollLeft() + move_px_x)
		//$("body").scrollTop($("body").scrollTop() + move_px_y)
	}

	if (ori_yy > 0 && e.data.type == 'y') {
		$("body").scrollTop($("body").scrollTop() + move_px_y)
	}

	ori_xx = new_xx;
    ori_yy = new_yy;
 
	console.log(new_xx +'-------'+ new_yy);
};

document.onkeydown = function(event){
 	if (event.target.nodeName == 'TEXTAREA' || event.target.nodeName == 'INPUT') {
        return;
    };
 
    if (event.keyCode == 32) {
        event.preventDefault();
        $("body").css('cursor','move');
		var ori_xx = 0;
        var ori_yy = 0;
		$('#goods_list_block').on('mousemove',{type:'x'},move_goods_list);
    };

    if (event.keyCode == 16) {
        event.preventDefault();
        $("body").css('cursor','move');
		var ori_xx = 0;
        var ori_yy = 0;
		$('#goods_list_block').on('mousemove',{type:'y'},move_goods_list);
    };



}

document.onkeyup = function(event){
 	if (event.target.nodeName == 'TEXTAREA' || event.target.nodeName == 'INPUT') {
        return;
    };
    /* Act on the event */
    if (event.keyCode == 32 || event.keyCode == 16) {
        event.preventDefault();
        $("body").css('cursor','auto');
        $('#goods_list_block').off()
        ori_xx = 0
		ori_yy = 0
    };

}



</script>





<?php
	if ($is_exited_switch) {
?>
<?= Html::jsFile('@web/js/plugins/switchery/switchery.js') ?>
<script type="text/javascript">
	var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
	elems.forEach(function(html) {
	  var switchery = new Switchery(html,{ color: 'rgb(26, 179, 148)'});
	});

	$(".js-switch").change(function(){
		var value = $(this).prop('checked')?1:0;
		var url = '<?= $update_label_url ?>';
		var data_id = $(this).attr('data-id');
		var id = '<?= Yii::$app->request->get("id")?>';
		var data_type = $(this).attr('data-type');
		var this_checkbox = $(this);
		$.get(url,{value: value, id:id, data_id: data_id, data_type:data_type},function(result){
			if (result.error == 1) {
			}else{
				this_checkbox.prop('checked','false');
				layer.msg(result.message);
			}
		},'json')
	})
</script>
<?php
	}
?>
