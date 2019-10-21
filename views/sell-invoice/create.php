<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\config\sys_config;
use app\common\config\lang_value_config;
use app\common\widgets\OrderForm;
use app\common\models\DictionaryValue;

?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<?= OrderForm::widget(['form_data'=>[
                    ['type'=>'text','label_name'=>'销售单号','name'=>'SellInvoice[order_id]','value'=>$sell_invoice->order_id,'tips','id'=>'order_id','init_value',],
                    ['type'=>'text','label_name'=>'销售单金额','name'=>'SellInvoice[order_total]','value'=>$sell_invoice->order_total,'tips','id'=>'order_total','init_value',],
                    ['type'=>'text','label_name'=>'客户','name'=>'SellInvoice[custom_id]','value'=>$sell_invoice->custom_id,'tips','id'=>'custom_id','init_value',],
                    ['type'=>'text','label_name'=>'税号','name'=>'SellInvoice[tax_code]','value'=>$sell_invoice->tax_code,'tips','id'=>'tax_code','init_value',],
                    ['type'=>'text','label_name'=>'抬头','name'=>'SellInvoice[title]','value'=>$sell_invoice->title,'tips','id'=>'title','init_value',],
                    ['type'=>'text','label_name'=>'发票金额','name'=>'SellInvoice[fee]','value'=>$sell_invoice->fee,'tips','id'=>'fee'],
                    ['type'=>'select','label_name'=>'发票类型','name'=>'SellInvoice[invoice_type]','value'=>$sell_invoice->invoice_type,'tips','id','init_value'=>ArrayHelper::map(DictionaryValue::getValueList(10),'dictionary_value','dictionary_value')],
                    ['type'=>'text','label_name'=>'发票税率','name'=>'SellInvoice[invoice_rate]','value'=>$sell_invoice->invoice_rate,'tips'=>'填写百分率"%"','id'=>'invoice_rate'],
                    ['type'=>'text','label_name'=>'发票税额','name'=>'SellInvoice[invoice_rate_fee]','value'=>$sell_invoice->invoice_rate_fee,'tips'=>'该数值由发票金额*发票税率自动生成','id'=>'invoice_rate_fee'],
                    ['type'=>'text','label_name'=>'发票编号','name'=>'SellInvoice[invoice_code]','value'=>$sell_invoice->invoice_code,'tips','id'],
                    ['type'=>'text','label_name'=>'发票号码','name'=>'SellInvoice[invoice_number]','value'=>$sell_invoice->invoice_number,'tips','id'],
                    ['type'=>'text','label_name'=>'发票内容','name'=>'SellInvoice[invioce_content]','value'=>$sell_invoice->invioce_content,'tips','id'],
                    ['type'=>'data_picker','label_name'=>'提醒开具时间','name'=>'SellInvoice[remain_time]','value'=>$sell_invoice->remain_time?date('Y-m-d H:i:s',$sell_invoice->remain_time):'','tips'=>'不添加提醒开具时间，则按照复核时间作为开具时间','id'],
                    ['type'=>'data_picker','label_name'=>'开具时间','name'=>'SellInvoice[invoice_time]','value'=>$sell_invoice->invoice_time?date('Y-m-d H:i:s',$sell_invoice->invoice_time):''],
                    ['type'=>'text','label_name'=>'领用人','name'=>'SellInvoice[receive_user_id]','value'=>$sell_invoice->receive_user_id,'tips','id'=>'receive_user_id'],
                    ['type'=>'text','label_name'=>'备注','name'=>'SellInvoice[remark]','value'=>$sell_invoice->remark,'tips','id','init_value',],
                    ]
                  ]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$sell_invoice,'model_name'=>"sell-invoice",'form_name'=>'order_form']); ?>
<script type="text/javascript">
  $(document).ready(function() {
      $("#order_id").tokenInput("<?= Url::to(['/sell-invoice/token-sell-order'])?>",
        {
          theme:'facebook', 
          hintText:'请输入要搜索的关键字',
          tokenLimit:1,
          <?php 
            $sell_order_id = $sell_invoice->order_id;
            $sell_order_sn = $sell_invoice->relate_order_sn;
            if ($sell_order_id && !$sell_order_sn) {
              $sell_order = app\common\models\SellOrder::findone($sell_order_id);
              if ($sell_order) {
                $sell_order_sn = $sell_order->order_sn;
              }
            }
          ?>
          <?php if($sell_order_id >= 1){ ?>prePopulate:[{id:'<?= $sell_order_id ?>',name:'<?= $sell_order_sn ?>'}],<?php }?>
          onAdd: function (item) {
            $("#custom_id").tokenInput("add", {id: item.custom_id, name: item.custom_name});
            
          },
          onDelete: function (item) {
            $("#custom_id").tokenInput("clear");
          },
        }
      );

      $("#receive_user_id").tokenInput("<?= Url::to(['/sell-invoice/token-user-search'])?>",
        {
          theme:'facebook', 
          hintText:'请输入要搜索的关键字',
          tokenLimit:1,
          <?php 
            $admin_id = $sell_invoice->receive_user_id;
            $admin_name = $sell_invoice->receive_user_name;
            if ($admin_id && !$admin_name) {
              $admin = app\common\models\Admin::findone($admin_id);
              if ($admin) {
                $admin_name = $admin->admin_name;
              }
            }
          ?>
          <?php if($admin_id >= 1){ ?>prePopulate:[{id:'<?= $admin_id ?>',name:'<?= $admin_name ?>'}],<?php }?>
        }
      );

      $("#custom_id").tokenInput("<?= Url::to(['/sell-invoice/token-custom-search'])?>",
        {
          theme:'facebook', 
          hintText:'请输入要搜索的关键字',
          tokenLimit:1,
          <?php 
            $custom_id = $sell_invoice->custom_id;
            $custom_name = $sell_invoice->custom_name;
            if ($custom_id && !$custom_name) {
              $custom = app\common\models\Custom::findone($custom_id);
              if ($custom) {
                $custom_name = $custom->custom_name;
              }
            }
          ?>
          <?php if($custom_id >= 1){ ?>prePopulate:[{id:'<?= $custom_id ?>',name:'<?= $custom_name ?>'}],<?php }?>
        }
      );

      $("#fee").keyup(function(){
        var fee = $(this).val();
        var rate = $("#invoice_rate").val();
        if (fee && rate) {
          var rate_fee = Math.round(fee*rate/100,2);
          var rate = $("#invoice_rate_fee").val(rate_fee);
        };
      });

      $("#invoice_rate").keyup(function(){
        var fee = $("#fee").val();
        var rate = $(this).val();
        if (fee && rate) {
          var rate_fee = Math.round(fee*rate/100,2);
          var rate = $("#invoice_rate_fee").val(rate_fee);
        };
      });

  });

</script>

<?php
  if ($sell_invoice) {
?>
<?= app\common\widgets\OperateBar::widget([
      'admit_process'=>['controller'=>$this->context->id,'url'=>Url::to(['/'.$this->context->id.'/admit','id'=>$sell_invoice->id]),'model'=>$sell_invoice,'status_label'=>'sell_invoice_status'],
      ])
?>
  
<?php
  }
?>