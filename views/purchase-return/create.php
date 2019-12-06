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

<?= app\common\widgets\OrderForm::widget(['form_data'=>[['type'=>'text','label_name'=>'采购单','name'=>'PurchaseReturn[purchase_id]','value'=>$purchase_return->purchase_id,'tips','id'=>'purchase_return','init_value',],
                                                        ['type'=>'text','label_name'=>'备注','name'=>'PurchaseReturn[remark]','value'=>$purchase_return->remark,'tips','id','init_value',],
                                                      ]
                                        ]);
?>
<?= app\common\widgets\Submit::widget(['model'=>$purchase_return,'model_name'=>"purchase-return",'form_name'=>'order_form']); ?>
<script type="text/javascript">
$("#purchase_return").tokenInput("<?= Url::to(['/purchase-return/token-purchase-search'])?>",
  {
    theme:'facebook', 
    hintText:'请输入要搜索的关键字',
    tokenLimit:1
  }
);

</script>
 