<?php
namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\QywechatConfig;
use app\includes\Message;
use app\includes\QYwechat\QYwechat;
use app\controllers\BaseController;
class QyWechatController extends BaseController
{   
	
	public $config;
    public function actionIndex()
    {
      return $this->render('index', []);
    }




}
