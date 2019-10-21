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
                      'url'=>Url::to(['web-user/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'consignee','label_name'=>'用户姓名'],
                                  ['type'=>'text','label'=>'tel','label_name'=>'电话'],
                                  ]
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\WebUser',
                      'model_name'=>'web-user',
                      'title_arr'=>['id'=>1,'consignee'=>0,'tel'=>0,'address'=>0,'plat_name'=>0,'add_time'=>1,],
                      'search_allowed' => ['consignee'=>2,'tel'=>2],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'link','action'=>'view','icorn_name'=>'edit','confirm'=>0],
                                      'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete','icorn_name'=>'trash','confirm'=>1]
                                    ],
                      'scope'=>true,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加会员','id'=>'create_sell_order','type'=>'js','url'=>Url::to(["sell-order/create"])],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>