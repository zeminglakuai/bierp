<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\config\lang_value_config;
use app\common\models\DictionaryValue;
?>

 
<?= app\common\widgets\OrderForm::widget(['form_data'=>[
		['type'=>'text','label_name'=>'固定资产名称','name'=>'AssetPurchase[asset_name]','value'=>$asset_purchase->asset_name,'tips','id'=>'asset_name','init_value','inneed'=>true,],
        ['type'=>'text','label_name'=>'型号','name'=>'AssetPurchase[asset_sn]','value'=>$asset_purchase->asset_sn,'tips','id'=>'asset_sn','init_value','inneed'=>true,],
        ['type'=>'select','label_name'=>'固定资产类型','name'=>'AssetPurchase[asset_cate]','value'=>$asset_purchase->asset_cate,'tips','id'=>'asset_cate','inneed'=>true,'init_value'=>ArrayHelper::map(DictionaryValue::getValueList(14),'dictionary_value','dictionary_value'),],
        ['type'=>'text','label_name'=>'数量','name'=>'AssetPurchase[number]','value'=>$asset_purchase->number,'tips','id'=>'number','init_value','inneed'=>true,],
        ['type'=>'select','label_name'=>'折旧方式','name'=>'AssetPurchase[depreciation_status]','value'=>$asset_purchase->depreciation_status,'tips','id'=>'depreciation_status','inneed'=>true,'init_value'=>lang_value_config::$depreciation_status,],
        ['type'=>'text','label_name'=>'采购价格','name'=>'AssetPurchase[purchase_price]','value'=>$asset_purchase->purchase_price,'tips','id'=>'purchase_price','init_value','inneed'=>true,],
        ['type'=>'select','label_name'=>'计量单位','name'=>'AssetPurchase[asset_unit]','value'=>$asset_purchase->asset_unit,'tips','id'=>'asset_unit','inneed'=>true,'init_value'=>ArrayHelper::map(DictionaryValue::getValueList(15),'dictionary_value','dictionary_value'),],
        ['type'=>'text','label_name'=>'折旧年限','name'=>'AssetPurchase[depreciation_year_limit]','value'=>$asset_purchase->depreciation_year_limit,'tips','id'=>'depreciation_year_limit','init_value','inneed'=>true,],
        ['type'=>'text','label_name'=>'每月折旧','name'=>'AssetPurchase[depreciation_per_month]','value'=>$asset_purchase->depreciation_per_month,'tips','id'=>'depreciation_per_month','init_value','inneed'=>true,],
        ['type'=>'text','label_name'=>'加速折旧值','name'=>'AssetPurchase[speed_depreciation_fee]','value'=>$asset_purchase->speed_depreciation_fee,'tips','id'=>'speed_depreciation_fee','init_value','inneed'=>true,],
        ['type'=>'text','label_name'=>'残值','name'=>'AssetPurchase[salvage]','value'=>$asset_purchase->salvage,'tips','id'=>'salvage','init_value','inneed'=>true,],
        ['type'=>'text','label_name'=>'备注','name'=>'AssetPurchase[remark]','value'=>$asset_purchase->remark,'tips','id'=>'remark','init_value',],
  ]
	]);
?>

<?= app\common\widgets\Submit::widget(['model'=>$asset_purchase,'model_name'=>$this->context->id,'form_name'=>'order_form']); ?>