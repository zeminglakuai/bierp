<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\LinkPager;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->context->page_title;
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::jsFile('@web/js/plugins/treeview/bootstrap-treeview.js') ?>

<div style="margin-bottom:70px;">
  <div class="row">
    <div class="col-sm-2">
      <div class="ibox float-e-margins">
        <div class="ibox-title"><h5>模块列表</h5></div>
        <div class="ibox-content">
          <div id="module_list" class="test"> </div>
        </div>
      </div>
    </div>
    <div class="col-sm-10" style="position:relative;">
      <div id="template_list" style="position:fixed;top:28px;left:17%;width:82%;">
         
      </div>
    </div>

  </div>
</div>

<?= app\common\widgets\OperateBar::widget([
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>


<script>
var module_list = <?= $module_list?>;

$('#module_list').treeview({
  data: module_list,
  levels: 1,
  nodeIcon: "glyphicon glyphicon-folder-close",  
  icon: "glyphicon glyphicon-folder-close",
  selectedIcon: "glyphicon glyphicon-folder-open",
  checkedIcon: "glyphicon glyphicon-folder-open",
  onNodeSelected:function(e,o){
    var index = layer.load(1, {
        shade: [0.4,'#000'] //0.1透明度的白色背景
    });
    $.get('<?= Url::to(['/help/content-frame'])?>',{module:o.id},function(result){
      $(".layui-layer-shade").hide();
      $(".layui-layer-loading").hide();
      $("#template_list").html(result.content)
    },'json');
  }
});
 
</script>