<?php
namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\QywechatConfig;
use app\includes\Message;
use app\includes\QYwechat\QYwechat;
use app\includes\QYwechat\Agent;

class QyWcApiController extends \yii\web\Controller
{   
	public $config;
    public $get_date;
     
	public function beforeAction($action)
    {
		$this->config = QywechatConfig::get_config(1);

		return true;
    }

    public function actionIndex()
    {

    }

    public function actionReceive()
    {
    	$type = Yii::$app->request->get('type',0);

    	if (array_key_exists($type, Agent::$agent)) {

            $agent_config = QywechatConfig::get_config(Agent::$agent[$type]['data_id']);
            $QYwechat = new QYwechat($agent_config['token'], $agent_config['encodingAesKey'], $this->config['corpid'],Agent::$agent[$type]['data_id']);

            $raw_post_data = file_get_contents('php://input', 'r');
            if ($raw_post_data <> '') {
               $post_data = simplexml_load_file($raw_post_data);
               
            }else{

                $result = $QYwechat->reply();
            }
      	}else{
            die('wrong param!!');
        }
    }
}
