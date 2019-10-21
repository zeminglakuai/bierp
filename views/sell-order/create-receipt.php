<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\config\sys_config;
use app\common\config\lang_value_config;
use app\common\widgets\OrderForm;

?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<?= OrderForm::widget(['form_data'=>[
                    ['type'=>'text','label_name'=>'销售单号','name'=>'Receipt[order_id]','value'=>$receipt->order_id?$receipt->order_id:$id,'tips','id'=>'order_id','init_value',],
                    ['type'=>'text','label_name'=>'收款金额','name'=>'Receipt[fee]','value'=>$receipt->fee,'tips','id'],
                                      ['type'=>'data_picker','label_name'=>'收款时间','name'=>'Receipt[remain_time]','value'=>$receipt->remain_time?date('Y-m-d H:i:s',$receipt->remain_time):'','tips'=>'不添加收款时间，则按照添加时间作为收款时间','id'],
                                      ['type'=>'text','label_name'=>'备注','name'=>'Receipt[remark]','value'=>$receipt->remark,'tips','id','init_value',],
                                      
                                  ]
                      ]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$receipt,'model_name'=>"sell-order",'form_name'=>'order_form','url'=>$receipt?Url::to(['/sell-order/update-receipt']):Url::to(['/sell-order/insert-receipt'])]); ?>
<script type="text/javascript">
  $(document).ready(function() {
      $("#order_id").tokenInput("<?= Url::to(['/sell-order/token-sell-order'])?>",
        {
          theme:'facebook', 
          hintText:'请输入要搜索的关键字',
          tokenLimit:1,
          <?php 
            $sell_order_id = $receipt->order_id?$receipt->order_id:$id;
            $sell_order_sn = $receipt->relate_order_sn;
            if ($sell_order_id && !$sell_order_sn) {
              $sell_order = app\common\models\SellOrder::findone($sell_order_id);
              if ($sell_order) {
                $sell_order_sn = $sell_order->order_sn;
              }
            }
          ?>
          <?php if($sell_order_id >= 1){ ?>prePopulate:[{id:'<?= $sell_order_id ?>',name:'<?= $sell_order_sn ?>'}],<?php }?>
        }
      );
  });
</script>