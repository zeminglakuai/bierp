<?php
namespace app\common\actions;

use Yii;
use yii\base\Action;
use app\common\models\SellOrder;

class SearchSellOrderAction extends Action {
    public function run() {

    	$sell_order_list = [];
    	$q = Yii::$app->request->get('q');
    	$SellOrder = SellOrder::find()->where(['like','order_sn',$q])->all();
    	if ($SellOrder) {
    		foreach ($SellOrder as $key => $value) {
	    		$sell_order_list[] = ['id'=>$value->id,'name'=>$value->order_sn,'custom_id'=>$value->custom_id,'custom_name'=>$value->custom_name,'total'=>$value->total];
	    	}
    	}

		die(json_encode($sell_order_list));
	}
}