<?php
namespace app\common\actions;

use Yii;
use yii\base\Action;

class SearchSupplierAction extends Action {
    public function run() {
    	$q = Yii::$app->request->get('q');
    	$id = Yii::$app->request->get('id');
    	if ($id) {
	    	$query = new \yii\db\Query();
	    	$supplier = $query->select('id,supplier_name as name,tax_code,title')
								->from('supplier')
								->where(['id'=>$id])
								->one();
			die(json_encode($supplier));
    	}
    	$query = new \yii\db\Query();
    	$supp_list = $query->select('id,supplier_name as name,tax_code,title')
							->from('supplier')
							->where(['like','supplier_name',$q])
							->orwhere(['like','simple_name',$q])
							->all();
		die(json_encode($supp_list));
	}
}