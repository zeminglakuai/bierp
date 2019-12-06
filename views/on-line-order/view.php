<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\modules\admin\config\config;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '查看线上销售单';
$this->params['breadcrumbs'][] = '查看线上销售单';
?>

<div class="page_header">
  <div class="fl" >
    <div class="page_title"> 线上销售单详情 </div>
  </div>
  <div class="fr" > </div>
  <div class="cl" ></div>
</div>
<div class="" style="margin-top:1em;">
	
</div>
<div class="oparet_bar">
  <div class="">
    <div class="col-xs-6" >
      <div class="btn-group dropup">
        <button data-toggle="dropdown" class="btn btn-xs btn-yellow dropdown-toggle " aria-expanded="false"> <i class="glyphicon glyphicon-print"></i>打 印 <span class="ace-icon fa fa-caret-down icon-on-right"></span> </button>
        <ul class="dropdown-menu ">
          <li> <a href="#">打印单据1</a> </li>
          <li> <a href="#">打印单据2</a> </li>
        </ul>
      </div>
      <div class="btn btn-xs btn-yellow"><i class="glyphicon glyphicon-export"></i>导 出</div>
    </div>
    <div class="col-xs-6 center">
      <div class="btn btn-xs btn-yellow"><i class="glyphicon glyphicon-import"></i>导 入</div>
      <div class="btn btn-xs btn-yellow"><i class="glyphicon glyphicon-floppy-save"></i>保 存</div>
      <div class="btn btn-xs btn-yellow"><i class="glyphicon glyphicon-ok-circle"></i>审 核</div>
    </div>
  </div>
</div>
