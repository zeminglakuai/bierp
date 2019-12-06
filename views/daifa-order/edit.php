<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\Depart;
use app\common\models\Role;
use app\common\config\sys_config;
use app\common\models\DictionaryValue;
?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>

<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<?= app\common\widgets\OrderForm::widget(['form_data'=>[
    ['type'=>'select','label_name'=>'配送方式','name'=>'DaifaOrder[shipping_method]','value'=>$daifa_order->shipping_method,'tips','id','init_value'=>ArrayHelper::map(DictionaryValue::getValueList(5),'dictionary_value','dictionary_value'),],
    ['type'=>'text','label_name'=>'物料费用','name'=>'DaifaOrder[materiel_fee]','value'=>$daifa_order->materiel_fee,'tips','id','init_value',],
    ['type'=>'text','label_name'=>'运输费用','name'=>'DaifaOrder[shipping_fee]','value'=>$daifa_order->shipping_fee,'tips','id','init_value',],
    ['type'=>'text','label_name'=>'发货单号','name'=>'DaifaOrder[shipping_code]','value'=>$daifa_order->shipping_code,'tips','id','init_value',],
    ['type'=>'text','label_name'=>'备注','name'=>'DaifaOrder[remark]','value'=>$daifa_order->remark,'tips','id','init_value',],
]
]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$daifa_order,'model_name'=>"daifa-order",'form_name'=>'order_form']); ?>
<script type="text/javascript">
    $("#custom").tokenInput("<?= Url::to(['/custom-order/token-custom-search'])?>",
        {
            theme:'facebook',
            hintText:'请输入要搜索的关键字',
            tokenLimit:1
        }
    );

</script>
