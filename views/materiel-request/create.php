<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\Depart;
use app\common\models\Role;
use app\common\models\Store;
use app\common\models\DictionaryValue;
use app\common\config\sys_config;

?>

<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>


<?= app\common\widgets\OrderForm::widget(['form_data'=>[
        ['type'=>'text','label_name'=>'备注','name'=>'MaterielRequest[remark]','value'=>$MaterielRequest->remark,'tips','id','init_value',],
      ]
]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$MaterielRequest,'model_name'=>$this->context->id,'form_name'=>'order_form']); ?>

 