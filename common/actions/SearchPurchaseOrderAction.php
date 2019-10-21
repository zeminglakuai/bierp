<?php
namespace app\common\actions;

use Yii;
use yii\base\Action;

class SearchPurchaseOrderAction extends Action {
    public function run() {
    	$q = Yii::$app->request->get('q');
    	$query = new \yii\db\Query();
    	$data_list = $query->select('id,order_sn as name,supplier_id,supplier_name')
							->from('purchase')
							->where(['like','order_sn',$q])
							->all();
		die(json_encode($data_list));
	}
}