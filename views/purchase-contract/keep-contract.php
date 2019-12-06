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

<?= app\common\widgets\OrderForm::widget(['form_data'=>[['type'=>'text','label_name'=>'保存合同人','name'=>'Contract[keeper_user_id]','value'=>$contract->keeper_user_id,'tips','id'=>'keeper_user_id','init_value',],
                                                      ]
                                        ]);
?>
<?= app\common\widgets\Submit::widget(['model'=>$contract,'model_name'=>"sell-contract",'form_name'=>'order_form','url'=>Url::to(['sell-contract/act-keep-contract','id'=>$id])]); ?>
<script type="text/javascript">
  $("#keeper_user_id").tokenInput("<?= Url::to(['/sell-contract/token-user-search'])?>",
    {
      theme:'facebook', 
      hintText:'请输入要搜索的关键字',
      tokenLimit:1
      <?php if($contract->keeper_user_id >= 1){ ?>,prePopulate:[{id:'<?= $contract->keeper_user_id?>',name:'<?= $contract->keeper_user_name?>'}],<?php }?>
    }
  );
</script>