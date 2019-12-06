<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\common\config\lang_value_config;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = '商品授权';
?>
<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['goods-auth/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'supplier_name','label_name'=>'供货商名称'],
                                  ]
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\GoodsAuth',
                      'model_name'=>'goods-auth',
                      'title_arr'=>['id'=>1,'supplier_name'=>0,'expire_time'=>1,'add_time'=>0],
                      'search_allowed' => ['supplier_name'=>2],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'link','action'=>'view','icorn_name'=>'edit','confirm'=>0],
                                      'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete','icorn_name'=>'trash','confirm'=>1]
                                    ],
                      'scope'=>true,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加商品授权','id'=>'create_goods_auth','type'=>'js','url'=>Url::to(["goods-auth/create"])],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>