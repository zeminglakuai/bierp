<?php
namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;
use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Admin;
use app\common\models\Leave;

use yii\helpers\Url;
use app\includes\Message;
use app\controllers\BaseController;
use app\includes\Common_fun;

class ExpireGoodsController extends BaseController
{
    public $page_title = '有效期商品';
    
}
