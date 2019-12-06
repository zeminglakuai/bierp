<?php
namespace app\common\actions;

use Yii;
use yii\base\Action;

class SearchCustomAction extends Action {
    public function run() {
    	$q = Yii::$app->request->get('q');
    	$query = new \yii\db\Query();
    	$custom_list = $query->select('id,custom_name as name')
							->from('custom')
							->where(['like','custom_name',$q])
							->orwhere(['like','contact',$q])
							->all();
		die(json_encode($custom_list));
	}
}