<?php

use yii\helpers\Url;

?>
<div class="oprate_bar">
  <div class="row">
    <div class="col-sm-3">
    	<?php if($create){
    	?>
    	<a  <?= $create['type'] == 'link'?'href="'.$create['url'].'"':'id="'.$create['id'].'"'?> class="btn btn-warning btn-sm"><i class="fa fa-plus"></i> <?= $create['label_name']?></a>
  		<?php
  		}?>

    	<?php if($export){
    	?>		
    	<div class="btn-group dropup">
          <button data-toggle="dropdown" class="btn btn-warning btn-sm dropdown-toggle">导出EXCEL <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
        	<?php 
        		foreach ($export_list as $key => $value) {
        		?>
    	    	    <li><a href="<?= Url::to([$export['module_name'].'/export','template_id'=>$value->id,'module_name'=>$export['module_name'],'type'=>$export['type'],'id'=>$export['id']])?>" target="_blank"><?= $value->template_name ?></a></li>
    			<?php	
		      }?>
          </ul>
      	</div>
		  <?php
		  }?>

      <?php if($export_ppt && $export_ppt_list){
      ?>    
      <div class="btn-group dropup">
          <button data-toggle="dropdown" class="btn btn-warning btn-sm dropdown-toggle">导出PPT <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
          <?php 
            foreach ($export_ppt_list as $key => $value) {
            ?>
                <li><a href="<?= Url::to([$export['module_name'].'/export-ppt','template_id'=>$value->id,'module_name'=>$export['module_name'],'type'=>$export['type'],'id'=>$export['id']])?>" target="_blank"><?= $value->template_name ?></a></li>
          <?php 
          }?>
          </ul>
        </div>
      <?php
      }?>

  	</div>
    <div class="col-sm-4">
      <?php if($approval_process){
        foreach ($approval_process as $key => $value) {
          if($key > 0){
      ?>
        <button 
          <?= $key-$admit_process["model"]->{$admit_process["status_label"]} == 1?'':'disabled="disabled"'?> 
          class="btn <?= $key-$admit_process["model"]->{$admit_process["status_label"]} <= 1?'btn-danger':''?> btn-sm admit_btn" 
          data-status="<?= $key?>" 
          data-toggle="tooltip" data-placement="top" data-original-title="<?= isset($value['tips'])?$value['tips']:''?>"
        >
          <i class="fa fa-check-square"></i> 
          <?= $key?>.
          <?= $value['process_name'] ?>
        </button>
      <?php
        }
        }
      }
      ?>
    </div>

    <div class="col-sm-3">
      <?php if($other_btn){
        foreach ($other_btn as $key => $value) {
        ?>
          <a <?= $value['type'] == 'link'?'href="'.$value['url'].'"':'id="'.$value['id'].'"'?> class="btn btn-danger btn-sm"><i class="fa fa-<?= $value['icon']?>"></i> <?= $value['label_name']?></a> 
        <?php
        }
      }?>
    </div>

    <div class="col-sm-2">
      <button type="button" id="refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i></button>
      <?php if($backup){
      ?>
  	   <a href="<?= $backup['url']?>"class="btn btn-primary btn-sm"><i class="fa fa-mail-reply"></i></a>	
      <?php
      }?>
    </div>
  </div>
</div>

<script>

<?php if($create){
?>
$("#<?= $create['id']?>").click(function(){
  //页面层
  layer.open({
    type: 2,
    title:'<?= $create["label_name"]?>',
    //skin: 'layui-layer-rim', //加上边框
    area: ['80%', '80%'], //宽高
    maxmin: true,
    content: '<?= $create["url"]?>',
    end:function(){
      location.reload();
    }
  });
});
<?php
}?>

<?php if($admit_process){
?>
function check_if_disabled(ob){
  if (ob.hasClass('disabled')) return true;
  return false;
}

// $(".admit_btn").click(function(){
//   if (check_if_disabled($(this))) return false;
//   if(confirm('确认通过审核？')){
//     var process_status = $(this).attr('data-status');
//     var url = '<?= $admit_process["url"]?>';
//     $.get(url,{process_status:process_status},function(result){
//       if(result.error == 1){
//         layer.msg(result.message, function(){
//           location.reload();
//         });
//       }else{
//         $("#goods_row_"+result.content).addClass('danger');
//         setTimeout('$("#goods_row_"+'+result.content+').removeClass("danger")',3000)
//         layer.msg(result.message, function(){});
//       }
//     },'json');
//   }
// });

$(".admit_btn").click(function(){
  //页面层
  var process_status = $(this).attr('data-status');
  var url = '<?= $admit_process["url"]?>';
  layer.open({
    type: 2,
    title:$(this).text(),
    //skin: 'layui-layer-rim', //加上边框
    area: ['950px', '320px'], //宽高
    content: 'to-admit?process_status='+process_status+'&url='+encodeURIComponent(url)
  });
});




<?php
}?>

$('#refresh').click(function(){
  location.reload();
})
</script>