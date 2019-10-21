<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\Depart;
use app\common\models\Role;
use app\common\config\sys_config;
?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>

<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>

<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<?= app\common\widgets\OrderForm::widget(['form_data'=>[['type'=>'text','label_name'=>'单据名称','name'=>'CustomOrder[order_name]','value'=>$custom_order->order_name,'tips','id','init_value',],
                                                        ['type'=>'text','label_name'=>'客户名称','name'=>'CustomOrder[custom_id]','value'=>$custom_order->custom_id,'tips','id'=>'custom','init_value',],
                                                        ['type'=>'text','label_name'=>'备注','name'=>'CustomOrder[remark]','value'=>$custom_order->remark,'tips','id','init_value',],
                                                      ]
                                        ]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$custom_order,'model_name'=>"custom-order",'form_name'=>'order_form']); ?>
<script type="text/javascript">
$("#custom").tokenInput("<?= Url::to(['/custom-order/token-custom-search'])?>",
  {
    theme:'facebook', 
    hintText:'请输入要搜索的关键字',
    tokenLimit:1
    <?php if($custom_order->custom_id >= 1){ ?>,prePopulate:[{id:'<?= $custom_order->custom_id?>',name:'<?= $custom_order->custom_name?>'}],<?php }?>
  }
);
  
</script>
 