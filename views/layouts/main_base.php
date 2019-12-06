<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\common\config\sys_config;
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html>
<head>
<?php $this->head();?>
<meta charset="utf-8">
<meta http-equiv="content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp" />
<title>
<?= $this->title;?>
</title>
<!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
<![endif]-->

<link rel="shortcut icon" href="favicon.ico">
<?= Html::cssFile('@web/css/bootstrap.min.css') ?>
<?= Html::cssFile('@web/css/font-awesome.min.css') ?>
<?= Html::cssFile('@web/css/animate.min.css') ?>
<?= Html::cssFile('@web/css/style.min.css') ?>
<?= Html::cssFile('@web/css/custom.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>

<?= Html::jsFile('@web/js/jquery.min.js') ?>
<?= Html::jsFile('@web/js/jquery-migrate-1.2.1.min.js') ?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>
<?= Html::jsFile('@web/js/plugins/layer/layer.min.js') ?>
<?= Html::jsFile('@web/js/custom.js') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

</head>
<!--  oncontextmenu="window.event.returnValue=false" oncontextmenu="return false" onselectstart="return false" oncopy="return false" -->
<body class="gray-bg">
<div class="row border-bottom white-bg page-heading">
  <div style="padding:5px 0;">
    <?= Breadcrumbs::widget(['homeLink'=>['label'=>'当前位置','url'=>'#'],'itemTemplate' => "<li class='bread_li'>{link}</li>",'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
  </div>
</div>
<?= $content?>

<div id="small-chat">
    <span class="badge badge-warning pull-right"></span>
    <a class="open-small-chat">
        <i class="fa fa-h-square"></i>
    </a>
</div>

<script>

$('#turn_back').click(function(){
	history.go(-1);
})
$("#small-chat").click(function(){
  //页面层
  var model = '<?= $this->context->id?>';
  layer.open({
    type: 2,
    title:'帮助文档',
    //skin: 'layui-layer-rim', //加上边框
    area: ['80%', '80%'], //宽高
    maxmin: true,
    content: '<?= Url::to(["/help/view","model"=>$this->context->id])?>'
  });
});
</script>
</body>
</html>
<?php $this->endPage() ?>
