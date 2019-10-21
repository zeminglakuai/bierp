<?php
namespace app\common\actions;

use Yii;
use yii\base\Action;
use yii\data\Pagination;
 

class ToAdmitAction extends Action {
    public function run() {

    	$get_data = Yii::$app->request->get();

		$get_data['id'] = !empty(trim(Yii::$app->request->get('id')))?trim(Yii::$app->request->get('id')):0;
		$get_data['data_status'] = !empty(trim(Yii::$app->request->get('data_status')))?trim(Yii::$app->request->get('data_status')):0;

		$this->controller->layout = 'empty';
		return $this->controller->render('/default/admit', [
										'get_data' => $get_data,

		]);
    }
}