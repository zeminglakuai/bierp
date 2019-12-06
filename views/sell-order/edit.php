<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\Depart;
use app\common\models\Store;
use app\common\models\Role;
use app\common\config\sys_config;
use app\common\models\DictionaryValue;

?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>

<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<?= app\common\widgets\OrderForm::widget(['form_data'=>[['type'=>'select','label_name'=>'付款方式','name'=>'SellOrder[pay_method]','value'=>$sell_order->pay_method,'tips','id','init_value'=>ArrayHelper::map(DictionaryValue::getValueList(4),'dictionary_value','dictionary_value'),],
                                                        ['type'=>'select','label_name'=>'配送方式','name'=>'SellOrder[shipping_method]','value'=>$sell_order->shipping_method,'tips','id','init_value'=>ArrayHelper::map(DictionaryValue::getValueList(5),'dictionary_value','dictionary_value'),],
                                                        ['type'=>'select','label_name'=>'发货仓库','name'=>'SellOrder[store_id]','value'=>$sell_order->store_id,'tips','id'=>'store_id','init_value'=>ArrayHelper::map(Store::get_store(),'id','store_name'),],
                                                        ['type'=>'text','label_name'=>'收货人','name'=>'SellOrder[consignee]','value'=>$sell_order->consignee,'tips','id','init_value',],
                                                        ['type'=>'text','label_name'=>'联系方式','name'=>'SellOrder[tel]','value'=>$sell_order->tel,'tips','id','init_value',],
                                                        ['type'=>'text','label_name'=>'地址','name'=>'SellOrder[address]','value'=>$sell_order->address,'tips','id','init_value',],
                                                        
                                                        ['type'=>'text','label_name'=>'备注','name'=>'SellOrder[remark]','value'=>$sell_order->remark,'tips','id','init_value',],
                                                      ]
                                        ]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$sell_order,'model_name'=>"sell-order",'form_name'=>'order_form']); ?>
<script type="text/javascript">
$("#custom").tokenInput("<?= Url::to(['/custom-order/token-custom-search'])?>",
  {
    theme:'facebook', 
    hintText:'请输入要搜索的关键字',
    tokenLimit:1
  }
);


</script>
 