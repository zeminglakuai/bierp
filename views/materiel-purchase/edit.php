<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\Depart;
use app\common\models\Store;
use app\common\models\Role;
use app\common\config\sys_config;
use app\common\models\DictionaryValue;

?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>

<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>
 
<?= app\common\widgets\OrderForm::widget(['form_data'=>[['type'=>'select','label_name'=>'付款方式','name'=>'Purchase[pay_method]','value'=>$purchase->pay_method,'tips','id','init_value'=>ArrayHelper::map(DictionaryValue::getValueList(4),'dictionary_value','dictionary_value'),],
                                                        ['type'=>'select','label_name'=>'收货仓库','name'=>'Purchase[store_id]','value'=>$purchase->store_id,'tips','id','init_value'=>ArrayHelper::map(Store::find()->all(),'id','store_name'),],
                                                        ['type'=>'text','label_name'=>'备注','name'=>'Purchase[remark]','value'=>$purchase->remark,'tips','id','init_value',],
                                                      ]
                                        ]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$purchase,'model_name'=>"purchase",'form_name'=>'order_form']); ?>
<script type="text/javascript">
$("#custom").tokenInput("<?= Url::to(['/custom-order/token-custom-search'])?>",
  {
    theme:'facebook', 
    hintText:'请输入要搜索的关键字',
    tokenLimit:1
  }
);
  
</script>
 