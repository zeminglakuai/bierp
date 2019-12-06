<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
$this->title = '客维商品管理';
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php $this->head();?>
<title>
<?= $this->title;?>
</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<?= Html::cssFile('@web/css/bootstrap.min.css') ?>
<?= Html::cssFile('@web/css/font-awesome.min.css') ?>
<?= Html::cssFile('@web/css/animate.min.css') ?>
<?= Html::cssFile('@web/css/style.min.css') ?>

<?php echo $content ?>