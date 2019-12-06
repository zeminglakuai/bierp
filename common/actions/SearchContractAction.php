<?php
namespace app\common\actions;

use Yii;
use yii\base\Action;

class SearchContractAction extends Action {
    public function run() {
    	$q = Yii::$app->request->get('q');
    	$query = new \yii\db\Query();
    	$contract_list = $query->select('id,datum_name as name')
							->from('datum')
							->where(['and',['like','datum_name',$q],['cat_id'=>296]])
							->all();

		die(json_encode($contract_list));
	}
}