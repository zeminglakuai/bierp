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
                                          ['type'=>'text','label_name'=>'合同名称','name'=>'Contract[contract_name]','value'=>$contract->contract_name],
                                          ['type'=>'text','label_name'=>'采购单据','name'=>'Contract[order_id]','value'=>$contract->order_id,'id'=>'purchase'],
                                          ['type'=>'text','label_name'=>'合同模板','name'=>'Contract[template_id]','value'=>$contract->contract_name,'id'=>'contract_template'],
                                          ['type'=>'text','label_name'=>'备注','name'=>'Contract[remark]','value'=>$contract->remark],
                                                      ]
                                        ]);
?>



<?= app\common\widgets\Submit::widget(['model'=>$contract,'model_name'=>"purchase-contract",'form_name'=>'order_form']); ?>
<script type="text/javascript">
 
$("#contract_template").tokenInput("<?= Url::to(['/purchase-contract/contract-search'])?>",
  {
    theme:'facebook', 
    hintText:'请输入要搜索的关键字',
    tokenLimit:1
  }
);

$("#purchase").tokenInput("<?= Url::to(['/purchase-contract/token-purchase'])?>",
  {
    theme:'facebook', 
    hintText:'请输入要搜索的关键字',
    tokenLimit:1
  }
);


</script>
 