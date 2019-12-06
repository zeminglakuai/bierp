<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\modules\admin\config\config;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客维商品管理';
$this->params['breadcrumbs'][] = '商品品牌';
?>
<form action="<?= Url::to(['/admin/brand/update-brand']) ?>" method="post" enctype="multipart/form-data">

 <table class="table table-striped">
    <tr>
      <td width="17%">品牌名称</td>
      <td><input type="text" name="Brand[brand_name]" size="20"  value="<?= $brand->brand_name?>"/></td>
    </tr>
    <tr>
      <td>供货商</td>
      <td>
        <?= Html::dropDownList('Brand[supplier_id]', $brand->supplier_id, ArrayHelper::map($su_list, 'id', 'simple_name')) ?>
        </td>
    </tr>
    <tr>
      <td >备注</td>
      <td><input type="text" name="Brand[remark]" size="20"  value="<?= $brand->remark?>"/></td>
    </tr>
  </table>
 <div class="" style="padding:1em;">
    <input type="hidden" name="id" value="<?= $brand->id?>" />
    <button type="submit" class="btn btn-danger">提交</button>
  </div>

</form>