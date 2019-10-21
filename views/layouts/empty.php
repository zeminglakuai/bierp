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

<link rel="shortcut icon" href="favicon.ico">
<?= Html::cssFile('@web/css/bootstrap.min.css') ?>
<?= Html::cssFile('@web/css/font-awesome.min.css') ?>
<?= Html::cssFile('@web/css/animate.min.css') ?>
<?= Html::cssFile('@web/css/style.min.css') ?>
<?= Html::cssFile('@web/css/custom.css') ?>

<?= Html::jsFile('@web/js/custom.js') ?>
<?= Html::jsFile('@web/js/jquery.min.js') ?>
<?= Html::jsFile('@web/js/plugins/layer/layer.min.js') ?>
<?= Html::jsFile('@web/js/jquery.jqprint-0.3.js') ?>
</head>
<body style="height:auto;" class="gray-bg">
<?php echo $content ?>
</body>
</html>
<?php $this->endPage() ?>