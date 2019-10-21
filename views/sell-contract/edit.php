<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\common\models\DictionaryValue;
use app\common\models\Brand;
use app\modules\admin\config\config;

?>

<?= Html::jsFile('@web/ueditor/ueditor.config.js') ?>
<?= Html::jsFile('@web/ueditor/ueditor.all.min.js') ?>

<div class="ibox ">
  <div class="ibox-content">
	<form action="<?= Url::to(['/sell-contract/update']) ?>" method="post" id="data_form"  class="form-horizontal" enctype="multipart/form-data">
    <script type="text/plain" id="editor" name="content" style="height:300px;"><?= $contract->content?></script> 
    <script type="text/javascript">var editor = UE.getEditor('editor');</script> 
	</form>
  </div>
</div>

<?= app\common\widgets\Submit::widget([
									'model'=>$contract,
									'model_name'=>"sell-contract",
									'form_name'=>'data_form',
									'url'=>Url::to(["/sell-contract/update-contract","id"=>$contract->id])
									]);
?>