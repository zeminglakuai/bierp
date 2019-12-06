<?php
namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Project;
use app\includes\Message;
use app\controllers\BaseController;
class ProjectController extends BaseController
{   
	
	public $config;
    public function actionIndex()
    {
      return $this->render('index', []);
    }




}

?>