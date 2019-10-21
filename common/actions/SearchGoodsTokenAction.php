<?php
namespace app\common\actions;

use Yii;
use yii\base\Action;
use app\common\models\Goods;

class SearchGoodsTokenAction extends Action {
    public function run() {
      $goods_list = [];
      $q = Yii::$app->request->get('q');

      $goods = Goods::find()->where(['or',['like','goods_name',$q],['like','goods_sn',$q]])->offset(0)->limit(10)->all();
      if ($goods) {
        foreach ($goods as $key => $value) {
          $goods_list[] = ['id'=>$value->goods_id,'name'=>$value->goods_name,'supplier_name'=>$value->supplier_name];
        }
      }

    die(json_encode($goods_list));
  }
}