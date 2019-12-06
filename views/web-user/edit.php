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

<?= app\common\widgets\OrderForm::widget(['form_data'=>[
                                                        ['type'=>'select','label_name'=>'配送方式','name'=>'ExportOrder[shipping_method]','value'=>$export_order->shipping_method,'tips','id','init_value',],
                                                        ['type'=>'text','label_name'=>'物料费用','name'=>'ExportOrder[materiel_fee]','value'=>$export_order->materiel_fee,'tips','id','init_value',],
                                                        ['type'=>'text','label_name'=>'运输费用','name'=>'ExportOrder[shipping_fee]','value'=>$export_order->shipping_fee,'tips','id','init_value',],
                                                        ['type'=>'text','label_name'=>'备注','name'=>'ExportOrder[remark]','value'=>$export_order->remark,'tips','id','init_value',],
                                                      ]
                                        ]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$export_order,'model_name'=>"export-order",'form_name'=>'order_form']); ?>
<script type="text/javascript">
$("#custom").tokenInput("<?= Url::to(['/custom-order/token-custom-search'])?>",
  {
    theme:'facebook', 
    hintText:'请输入要搜索的关键字',
    tokenLimit:1
  }
);
  
</script>
 