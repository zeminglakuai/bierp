<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Stock;
use yii\helpers\Url;
use app\includes\Message;
use app\controllers\BaseController;
use app\includes\Common_fun;

class CheckStorController extends BaseController
{
  public $page_title = '盘点';
  public function beforeAction($action)
  {
    parent::beforeAction($action);

    //检查当前单据用户是不是有操作权限
    $need_privi_arr = ['delete','edit','admit','insert-goods','delete-goods','update-goods-label'];
    $admit_allow_arr = ['edit'];

    $scope_model = 'app\common\models\ExportOrder';
    $status_label = 'export_order_status';

    if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
      return true;
    }else{
      return false;
    }
  }
  public function actionIndex()
  {
      return parent::actionIndex(); // TODO: Change the autogenerated stub
  }
}
