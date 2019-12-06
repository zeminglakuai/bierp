<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

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
</head>

<body class="gray-bg">

 
<?= Html::jsFile('@web/js/jquery.min.js') ?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>

<?= $content?>

<?= Html::jsFile('@web/js/plugins/slimscroll/jquery.slimscroll.min.js') ?>

</body>
</html>
<?php $this->endPage() ?>
