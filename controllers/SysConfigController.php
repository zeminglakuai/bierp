<?php
namespace app\controllers;

use Yii;
use app\common\models\Config;
use app\includes\Message;
use yii\web\Controller;
use yii\db\Query;
use app\controllers\BaseController;

class SysConfigController extends BaseController
{	
	public $enableCsrfValidation = false;
    public function actionIndex()
    {	
    	$config_title = Config::find()->asarray()
								->where(['parent_id' => 0])
								->all();
		$config_content = [];						
		if (is_array($config_title)) {
			foreach ($config_title as $key => $value) {
					$config_content[$value['name']] = Config::find()->asarray()
																->where(['parent_id' => $value['id'],'visiable'=>1])
																->all();
			}

			// /修正商户类型

			if (@$seller_type = $config_content['seller_type']) {
			}else{
				$seller_type = [['id'=>0,'type_name'=>'']];
			}
 
			$config_content['seller_type'] = $seller_type;
			
		}		

		return $this->render('index',[
			'config_title' => $config_title,
			'config_content' => $config_content,
		]);
    }

	public function actionUpdate()
    {	
    	$param_arr = Yii::$app->request->post('value');

    	if (is_array($param_arr)) {
    		foreach ($param_arr as $key => $value) {
    			Yii::$app->db->createCommand()->update('config', ['value' => $value], 'id = '.$key)->execute();
    		}
    		Message::show_message('更新成功');
    	}else{
    		Message::show_message('参数错误');
    	}

    }



}
