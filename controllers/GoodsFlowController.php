<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Brand;
use app\common\models\Goods;
use app\common\models\Supplier;
use app\common\models\BrandSupplier;
use app\includes\Message;
use app\controllers\BaseController;

class GoodsFlowController extends BaseController
{   
    public $enableCsrfValidation = false;
    public $page_title = '商品流水账';
    public function actionIndex(){
      $search_data = Yii::$app->request->get();
      $goods = Goods::findone($search_data['goods_id']);
      if ($goods) {
      	$search_data['goods_name'] = $goods->goods_name;
      }else{
      	$search_data = [];
      }


      //产品采购记录


      //销售记录

      return $this->render('index',[
                                      'data_list'=>$data_list,
                                      'search_data' => $search_data,
                                      ]
          );
    }
}
