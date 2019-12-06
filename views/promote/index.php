<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\common\config\lang_value_config;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->context->page_title;
?>
<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['promote/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'order_sn','label_name'=>'单号'],
                                  ['type'=>'text','label'=>'b2c_order_sn','label_name'=>'B2c单据号'],
                                  ['type'=>'text','label'=>'custom_name','label_name'=>'客户名称'],
                                  ['type'=>'select','label'=>'promote_status','label_name'=>'单据状态','value_arr'=>lang_value_config::$custom_order_status],
                                  ]
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\Promote',
                      'model_name'=>'promote',
                      'title_arr'=>['id'=>1,'order_sn'=>0,'b2c_order_sn'=>0,'custom_name'=>0,'add_user_name'=>0,'add_time'=>0,'promote_status'=>0],
                      'search_allowed' => ['order_sn'=>2,'order_name'=>2,'custom_name'=>2,'promote_status'=>1],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'link','action'=>'view','icorn_name'=>'edit','confirm'=>0],
                                      'delete'=>['lable_name'=>'删除','type'=>'link','action'=>'delete','icorn_name'=>'trash','confirm'=>1]
                                    ],
                      'scope'=>true,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加促销方案','id'=>'create_promote','type'=>'js','url'=>Url::to(["promote/create"])],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>















