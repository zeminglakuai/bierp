<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

?>
<?php if($title){
?>

<div class="ibox float-e-margins">
  <div class="ibox-title">
     <?= $title['label_name']?>-模板列表 
    <div class="ibox-tools">
      <button class="btn btn-xs btn-primary" id="add_title_template"><i class="fa fa-plus"></i> 添加模板</button>
    </div>
  </div>
  <div class="ibox-content">
      <table class="table">
        <thead>
        <tr>
          <th width="60%">模板名称</th>
          <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php 
          foreach($title['template_list'] as $kk => $vv){
        ?>
          <tr id="data_row_<?= $vv->id?>">
          <td><?= $vv->template_name?></td>
          <td>
            <button class="btn btn-xs btn-primary edit_title_template"  data-id="<?= $vv->id?>"><i class="fa fa-edit"> </i> 编辑</button>
            <button class="btn btn-xs btn-danger delete_title_template"  data-id="<?= $vv->id?>"><i class="fa fa-times"> </i> 删除</button>
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
$("#add_title_template").click(function(){
  //页面层
  layer.open({
    type: 2,
    title:'添加模板',
    //skin: 'layui-layer-rim', //加上边框
    area: ['80%', '80%'], //宽高
    maxmin: true,
    content: "<?= Url::to(['/export-template/create','parent_module'=>$parent_module,'module'=>$module,'type'=>'title'])?>"
  });
});

$(".edit_title_template").click(function(){
  var data_id = $(this).attr('data-id');
  var url = create_url('<?= Url::to(["/export-template/edit"])?>');  
  //页面层
  layer.open({
    type: 2,
    title:'编辑导出模板',
    //skin: 'layui-layer-rim', //加上边框
    area: ['80%', '80%'], //宽高
    maxmin: true,
    content: url+"id="+data_id
  });
});


$(".delete_title_template").click(function(){
  var data_id = $(this).attr('data-id');
  layer.confirm('确定删除？', {
    btn: ['是','取消'] //按钮
  }, function(){
     var url = create_url('<?= Url::to(["/export-template/delete"])?>'); 
     $.get(url+'id='+data_id,function(result){
      if(result.error == 1){
        $("#data_row_"+data_id).remove();
        layer.msg(result.message);
      }else{
        layer.msg(result.message);
      }
    },'json');
  });
})

</script>

<?php
}

?>

<?php if($detail_list){
?>

<div class="ibox float-e-margins">
  <div class="ibox-title">
     <?= $detail_list['label_name']?>-模板列表 
    <div class="ibox-tools">
      <button class="btn btn-xs btn-primary" id="add_detail_template"><i class="fa fa-plus"></i> 添加模板</button>
    </div>
  </div>
  <div class="ibox-content">
      <table class="table">
        <thead>
        <tr>
          <th width="60%">模板名称</th>
          <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php 
          foreach($detail_list['template_list'] as $kk => $vv){
        ?>
          <tr id="date_row_<?= $vv->id?>">
          <td><?= $vv->template_name?></td>
          <td>
            <button class="btn btn-xs btn-primary edit_detail_template"  data-id="<?= $vv->id?>"><i class="fa fa-edit"> </i> 编辑</button>
            <button class="btn btn-xs btn-danger delete_detail_template"  data-id="<?= $vv->id?>"><i class="fa fa-times"> </i> 删除</button>
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
$("#add_detail_template").click(function(){
  //页面层
  layer.open({
    type: 2,
    title:'添加模板',
    //skin: 'layui-layer-rim', //加上边框
    area: ['80%', '80%'], //宽高
    maxmin: true,
    content: "<?= Url::to(['/export-template/create','module'=>$module,'type'=>'detail'])?>"
  });
});

$(".edit_detail_template").click(function(){
  var data_id = $(this).attr('data-id');
  var url = create_url('<?= Url::to(["/export-template/edit"])?>');  
  //页面层
  layer.open({
    type: 2,
    title:'编辑导出模板',
    //skin: 'layui-layer-rim', //加上边框
    area: ['80%', '80%'], //宽高
    maxmin: true,
    content: url+"id="+data_id
  });
});

$(".delete_detail_template").click(function(){
  var data_id = $(this).attr('data-id');
  layer.confirm('确定删除？', {
    btn: ['是','取消'] //按钮
  }, function(){
     var url = create_url('<?= Url::to(["/export-template/delete"])?>'); 
     $.get(url+'id='+data_id,function(result){
      if(result.error == 1){
        layer.msg(result.message);
        $("#date_row_"+data_id).remove();
      }else{
        layer.msg(result.message);
      }
    },'json');
  });
})

</script>

<?php
}?>