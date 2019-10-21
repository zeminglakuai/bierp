<?php
namespace app\common\actions;

use Yii;
use yii\base\Action;

class SearchAdminAction extends Action {
    public function run() {
    	$q = Yii::$app->request->get('q');
    	$query = new \yii\db\Query();
    	$supp_list = $query->select('id,real_name as name,admin_name')
							->from('admin')
							->where(['like','admin_name',$q])
							->orwhere(['like','real_name',$q])
							->orwhere(['like','tel',$q])
							->all();
		die(json_encode($supp_list));
	}
}