<?php
use yii\helpers\Html;
?>
<div class="form-group">
    <label class="col-sm-2 control-label"><?= $inneed?'<span class="red">*</span>':''?> <?= $label_name?></label>
    <div class="col-sm-9">
        <?= Html::dropDownList($name, $value, $init_value,['prompt' => '请选择'.$label_name,'class' => 'form-control','id'=>$id]) ?>
    </div>
</div>