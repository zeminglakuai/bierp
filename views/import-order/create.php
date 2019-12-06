<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\Depart;
use app\common\models\Role;
use app\common\models\Store;
use app\common\config\sys_config;
use app\common\models\DictionaryValue;
?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>

<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<?= app\common\widgets\OrderForm::widget(['form_data'=>[
        ['type'=>'text','label_name'=>'供货商','name'=>'ImportOrder[supplier_id]','value'=>$import_order->supplier_id,'tips','id'=>'supplier','init_value',],
        ['type'=>'select','label_name'=>'收货仓库','name'=>'ImportOrder[store_id]','value'=>$import_order->store_id,'tips','id'=>'supplier','init_value'=>ArrayHelper::map(Store::get_store(), 'id', 'store_name'),],
        ['type'=>'text','label_name'=>'备注','name'=>'ImportOrder[remark]','value'=>$import_order->remark,'tips','id','init_value',],
      ]
]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$import_order,'model_name'=>"import-order",'form_name'=>'order_form']); ?>
<script type="text/javascript">
$("#supplier").tokenInput("<?= Url::to(['/import-order/search-supplier'])?>",
  {
    theme:'facebook', 
    hintText:'请输入要搜索的关键字',
    tokenLimit:1
    <?php if($import_order->supplier_id >= 1){ ?>,prePopulate:[{id:'<?= $import_order->supplier_id?>',name:'<?= $import_order->supplier_name?>'}]<?php }?>
  }
);
  
</script>
 