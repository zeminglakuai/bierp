<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\common\config\lang_value_config;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = '供货商联系人';
?>
<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['supplier-contact/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'admin_name','label_name'=>'联系人名称'],
                                  ['type'=>'text','label'=>'real_name','label_name'=>'真实姓名'],
                                  ['type'=>'text','label'=>'tel','label_name'=>'手机'],
                                  ]
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\Admin',
                      'model_name'=>'supplier-contact',
                      'init_condition'=>['supplier_id > 0'],
                      'title_arr'=>['id'=>1,'admin_name'=>0,'real_name'=>0,'tel'=>0,'supplier_name'=>0,'add_time'=>0,'is_active'=>0],
                      'search_allowed' => ['admin_name'=>2,'real_name'=>2,'tel'=>2],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'js','action'=>'edit','icorn_name'=>'edit','confirm'=>0],
                                     'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete','icorn_name'=>'trash','confirm'=>1]
                                    ],
                      'scope'=>true,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加供货商联系人','id'=>'create_supplier_contact','type'=>'js','url'=>Url::to(["supplier-contact/create"])],
                                            'export'=>['label_name'=>'导出','id'=>'export','type'=>'js','sublist'=>[['label_name'=>'导出找货单','id'=>'1'],['label_name'=>'导出找货单','id'=>'2'],['label_name'=>'导出找货单','id'=>'3']]],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>