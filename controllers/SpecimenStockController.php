<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Stock;
use app\common\models\SpecimenStock;
use yii\helpers\Url;
use app\includes\Message;
use app\controllers\BaseController;
use app\includes\Common_fun;

class SpecimenStockController extends BaseController
{
  public $page_title = '样板库存';

  public function actionIndex(){

    return $this->render('index',['init_condition'=>$init_condition]);
  }

}
