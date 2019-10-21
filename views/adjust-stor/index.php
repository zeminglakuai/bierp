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
                      'url'=>Url::to(['adjust-stor/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'order_sn','label_name'=>'单号'],
                                  ['type'=>'select','label'=>'stock@store_id','label_name'=>'仓库','value_arr'=>ArrayHelper::map(Store::find()->all(), 'id', 'store_name'),'prompt'=>'请选择仓库'],
                                  ]
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\AdjustStore',
                      'model_name'=>'adjust-stor',
                      'title_arr'=>['id'=>1,'order_sn'=>0,'goodsNumber'=>0,'add_user_name'=>0,'add_time'=>0,'adjust_stor_status'=>1,],
                      'search_allowed' => ['order_sn'=>2,'custom_name'=>2,'sell_order_status'=>1,'custom_order_id'=>2],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'link','action'=>'view','icorn_name'=>'view','confirm'=>0],
                                      'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete','icorn_name'=>'trash','confirm'=>1]
                                    ],
                      'scope'=>true,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加'.$this->context->page_title,'id'=>'create_sell_order','type'=>'js','url'=>Url::to(["sell-order/create"])],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>