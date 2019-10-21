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


<?= app\common\widgets\OrderForm::widget(['form_data'=>[['type'=>'text','label_name'=>'销售单','name'=>'SellOrderReturn[sell_order_id]','value'=>$sell_order_return->sell_order_id,'tips','id'=>'sell_order_id','init_value',],
                                                        ['type'=>'text','label_name'=>'退货原因','name'=>'SellOrderReturn[return_reason]','value'=>$sell_order_return->return_reason,'tips','id','init_value',],
                                                        ['type'=>'text','label_name'=>'备注','name'=>'SellOrderReturn[remark]','value'=>$sell_order_return->remark,'tips','id','init_value',],
                                                      ]
                                        ]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$sell_order_return,'model_name'=>"sell-order-return",'form_name'=>'order_form']); ?>
<script type="text/javascript">
$("#sell_order_id").tokenInput("<?= Url::to(['/sell-order-return/token-sell-order'])?>",
  {
    theme:'facebook', 
    hintText:'请输入要搜索的关键字',
    tokenLimit:1
  }
);
  
</script>
 