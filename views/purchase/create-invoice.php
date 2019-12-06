<?php

use app\common\models\DictionaryValue;
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
                    ['type'=>'text','label_name'=>'采购单号','name'=>'AcceptInvoice[order_id]','value'=>$accept_invoice->order_id?$accept_invoice->order_id:$id,'tips','id'=>'order_id','init_value',],
                    ['type'=>'text','label_name'=>'供货商','name'=>'AcceptInvoice[supplier_id]','value'=>$accept_invoice->supplier_id,'tips','id'=>'supplier_id','init_value',],
    ['type'=>'select','label_name'=>'发票类型','name'=>'AcceptInvoice[invoice_type]','value'=>$accept_invoice->invoice_type,'tips','id','init_value'=>ArrayHelper::map(DictionaryValue::getValueList(10),'dictionary_value','dictionary_value')],

    ['type'=>'text','label_name'=>'发票金额','name'=>'AcceptInvoice[fee]','value'=>$accept_invoice->fee,'tips','id'],
                    ['type'=>'data_picker','label_name'=>'提醒收取时间','name'=>'AcceptInvoice[remain_time]','value'=>$accept_invoice->remain_time?date('Y-m-d H:i:s',$accept_invoice->remain_time):'','tips'=>'不添加提醒收取时间，则按照添加时间作为提醒收取时间','id'],
                    ['type'=>'text','label_name'=>'备注','name'=>'AcceptInvoice[remark]','value'=>$accept_invoice->remark,'tips','id','init_value',],
                          ]
                      ]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$accept_invoice,
                                        'model_name'=>"purchase",
                                        'form_name'=>'order_form',
                                        'defined_function'=>true
                                        ]); ?>
<script type="text/javascript">
  $(document).ready(function() {
      $("#order_id").tokenInput("<?= Url::to(['/purchase/token-purchase'])?>",
        {
          theme:'facebook', 
          hintText:'请输入要搜索的关键字',
          tokenLimit:1,
          <?php 
            $purchase_id = $accept_invoice->order_id?$accept_invoice->order_id:$id;
            $purchase_order_sn = $accept_invoice->relate_order_sn;
            if ($purchase_id && !$purchase_order_sn) {
              $Purchase = app\common\models\Purchase::findone($purchase_id);
              if ($Purchase) {
                $purchase_order_sn = $Purchase->order_sn;
              }
            }
          ?>
          <?php if($purchase_id >= 1){ ?>prePopulate:[{id:'<?= $purchase_id ?>',name:'<?= $purchase_order_sn ?>'}],<?php }?>
          onAdd: function (item) {
            $("#supplier_id").tokenInput("add", {id: item.supplier_id, name: item.supplier_name});
          },
          onDelete: function (item) {
            $("#supplier_id").tokenInput("clear");
          },
        }
      );

      $("#supplier_id").tokenInput("<?= Url::to(['/purchase/search-supplier'])?>",
        {
          theme:'facebook', 
          hintText:'请输入要搜索的关键字',
          tokenLimit:1
          <?php
            $supplier_id = $accept_invoice->supplier_id?$accept_invoice->supplier_id:($Purchase->supplier_id?$Purchase->supplier_id:0);
            $supplier_name = $accept_invoice->supplier_name?$accept_invoice->supplier_name:($Purchase->supplier_name?$Purchase->supplier_name:0);
            if ($supplier_id && !$supplier_name) {
              $Supplier = app\common\models\Supplier::findone($supplier_id);
              if ($Supplier) {
                $supplier_name = $Supplier->supplier_name;
              }
            }
          ?>
          <?php if($supplier_id >= 1){ ?>,prePopulate:[{id:'<?= $supplier_id ?>',name:'<?= $supplier_name ?>'}]<?php }?>
        }
      );

  <?php if(isset($accept_invoice->id)){
  ?>
      $("#edit_purchase").click(function(){
        var index = parent.layer.getFrameIndex(window.name);
        var formData = new FormData($("#order_form")[0]);
        var update_url = create_url('<?= Url::to(["/purchase/update-invoice","id"=>$accept_invoice->order_id?$accept_invoice->order_id:$id])?>');
        $.ajax({  
              url: update_url+'invoice_id='+<?= $accept_invoice->id?> ,  
              type: 'POST',  
              data: formData,
              dataType:'json',
              async: false,  
              cache: false,  
              contentType: false,
              processData: false,
              success: function (result) {
                if(result.error == 1){
                  parent.layer.msg(result.message);
                  parent.location.reload();
                }else{
                  layer.msg(result.message);
                } 
              },  
              error: function (result) {  
                  layer.msg('发生错误');
              }
        });
      });
  <?php
  }else{
  ?>
      $("#add_purchase").click(function(){
          var formData = new FormData($("#order_form")[0]);
           $.ajax({  
                url: '<?= Url::to(["/purchase/insert-invoice","id"=>$id])?>' ,  
                type: 'POST',  
                data: formData,
                dataType:'json',
                async: false,  
                cache: false,  
                contentType: false,  
                processData: false,  
                success: function (result) {  
                  if(result.error == 1){
                    parent.layer.msg(result.message);
                    parent.location.reload();
                  }else{
                    layer.msg(result.message);
                  } 
                },
                error: function (result) {  
                    layer.msg('发生错误');
                }
           });
      });
  <?php
  }
  ?>

  });
</script>