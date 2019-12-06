<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\common\models\Depart;
use app\common\config\lang_value_config;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = '任务计划';
?>
<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['mession/index']),
                      'condition'=>[
                                  ['type'=>'html_str','label'=>'depart_id','label_name'=>'部门','value'=>Depart::get_depart_select('depart_id',Yii::$app->request->get('depart_id'))],
                                  ['type'=>'text','label'=>'mession_name','label_name'=>'名称'],
                                  ]
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\Mession',
                      'model_name'=>'mession',
                      'title_arr'=>['id'=>1,'mession_name'=>0,'mession_depart_name'=>0,'year'=>1,'add_user_name'=>0,'add_time'=>0,'mession_status'=>1],
                      'search_allowed' => ['mession_depart_id'=>2,'mession_name'=>2],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'link','action'=>'view','icorn_name'=>'edit','confirm'=>0],
                                      'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete','icorn_name'=>'trash','confirm'=>1]
                                    ],
                      'scope'=>true,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加任务计划','id'=>'create_mession','type'=>'js','url'=>Url::to(["mession/create"])],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>