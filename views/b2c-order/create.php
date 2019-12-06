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

<?= app\common\widgets\OrderForm::widget(['form_data'=>[['type'=>'text','label_name'=>'单据名称','name'=>'B2cOrder[order_name]','value'=>$b2c_order->order_name,'tips','id','init_value',],
                                                        ['type'=>'text','label_name'=>'客户名称','name'=>'B2cOrder[custom_id]','value'=>$b2c_order->custom_id,'tips','id'=>'custom','init_value',],
                                                        ['type'=>'select','label_name'=>'配送方式','name'=>'B2cOrder[shipping_method]','value'=>$b2c_order->shipping_method,'tips','id','init_value'=>ArrayHelper::map(DictionaryValue::getValueList(7),'dictionary_value','dictionary_value'),],
                                                        ['type'=>'text','label_name'=>'备注','name'=>'B2cOrder[remark]','value'=>$b2c_order->remark,'tips','id','init_value',],
                                                      ]
                                        ]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$b2c_order,'model_name'=>"b2c-order",'form_name'=>'order_form']); ?>
<script type="text/javascript">
$("#custom").tokenInput("<?= Url::to(['/b2c-order/token-custom-search'])?>",
  {
    theme:'facebook', 
    hintText:'请输入要搜索的关键字',
    tokenLimit:1
    <?php if($b2c_order->custom_id >= 1){ ?>,prePopulate:[{id:'<?= $b2c_order->custom_id?>',name:'<?= $b2c_order->custom_name?>'}],<?php }?>
  }
);
  
</script>