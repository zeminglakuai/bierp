<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\LinkPager;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '部门列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::jsFile('@web/js/plugins/treeview/bootstrap-treeview.js') ?>

<div style="margin-bottom:70px;">
  <div class="row">
    <div class="col-sm-3">
      <div class="ibox float-e-margins">
        <div class="ibox-title">部门列表</div>
        <div class="ibox-content">
          <div id="depart_list" class="test"> </div>
        </div>
      </div>
    </div>
    <div class="col-sm-9" id="depart_oprate">
       
    </div>
  </div>
</div>
<div class="oprate_bar">
  <div class="row">
    <div class="col-sm-1"><button type="button" id="add_depart" class="btn btn-warning btn-sm"><i class="fa fa-plus"></i> 新建部门</button></div>
    <div class="col-sm-10"></div>
    <div class="col-sm-1"><button type="button" id="refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i> 刷新</button></div>
  </div>
</div>
<script>
 
var deparet_list = <?= $depart_list?>;

$('#depart_list').treeview({
  data: deparet_list,
  levels: 5,
  icon: "glyphicon glyphicon-folder-close",
  selectedIcon: "glyphicon glyphicon-folder-open",
  checkedIcon: "glyphicon glyphicon-folder-open",
  onNodeSelected:function(e,o){
    var index = layer.load(1, {
        shade: [0.4,'#000'] //0.1透明度的白色背景
    });
    $.get('<?= Url::to(['/depart/role-list'])?>',{id:o.id},function(result){
      $(".layui-layer-shade").hide();
      $(".layui-layer-loading").hide();
      $("#depart_oprate").html(result.content)
    },'json');
  }
});

$('#depart_list').treeview('selectNode', [ 0, { silent: true } ]);

$("#add_depart").click(function(){
  //页面层
  layer.open({
    type: 2,
    title:'添加部门',
    //skin: 'layui-layer-rim', //加上边框
    area: ['80%', '500px'], //宽高
    maxmin: true,
    content: '<?= Url::to(["/depart/create"])?>'
  });
});




</script>