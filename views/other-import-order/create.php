<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\Depart;
use app\common\models\Role;
use app\common\models\Store;
use app\common\config\sys_config;
use app\common\config\lang_value_config;
use app\common\models\DictionaryValue;



?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>

<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<?= app\common\widgets\OrderForm::widget(['form_data'=>[
        ['type'=>'select','label_name'=>'仓库','name'=>'OtherImportOrder[store_id]','value'=>$other_import_order->store_id,'tips','id'=>'supplier','init_value'=>ArrayHelper::map(Store::get_store(), 'id', 'store_name'),],
        ['type'=>'select','label_name'=>'其他入库类型','name'=>'OtherImportOrder[other_import_order_type]','value'=>$other_import_order->other_import_order_type,'tips','id'=>'other_import_order_type','init_value'=>lang_value_config::$other_import_order_type,],
        ['type'=>'text','label_name'=>'备注','name'=>'OtherImportOrder[remark]','value'=>$other_import_order->remark,'tips','id','init_value',],
      ]
]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$other_import_order,'model_name'=>"other-import-order",'form_name'=>'order_form']); ?>