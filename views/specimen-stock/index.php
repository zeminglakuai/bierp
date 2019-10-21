<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\common\models\Store;
use app\common\config\lang_value_config;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

 
$this->params['breadcrumbs'][] = $this->context->page_title;
?>
<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['storage/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'goods@goods_name','label_name'=>'商品名称'],
                                  ]
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\SpecimenStock',
                      'model_name'=>'specimen-stock',
                      'init_condition'=>$init_condition,                  
                      'title_arr'=>['goods_id'=>1,'goods&goods_name'=>0,'goods&goods_sn'=>0,'goods&isbn'=>0,'goods&market_price'=>0,'stor_code'=>1,'number'=>1,],
                      'search_allowed' => ['goods@goods_name'=>2,'stock@store_id'=>2],
                      'opration' => [],
                      'scope'=>true,
                      'slave'=>'goods',
                      'slave_condition'=>[['goods.cat_id'=>Yii::$app->request->get('cat_id')],['like','goods.goods_name','Yii::$app->request->get("goods_name")']],                     
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>