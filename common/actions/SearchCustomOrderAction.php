<?php
namespace app\common\actions;

use Yii;
use yii\base\Action;


class SearchCustomOrderAction extends Action {
    public function run() {
    	$q = Yii::$app->request->get('q');
    	$query = new \yii\db\Query();
    	$supp_list = $query->select('id,order_name as name')
							->from('custom_order')
							->where(['like','order_name',$q])
							->orwhere(['like','order_sn',$q])
							->all();
		die(json_encode($supp_list));
	}
}