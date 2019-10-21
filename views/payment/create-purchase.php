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
					          ['type'=>'text','label_name'=>'采购单号','name'=>'Payment[order_id]','value'=>$payment->order_id,'tips','id'=>'order_id','init_value',],

    ['type'=>'text','label_name'=>'供货商','name'=>'Payment[supplier_id]','value'=>$payment->supplier_id,'tips','id'=>'supplier_id','init_value',],
                    ['type'=>'text','label_name'=>'付款金额','name'=>'Payment[fee]','value'=>$payment->fee,'tips','id'],
                    ['type'=>'data_picker','label_name'=>'提醒收款时间','name'=>'Payment[remain_time]','value'=>$payment->remain_time?date('Y-m-d H:i:s',$payment->remain_time):'','tips'=>'不添加提醒付款时间，则按照添加时间作为付款时间','id'],
                    ['type'=>'select','label_name'=>'付款方式','name'=>'Payment[receipt_method]','value'=>$payment->receipt_method,'tips','init_value'=>ArrayHelper::map(DictionaryValue::getValueList(4),'dictionary_value','dictionary_value'),],
                    ['type'=>'select','label_name'=>'收款银行','name'=>'Payment[receipt_bank_name]','value'=>$payment->receipt_bank_name,'init_value'=>ArrayHelper::map(DictionaryValue::getValueList(9),'dictionary_value','dictionary_value'),],
                    ['type'=>'text','label_name'=>'收款银行开户名','name'=>'Payment[receipt_bank_account]','value'=>$payment->receipt_bank_account],
                    ['type'=>'text','label_name'=>'收款银行账号','name'=>'Payment[receipt_bank_code]','value'=>$payment->receipt_bank_code],
                    ['type'=>'select','label_name'=>'付款银行','name'=>'Payment[pay_bank_name]','value'=>$payment->pay_bank_name,'init_value'=>ArrayHelper::map(DictionaryValue::getValueList(9),'dictionary_value','dictionary_value'),],
                    ['type'=>'text','label_name'=>'付款银行开户名','name'=>'Payment[pay_bank_account]','value'=>$payment->pay_bank_account],
                    ['type'=>'text','label_name'=>'付款银行账号','name'=>'Payment[pay_bank_code]','value'=>$payment->pay_bank_code],
                    ['type'=>'text','label_name'=>'其他付款信息','name'=>'Payment[pay_other]','value'=>$payment->pay_other],
                    ['type'=>'data_picker','label_name'=>'付款时间','name'=>'Payment[pay_time]','value'=>$payment->pay_time?date('Y-m-d H:i:s',$payment->pay_time):''],
                    ['type'=>'text','label_name'=>'备注','name'=>'Payment[remark]','value'=>$payment->remark,'tips','id','init_value',],
                    ]
	                ]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$payment,'model_name'=>"payment",'form_name'=>'order_form']); ?>
<script type="text/javascript">
  $(document).ready(function() {
      $("#order_id").tokenInput("<?= Url::to(['/payment/token-purchase'])?>",
        {
          theme:'facebook', 
          hintText:'请输入要搜索的关键字',
          tokenLimit:1,
          <?php 
          	$purchase_id = $payment->order_id;
          	$purchase_sn = $payment->relate_order_sn;
          	if ($purchase_id && !$purchase_sn) {
          		$purchase = app\common\models\Purchase::findone($purchase_id);
          		if ($purchase) {
          			$purchase_sn = $purchase->order_sn;
          		}
          	}
          ?>
          <?php if($purchase_id >= 1){ ?>prePopulate:[{id:'<?= $purchase_id ?>',name:'<?= $purchase_sn ?>'}],<?php }?>
          onAdd: function (item) {
            $("#supplier_id").tokenInput("add", {id: item.supplier_id, name: item.supplier_name});
          },
          onDelete: function (item) {
            $("#custom_id").tokenInput("clear");
          },
        }
      );

      $("#supplier_id").tokenInput("<?= Url::to(['/payment/search-supplier'])?>",
        {
          theme:'facebook', 
          hintText:'请输入要搜索的关键字',
          tokenLimit:1,
          <?php 
            $supplier_id = $payment->supplier_id;
            $supplier_name = $payment->supplier_name;
            if ($supplier_id && !$supplier_name) {
              $supplier = app\common\models\Supplier::findone($supplier_id);
              if ($supplier) {
                $supplier_name = $supplier->supplier_name;
              }
            }
          ?>
          <?php if($supplier_id >= 1){ ?>prePopulate:[{id:'<?= $supplier_id ?>',name:'<?= $supplier_name ?>'}],<?php }?>
        }
      );
  });

</script>

<?php
  if ($payment) {
?>
<?= app\common\widgets\OperateBar::widget([
      'admit_process'=>['controller'=>$this->context->id,'url'=>Url::to(['/'.$this->context->id.'/admit','id'=>$payment->id]),'model'=>$payment,'status_label'=>'payment_status'],
      ])
?>
  
<?php
  }
?>