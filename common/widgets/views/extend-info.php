<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\common\models\admin; 
?>
<?php
	foreach ($filed_arr as $key => $value) {
?>
	<div class="form-group">
	  <label class="col-sm-2 control-label"><?=$value->dictionary_value ?></label>
	  <div class="col-sm-9">
	  		<input type="hidden" name="ContactExtendInfo[id][]" value="<?=$extend_info_arr[$value->id]['id']?>">
	        <input type="hidden" name="ContactExtendInfo[filed_id][]" value="<?=$value->id ?>">
	        <input type="text" name="ContactExtendInfo[filed_value][]" value="<?=$extend_info_arr[$value->id]['filed_value']?>" class="form-control">
	  </div>
	</div>
<?php
	}
?>