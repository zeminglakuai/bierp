<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\common\config\sys_config;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客维商品管理-供货商管理';
$this->params['breadcrumbs'][] = '供货商管理';
?>
<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['supplier/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'nick_name','label_name'=>'姓名'],
                                  ['type'=>'text','label'=>'contact','label_name'=>'联系人'],
                                  ['type'=>'text','label'=>'tel','label_name'=>'电话'],                           
                                  ]
                      ])
?>
 

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\QywehcatUser',
                      'title_arr'=>['id'=>1,'nick_name'=>0,'user_id'=>0,'adminUser&admin_name'=>0,'is_subcrib'=>1,'subcrib_time'=>1,'add_time'=>1],
                      'search_allowed' => ['nick_name'=>2,'user_id'=>2],
                      'opration' => ['edit'=>['lable_name'=>'查看','type'=>'js','action'=>'view','icorn_name'=>'edit']],
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>