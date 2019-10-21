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
                    ['type'=>'text','label_name'=>'采购单号','name'=>'AcceptInvoice[order_id]','value'=>$accept_invoice->order_id,'tips','id'=>'order_id','init_value',],
                    ['type'=>'text','label_name'=>'采购单金额','name'=>'AcceptInvoice[order_total]','value'=>$accept_invoice->order_total,'tips','id'=>'order_total','init_value',],
                    ['type'=>'text','label_name'=>'供货商','name'=>'AcceptInvoice[supplier_id]','value'=>$accept_invoice->supplier_id,'tips','id'=>'supplier_id','init_value',],
                    ['type'=>'text','label_name'=>'税号','name'=>'AcceptInvoice[tax_code]','value'=>$accept_invoice->tax_code,'tips','id'=>'tax_code','init_value',],
                    ['type'=>'text','label_name'=>'抬头','name'=>'AcceptInvoice[title]','value'=>$accept_invoice->title,'tips','id'=>'title','init_value',],
                    ['type'=>'text','label_name'=>'发票金额','name'=>'AcceptInvoice[fee]','value'=>$accept_invoice->fee,'tips','id'=>'fee'],
                    ['type'=>'select','label_name'=>'发票类型','name'=>'AcceptInvoice[invoice_type]','value'=>$accept_invoice->invoice_type,'tips','id','init_value'=>ArrayHelper::map(DictionaryValue::getValueList(10),'dictionary_value','dictionary_value')],
                    ['type'=>'text','label_name'=>'发票税率','name'=>'AcceptInvoice[invoice_rate]','value'=>$accept_invoice->invoice_rate,'tips'=>'填写百分率"%"','id'=>'invoice_rate'],
                    ['type'=>'text','label_name'=>'发票税额','name'=>'AcceptInvoice[invoice_rate_fee]','value'=>$accept_invoice->invoice_rate_fee,'tips'=>'该数值由发票金额*发票税率自动生成','id'=>'invoice_rate_fee'],
                    ['type'=>'text','label_name'=>'发票编号','name'=>'AcceptInvoice[invoice_code]','value'=>$accept_invoice->invoice_code,'tips','id'],
                    ['type'=>'text','label_name'=>'发票内容','name'=>'AcceptInvoice[invioce_content]','value'=>$accept_invoice->invioce_content,'tips','id'],
                    ['type'=>'data_picker','label_name'=>'提醒收取时间','name'=>'AcceptInvoice[remain_time]','value'=>$accept_invoice->remain_time?date('Y-m-d H:i:s',$accept_invoice->remain_time):'','tips'=>'不添加提醒收取时间，则按照复核时间作为提醒收取时间','id'],
                    ['type'=>'data_picker','label_name'=>'开具时间','name'=>'AcceptInvoice[invoice_time]','value'=>$accept_invoice->invoice_time?date('Y-m-d H:i:s',$accept_invoice->invoice_time):''],
                    ['type'=>'text','label_name'=>'收票人','name'=>'AcceptInvoice[accept_user_id]','value'=>$accept_invoice->accept_user_id,'tips','id'=>'accept_user_id'],
                    ['type'=>'text','label_name'=>'备注','name'=>'AcceptInvoice[remark]','value'=>$accept_invoice->remark,'tips','id','init_value',],
                    ]
                  ]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$accept_invoice,'model_name'=>"accept-invoice",'form_name'=>'order_form']); ?>
<script type="text/javascript">
  $(document).ready(function() {
      $("#order_id").tokenInput("<?= Url::to(['/sell-invoice/token-purchase'])?>",
        {
          theme:'facebook', 
          hintText:'请输入要搜索的关键字',
          tokenLimit:1,
          <?php 
            $purchase_id = $accept_invoice->order_id;
            $purchase_sn = $accept_invoice->relate_order_sn;
            if ($purchase_id && !$purchase_sn) {
              $sell_order = app\common\models\SellOrder::findone($purchase_id);
              if ($sell_order) {
                $purchase_sn = $sell_order->order_sn;
              }
            }
          ?>
          <?php if($purchase_id >= 1){ ?>prePopulate:[{id:'<?= $purchase_id ?>',name:'<?= $purchase_sn ?>'}],<?php }?>
          onAdd: function (item) {
            $("#supplier_id").tokenInput("add", {id: item.supplier_id, name: item.supplier_name});
            $("#order_total").val(item.total);

            //得到供货商税号 和 title
            $.get("<?= Url::to(['/accept-invoice/search-supplier'])?>",{id:item.supplier_id},function(result){
              if (result.id) {
                $("#tax_code").val(result.tax_code);
                $("#title").val(result.title);
              };
            },'json')

          },
          onDelete: function (item) {
            $("#supplier_id").tokenInput("clear");
          },
        }
      );

      $("#accept_user_id").tokenInput("<?= Url::to(['/accept-invoice/token-user-search'])?>",
        {
          theme:'facebook', 
          hintText:'请输入要搜索的关键字',
          tokenLimit:1,
          <?php 
            $admin_id = $accept_invoice->accept_user_id;
            $admin_name = $accept_invoice->accept_user_name;
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

      $("#supplier_id").tokenInput("<?= Url::to(['/accept-invoice/search-supplier'])?>",
        {
          theme:'facebook', 
          hintText:'请输入要搜索的关键字',
          tokenLimit:1,
          <?php 
            $supplier_id = $accept_invoice->supplier_id;
            $supplier_name = $accept_invoice->supplier_name;
            if ($supplier_id && !$supplier_name) {
              $Supplier = app\common\models\Supplier::findone($supplier_id);
              if ($Supplier) {
                $supplier_name = $Supplier->supplier_name;
              }
            }
          ?>
          <?php if($supplier_id >= 1){ ?>prePopulate:[{id:'<?= $supplier_id ?>',name:'<?= $supplier_name ?>'}],<?php }?>
          onAdd: function (item) {
                $("#tax_code").val(item.tax_code);
                $("#title").val(item.title);
          },
          onDelete: function (item) {
                $("#tax_code").val('');
                $("#title").val('');
          },
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
  if ($accept_invoice) {
?>
<?= app\common\widgets\OperateBar::widget([
      'admit_process'=>['controller'=>$this->context->id,'url'=>Url::to(['/'.$this->context->id.'/admit','id'=>$accept_invoice->id]),'model'=>$accept_invoice,'status_label'=>'accept_invoice_status'],
      ])
?>
  
<?php
  }
?>