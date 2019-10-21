<?php
namespace app\controllers;

use Yii;

use app\includes\Message;
use app\controllers\BaseController;

class SoldLowController extends BaseController
{   
    public $enableCsrfValidation = false;
    public function actionIndex()
    {	
  		return $this->render('index', []);
    }
}
