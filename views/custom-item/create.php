<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\Depart;
use app\common\models\Role;
use app\common\models\Store;
use app\common\config\sys_config;

?>

<?= app\common\widgets\OrderForm::widget(['form_data'=>[
        ['type'=>'select','label_name'=>'相关仓库','name'=>'StockLock[store_id]','value'=>$stock_lock->store_id,'tips','id'=>'store_id','init_value'=>ArrayHelper::map(Store::get_store(), 'id', 'store_name'),],
        ['type'=>'text','label_name'=>'备注','name'=>'StockLock[remark]','value'=>$stock_lock->remark,'tips','id','init_value',],
      ]
]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$stock_lock,'model_name'=>"stock-lock",'form_name'=>'order_form']); ?>
