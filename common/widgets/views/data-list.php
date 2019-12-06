<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use app\common\config\lang_config;
use app\common\config\lang_value_config;
?>

<?php 
	$perent_model = new $model;
	$model_attr = $perent_model->attributeLabels();

?>
<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
	<table class="table table-hover dataTable">
		<thead>
			<tr>
			<?php foreach($title_arr as $kk => $vv){
			?>
			<th align="center" class="<?
				if($vv){
					 if($search_data['sortby'] == $kk){
					 	if($search_data['order'] == 4){
					 		echo 'sorting_asc data_sort';
					 	}else{
					 		echo 'sorting_desc data_sort';
					 	}
					 }else{
					 	echo 'sorting data_sort';
					 }
				}?>" data-type="<?= $kk?>">
				<?= isset(lang_config::${$kk})?lang_config::${$kk}:$model_attr[$kk] ?>
			</th>	
			<?php
			}?>
			<th align="center">操作</th>
			</tr>
		</thead>
		<tbody>
		<?php if($data_list){?>
		<?php foreach($data_list as $kk => $vv){?>
			<tr id="data_row_<?= $vv->id?>">
			<?php foreach($title_arr as $kkk => $vvv){
			?>
			<td>
				<?php
				if(strpos($kkk, '&') > 0){
					$data_label_arr = explode('&', $kkk);
 
					$present_kkk = $data_label_arr[1];
					$present_vv = $vv->$data_label_arr[0];

					if (strpos($present_kkk, 'is_') === 0) {
						echo $present_vv->$present_kkk == 1?'是':'否';
					}elseif(strpos($present_kkk, '_time') > 0){
						echo $present_vv->$present_kkk?date('Y/m/d H:i',$present_vv->$present_kkk):'';
					}elseif(strpos($present_kkk, '_status') > 0){
						echo !$present_vv->$present_kkk?lang_value_config::${$present_kkk}[$present_vv->$present_kkk]:$present_vv->status_name;
					}elseif(strpos($present_kkk, '_type') > 0){
						echo isset(lang_value_config::${$present_kkk}[$present_vv->$present_kkk])?lang_value_config::${$present_kkk}[$present_vv->$present_kkk]:$present_vv->$present_kkk;
					}elseif($present_kkk =='status_name'){
						echo !empty($present_vv->$present_kkk)?$present_vv->$present_kkk:'未审核';
					}
					else{
						echo $present_vv->$present_kkk;
					}
				}else{
					if (strpos($kkk, 'is_') === 0) {
						echo $vv->$kkk == 1?'是':'否';
					}elseif(strpos($kkk, '_time') > 0){
						echo $vv->$kkk?date('Y/m/d H:i',$vv->$kkk):'';
					}elseif(strpos($kkk, '_status') > 0){
						echo !$vv->$kkk?lang_value_config::${$kkk}[$vv->$kkk]:$vv->status_name;
					}elseif(strpos($kkk, '_type') > 0){
						echo isset(lang_value_config::${$kkk}[$vv->$kkk])?lang_value_config::${$kkk}[$vv->$kkk]:$vv->$kkk;
					}elseif($kkk =='status_name'){
						echo !empty($vv->$kkk)?$vv->$kkk:'未审核';
					}
					else{
						echo $vv->$kkk;
					}
				}


				?>
			</td>
			<?php
			}?>
			<td>
				<?php
					foreach ($opration as $key => $value) {
					?>
						<A  class="btn btn-xs btn-<?= $key == 'delete'?'danger':'primary';?> <?= $key ?>_<?= $model_name_lower?>"
						 	href="<?= $value['type']== 'js'?'javascript:void();':Url::to(['/'.$model_name_lower.'/'.$value['action'],'id'=>$vv->id])?>"
						 	data-id="<?= $vv->id?>"
						 	data-id-name="<?= $value['id_name']?>"
						 	data-toggle="tooltip" data-placement="top" data-original-title="<?= isset($value['tips'])?$value['tips']:''?>"
						 	data-action="<?= $value['action']?>"
						 	>
							<span class="glyphicon glyphicon-<?= $value['icorn_name']?>"></span>
							<?= $value['lable_name']; ?>
						</A>
					<?php
					}
				?>
			</td>
			</tr>
		<?php }?>
		<?php }else{
		?>
		<tr>
		<td colspan="<?= count($title_arr)+1 ?>" align="center">当前单据或条件下没有数据</td>
		</tr>
		<?php  
		}?>

		</tbody>
	</table>
  </div>
</div>
<div class="row" style="margin-bottom:5em;">
	<div class="col-sm-6"></div>
	<div class="col-sm-5" align="right">
	  <?php
	  echo LinkPager::widget([
	      'pagination' => $pages,
	  ]);
	  ?>
	</div>
</div>

<script type="text/javascript">
	$(".data_sort").click(function(){
	
		var sortby = $(this).attr('data-type');
		var order = $(this).attr("class") == 'sorting_desc data_sort'?'SORT_ASC':'SORT_DESC';
		<?php
			$url_arr = ["/".$model_name_lower."/index"];
			foreach (Yii::$app->request->get() as $key => $value) {
				if (array_key_exists($key, $search_allowed)) {
					$url_arr = array_merge($url_arr,[$key=>$value]);
				}
			}
		?>
		var to_url = '<?= Url::to($url_arr)?>';
		to_url = create_url(to_url);
		window.location.href= to_url+'sortby='+sortby+'&order='+order;
	});

<?php if($opration['edit']['type'] == 'js'){
?>
$(".edit_<?= $model_name_lower?>").click(function(){
  var data_id = $(this).attr('data-id');
  var data_id_name = $(this).attr('data-id-name')?$(this).attr('data-id-name'):'id';  
  var data_action = $(this).attr('data-action')?$(this).attr('data-action'):'edit';
  var to_url = create_url('<?= "/".$model_name_lower."/" ?>'+data_action);
  //页面层
  layer.open({
    type: 2,
    title:'编辑',
    //skin: 'layui-layer-rim', //加上边框
    area: ['90%', '80%'], //宽高
    content: to_url+data_id_name+'='+data_id
  });
});
<?php
}?>

<?php if($opration['delete']['type'] == 'js'){
?>

$(".delete_<?= $model_name_lower?>").click(function(){
  var data_id = $(this).attr('data-id');
  var data_action = $(this).attr('data-action')?$(this).attr('data-action'):'delete';
  var data_id_name = $(this).attr('data-id-name')?$(this).attr('data-id-name'):'id'; 
  var delete_url = create_url('<?= "/".$model_name_lower."/" ?>'+data_action);
  if(confirm('确认删除该数据？')){
  	$.get(delete_url+data_id_name+'='+data_id,function(result){
		if(result.error == 1){
			layer.msg(result.message);
			$("#data_row_"+data_id).remove();
		}else{
			layer.msg(result.message);
		}
  	},'json');
  }
});

<?php
}?>
</script>
<script>
  $(function () { $("[data-toggle='tooltip']").tooltip(); });
</script>