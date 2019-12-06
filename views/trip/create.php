<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\Depart;
use app\common\models\Role;
use app\common\models\Store;
use app\common\models\DictionaryValue;
use app\common\config\sys_config;

?>

<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>


<?= app\common\widgets\OrderForm::widget(['form_data'=>[
		['type'=>'token_input','label_name'=>'出差人','table_name'=>'admin','name_name'=>'real_name','name'=>'Trip[triper_id]','id'=>'triper_id','value'=>$trip->triper_id,'label'=>'triper_id','token_url'=>Url::to(['/'.$this->context->id.'/token-user-search'])],
		['type'=>'text','label_name'=>'原因','name'=>'Trip[reason]','value'=>$trip->reason,'tips','id','init_value',],
        ['type'=>'text','label_name'=>'差旅地点','name'=>'Trip[trip_point]','value'=>$trip->trip_point,'tips','id','init_value',],
		['type'=>'data_picker','label_name'=>'差旅开始时间','name'=>'Trip[start_time]','value'=>date('Y-m-d H:i',$trip->start_time),'tips','id','init_value',],
		['type'=>'data_picker','label_name'=>'差旅结束时间','name'=>'Trip[end_time]','value'=>date('Y-m-d H:i',$trip->end_time),'tips','id','init_value',],
        ['type'=>'data_picker','label_name'=>'住宿开始时间','name'=>'Trip[live_start_time]','value'=>date('Y-m-d H:i',$trip->live_start_time),'tips','id','init_value',],
		['type'=>'data_picker','label_name'=>'住宿结束时间','name'=>'Trip[live_end_time]','value'=>date('Y-m-d H:i',$trip->live_end_time),'tips','id','init_value',],
		['type'=>'text','label_name'=>'交通工具','name'=>'Trip[vehicle]','value'=>$trip->vehicle,'tips','id','init_value',],
		['type'=>'text','label_name'=>'酒店','name'=>'Trip[hotel]','value'=>$trip->hotel,'tips','id','init_value',],
        ['type'=>'text','label_name'=>'备注','name'=>'Trip[remark]','value'=>$trip->remark,'tips','id','init_value',],
      ]
]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$trip,'model_name'=>$this->context->id,'form_name'=>'order_form']); ?>

