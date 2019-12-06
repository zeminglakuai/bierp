<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\Store;
use app\common\config\sys_config;

?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>

<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<?= app\common\widgets\OrderForm::widget(['form_data'=>[
        ['type'=>'select','label_name'=>'出库仓库','name'=>'RequestAdjustOrder[from_store_id]','value'=>$request_adjust_order->from_store_id,'tips','id'=>'from_store_id','init_value'=>ArrayHelper::map(Store::find()->all(), 'id', 'store_name'),],
        ['type'=>'select','label_name'=>'接受仓库','name'=>'RequestAdjustOrder[to_store_id]','value'=>$request_adjust_order->to_store_id,'tips','id'=>'to_store_id','init_value'=>ArrayHelper::map(Store::find()->all(), 'id', 'store_name'),],
        ['type'=>'text','label_name'=>'收货人','name'=>'RequestAdjustOrder[consignee]','value'=>$request_adjust_order->consignee,'tips','id','init_value',],
        ['type'=>'text','label_name'=>'收货电话','name'=>'RequestAdjustOrder[tel]','value'=>$request_adjust_order->tel,'tips','id','init_value',],
        ['type'=>'text','label_name'=>'收货地址','name'=>'RequestAdjustOrder[address]','value'=>$request_adjust_order->address,'tips','id','init_value',],
        ['type'=>'text','label_name'=>'备注','name'=>'RequestAdjustOrder[remark]','value'=>$request_adjust_order->remark,'tips','id','init_value',],
      ]
]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$request_adjust_order,'model_name'=>$this->context->id,'form_name'=>'order_form']); ?>