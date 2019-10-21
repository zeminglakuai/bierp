<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\Depart;
use app\common\models\SellOrder;
use app\common\models\Role;
use app\common\config\sys_config;
use app\common\widgets\OrderForm;

?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>

<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

 
<?= OrderForm::widget(['form_data'=>[
                    ['type'=>'token_input','table_name'=>'supplier','name'=>'AskPriceOrder[supplier_id]','id'=>'supplier_id','name_name'=>'supplier_name','label'=>'supplier_id','label_name'=>'供货商','value'=>$ask_price_order->supplier_id,'token_url'=>Url::to(['/'.$this->context->id.'/search-supplier'])],
                    ['type'=>'text','label_name'=>'区域','name'=>'AskPriceOrder[area]','value'=>$ask_price_order->area,'tips','id','init_value',],
                    ['type'=>'text','label_name'=>'备注','name'=>'AskPriceOrder[remark]','value'=>$ask_price_order->remark,'tips','id','init_value',],
                    ]
                  ]);
?>


<?= app\common\widgets\Submit::widget(['model'=>$ask_price_order,'model_name'=>$this->context->id,'form_name'=>'order_form']); ?>

