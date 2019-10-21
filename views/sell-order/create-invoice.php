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
                    ['type'=>'text','label_name'=>'销售单号','name'=>'SellInvoice[order_id]','value'=>$sell_invoice->order_id?$sell_invoice->order_id:$id,'tips','id'=>'order_id','init_value',],
                    ['type'=>'text','label_name'=>'发票金额','name'=>'SellInvoice[fee]','value'=>$sell_invoice->fee?$sell_invoice->fee:$remind_fee,'tips','id'],
                    ['type'=>'data_picker','label_name'=>'收取时间','name'=>'SellInvoice[remain_time]','value'=>$sell_invoice->remain_time?date('Y-m-d H:i:s',$sell_invoice->remain_time):'','tips'=>'不添加收款时间，则按照添加时间作为收款时间','id'],
                    ['type'=>'text','label_name'=>'备注','name'=>'SellInvoice[remark]','value'=>$sell_invoice->remark,'tips','id','init_value',],
                          ]
                      ]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$sell_invoice,'model_name'=>"sell-order",'form_name'=>'order_form','url'=>$sell_invoice?Url::to(['/sell-order/update-invoice','id'=>$id]):Url::to(['/sell-order/insert-invoice','id'=>$id])]); ?>

<script type="text/javascript">
  $(document).ready(function() {
      $("#order_id").tokenInput("<?= Url::to(['/sell-order/token-sell-order'])?>",
        {
          theme:'facebook', 
          hintText:'请输入要搜索的关键字',
          tokenLimit:1,
          <?php 
            $sell_order_id = $sell_invoice->order_id?$sell_invoice->order_id:$id;
            $sell_order_sn = $sell_invoice->relate_order_sn;
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