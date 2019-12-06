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
										['type'=>'text','label_name'=>'销售单号','name'=>'Receipt[order_id]','value'=>$receipt->order_id,'tips','id'=>'order_id','init_value',],
										['type'=>'text','label_name'=>'客户','name'=>'Receipt[custom_id]','value'=>$receipt->custom_id,'tips','id'=>'custom_id','init_value',],
                    ['type'=>'text','label_name'=>'收款金额','name'=>'Receipt[fee]','value'=>$receipt->fee,'tips','id'],
                    ['type'=>'data_picker','label_name'=>'提醒收款时间','name'=>'Receipt[remain_time]','value'=>$receipt->remain_time?date('Y-m-d H:i:s',$receipt->remain_time):'','tips'=>'不添加提醒收款时间，则按照添加时间作为收款时间','id'],
                    ['type'=>'select','label_name'=>'收款方式','name'=>'Receipt[receipt_method]','value'=>$receipt->receipt_method,'tips','init_value'=>ArrayHelper::map(DictionaryValue::getValueList(4),'dictionary_value','dictionary_value'),],
                    ['type'=>'select','label_name'=>'收款银行','name'=>'Receipt[receipt_bank_name]','value'=>$receipt->receipt_bank_name,'init_value'=>ArrayHelper::map(DictionaryValue::getValueList(9),'dictionary_value','dictionary_value'),],
                    ['type'=>'text','label_name'=>'收款银行开户名','name'=>'Receipt[receipt_bank_account]','value'=>$receipt->receipt_bank_account],
                    ['type'=>'text','label_name'=>'收款银行账号','name'=>'Receipt[receipt_bank_code]','value'=>$receipt->receipt_bank_code],
                    ['type'=>'select','label_name'=>'付款银行','name'=>'Receipt[pay_bank_name]','value'=>$receipt->pay_bank_name,'init_value'=>ArrayHelper::map(DictionaryValue::getValueList(9),'dictionary_value','dictionary_value'),],
                    ['type'=>'text','label_name'=>'付款银行开户名','name'=>'Receipt[pay_bank_account]','value'=>$receipt->pay_bank_account],
                    ['type'=>'text','label_name'=>'付款银行账号','name'=>'Receipt[pay_bank_code]','value'=>$receipt->pay_bank_code],
                    ['type'=>'text','label_name'=>'其他付款信息','name'=>'Receipt[pay_ohter]','value'=>$receipt->pay_ohter],
                    ['type'=>'data_picker','label_name'=>'收款时间','name'=>'Receipt[receipt_time]','value'=>$receipt->receipt_time?date('Y-m-d H:i:s',$receipt->receipt_time):''],
                    ['type'=>'text','label_name'=>'备注','name'=>'Receipt[remark]','value'=>$receipt->remark,'tips','id','init_value',],
                    ]
	                ]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$receipt,'model_name'=>"receipt",'form_name'=>'order_form']); ?>
<script type="text/javascript">
  $(document).ready(function() {
      $("#order_id").tokenInput("<?= Url::to(['/receipt/token-sell-order'])?>",
        {
          theme:'facebook', 
          hintText:'请输入要搜索的关键字',
          tokenLimit:1,
          <?php 
          	$sell_order_id = $receipt->order_id;
          	$sell_order_sn = $receipt->relate_order_sn;
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

      $("#custom_id").tokenInput("<?= Url::to(['/receipt/token-custom-search'])?>",
        {
          theme:'facebook', 
          hintText:'请输入要搜索的关键字',
          tokenLimit:1,
          <?php 
            $custom_id = $receipt->custom_id;
            $custom_name = $receipt->custom_name;
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

  });

</script>

<?php
  if ($receipt) {
?>
<?= app\common\widgets\OperateBar::widget([
      'admit_process'=>['controller'=>$this->context->id,'url'=>Url::to(['/'.$this->context->id.'/admit','id'=>$receipt->id]),'model'=>$receipt,'status_label'=>'receipt_status'],
      ])
?>
  
<?php
  }
?>