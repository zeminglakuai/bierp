<?php


?>

<link href="/css/plugins/summernote/summernote.css" rel="stylesheet">
<link href="/css/plugins/summernote/summernote-bs3.css" rel="stylesheet">


<div class="ibox">
    <div class="ibox-content no-padding">
        <div class="summernote"></div>
    </div>
</div>


<script src="/js/bootstrap.min.js?v=3.3.6"></script>
<script src="/js/plugins/summernote/summernote.min.js"></script>
<script src="/js/plugins/summernote/summernote-zh-CN.js"></script>

<script>
    $(document).ready(function(){
    	$(".summernote").summernote({
			lang:"zh-CN",
			focus: true                  // set focus to editable area after initializing summernote
    	})
    });
</script>


<div class="oprate_bar" style="height:4em;">
	<div class="row">
    <div class="col-sm-8">

    </div>
    <div class="col-sm-4">
		<button id="admit" class="btn btn-primary"><i class="fa fa-check"></i> 确认提交</button>
		<button id="unadmit" class="btn btn-danger"><i class="fa fa-ban"></i> 不予通过</button>
		<button id="cancel" class="btn btn-white"><i class="fa fa-times"></i> 关闭</button>
	</div>
	</div>
</div>
<script type="text/javascript">

	var prasent_index = parent.layer.getFrameIndex(window.name);
	$("#admit").click(function(){
		var remark = encodeURIComponent($('.summernote').code());
	    var process_status = <?= $get_data['process_status'] ?>;
	    var url = '<?= $get_data["url"]?>';

	    $.get(url,{process_status:process_status,remark:remark},function(result){
	      if(result.error == 1){
	        parent.layer.msg(result.message, function(){
	          parent.location.reload();
	        });
	      }else{
	        $("#goods_row_"+result.content).addClass('danger');
	        setTimeout('$("#goods_row_"+'+result.content+').removeClass("danger")',3000)
	        parent.layer.msg(result.message, function(){});
	      }
	    },'json');
	});

	$("#unadmit").click(function(){
		var remark = encodeURIComponent($('.summernote').code());
	    var process_status = <?= $get_data['process_status'] ?>;
	    var url = '<?= $get_data["url"]?>';

	    $.get(url,{process_status:process_status,remark:remark,unadmit:'unadmit'},function(result){
	      if(result.error == 1){
	        parent.layer.msg(result.message, function(){
	          parent.location.reload();
	        });
	      }else{
	        parent.layer.msg(result.message, function(){});
	      }
	    },'json');
	});

	$("#cancel").click(function(){
		parent.layer.close(prasent_index);
	});

</script>
