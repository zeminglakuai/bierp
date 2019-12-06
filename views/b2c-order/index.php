<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\common\config\lang_value_config;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = 'B2C方案';
?>
<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['b2c-order/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'order_sn','label_name'=>'单号'],
                                  ['type'=>'text','label'=>'order_name','label_name'=>'项目名称'],
                                  ['type'=>'text','label'=>'custom_name','label_name'=>'客户名称'],
                                  ['type'=>'select','label'=>'b2c_order_status','label_name'=>'单据状态','value_arr'=>lang_value_config::$b2c_order_status],
                                  ]
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\B2cOrder',
                      'model_name'=>'b2c-order',
                      'title_arr'=>['id'=>1,'order_sn'=>0,'order_name'=>0,'custom_name'=>0,'goodsNumber'=>0 ,'saleTotal'=>0,'add_user_name'=>0,'add_time'=>0,'b2c_order_status'=>0],
                      'search_allowed' => ['order_sn'=>2,'order_name'=>2,'custom_name'=>2,'b2c_order_status'=>1],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'link','action'=>'view','icorn_name'=>'edit','confirm'=>0],
                                      'delete'=>['lable_name'=>'删除','type'=>'link','action'=>'delete','icorn_name'=>'trash','confirm'=>1]
                                    ],
                      'scope'=>true,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加B2C方案','id'=>'create_custom_order','type'=>'js','url'=>Url::to(["b2c-order/create"])],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>