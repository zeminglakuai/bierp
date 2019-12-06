<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\config\sys_config;
?>

<div class="ibox float-e-margins">
  <div class="ibox-title">
    <h5><?= $module_name?>-复核流程</h5>
    <div class="ibox-tools">
      <button class="btn btn-xs btn-primary" id="add_process"><i class="fa fa-plus"></i> 添加流程</button>
    </div>
  </div>
  <div class="ibox-content">
      <table class="table">
        <thead>
        <tr>
          <th width="5%">状态ID</th>
          <th width="15%">复核按钮名称</th>
          <th width="15%">复核部门类型</th>
          <th width="15%">复核过名称</th>
          <th width="15%">提示</th>
          <th width="10%">允许修改</th>
          <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php 
          foreach($process_data_arr as $kk => $vv){
        ?>
          <tr id="data_row_<?= $kk?>">
          <td><?= $kk?></td>  
          <td><?= $vv['process_name']?></td>
          <td><?= sys_config::$approval_depart_type[$vv['scope_depart_type']]?></td>
          <td><?= $vv['processed_name']?></td>
          <td><?= $vv['tips']?></td>
          <td><?= $vv['allow_amend_order']?'是':'否'?></td>
          <td>
            <button class="btn btn-xs btn-primary edit_process"  data-id="<?= $kk?>"><i class="fa fa-edit"> </i> 编辑</button>
            <button class="btn btn-xs btn-danger delete_process"  data-id="<?= $kk?>"><i class="fa fa-times"> </i> 删除</button>
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
$("#add_process").click(function(){
  //页面层
  layer.open({
    type: 2,
    title:'添加复核流程',
    //skin: 'layui-layer-rim', //加上边框
    area: ['80%', '80%'], //宽高
    maxmin: true,
    content: "<?= Url::to(['/approval-process/create','module'=>$module])?>"
  });
});

$(".edit_process").click(function(){
  var data_id = $(this).attr('data-id');
  var url = create_url('<?= Url::to(["/approval-process/edit"])?>');  
  //页面层
  layer.open({
    type: 2,
    title:'编辑复核流程',
    //skin: 'layui-layer-rim', //加上边框
    area: ['80%', '80%'], //宽高
    maxmin: true,
    content: url+"id="+data_id+'&module=<?= $module?>'
  });
});


$(".delete_process").click(function(){
  var data_id = $(this).attr('data-id');
  layer.confirm('确定删除复核流程？', {
    btn: ['是','取消'] //按钮
  }, function(){
     var url = create_url('<?= Url::to(["/approval-process/delete"])?>'); 
     $.get(url+'id='+data_id+'&module=<?= $module?>',function(result){
      if(result.error == 1){
        layer.msg(result.message);
        //重载模板列表
        var parent_frame = $("#template_list");

        $.get('<?= Url::to(['/approval-process/process-list'])?>',{module:'<?= $module?>'},function(result){
          parent_frame.html(result.content);
        },'json');

        
      }else{
        layer.msg(result.message);
      }
    },'json');
  });
})

</script>
