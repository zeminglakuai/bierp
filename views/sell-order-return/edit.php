<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\Depart;
use app\common\models\Role;
use app\common\config\sys_config;
use app\common\models\Store;

?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>


<?= app\common\widgets\OrderForm::widget(['form_data'=>[
                            ['type'=>'select','label_name'=>'收货仓库','name'=>'SellOrderReturn[store_id]','value'=>$sell_order_return->store_id,'tips','id','init_value'=>ArrayHelper::map(Store::get_store(),'id','store_name'),],
                            ['type'=>'text','label_name'=>'退货原因','name'=>'SellOrderReturn[return_reason]','value'=>$sell_order_return->return_reason,'tips','id','init_value',],
                            ['type'=>'text','label_name'=>'收货人','name'=>'SellOrderReturn[consignee]','value'=>$sell_order_return->consignee,'tips','id','init_value',],
                            ['type'=>'text','label_name'=>'收货人手机','name'=>'SellOrderReturn[tel]','value'=>$sell_order_return->tel,'tips','id','init_value',],
                            ['type'=>'text','label_name'=>'收货人地址','name'=>'SellOrderReturn[address]','value'=>$sell_order_return->address,'tips','id','init_value',],
                            ['type'=>'text','label_name'=>'备注','name'=>'SellOrderReturn[remark]','value'=>$sell_order_return->remark,'tips','id','init_value',],
                             ]
                            ]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$sell_order_return,'model_name'=>"sell-order-return",'form_name'=>'order_form']); ?>
