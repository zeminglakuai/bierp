<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\common\models\ApprovalProcess;
use app\common\config\lang_value_config;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = '采购单据';
?>
<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['purchase/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'order_sn','label_name'=>'单号'],
                                  ['type'=>'text','label'=>'supplier_name','label_name'=>'供货商名称'],
                                  ['type'=>'text','label'=>'order_name','label_name'=>'项目名称'],
                                  ['type'=>'select','label'=>'purchase_status','label_name'=>'审核状态','value_arr'=>ApprovalProcess::getApprovalValue($this->context->id)],
                                  ]
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\Purchase',
                      'model_name'=>'purchase',
                      'title_arr'=>['id'=>1,'order_sn'=>0,'order_name'=>0,'supplier_name'=>0,'goodsNumber'=>0,'total'=>0,'sell_order_sn'=>1,'add_user_name'=>0,'depart_name'=>0,'add_time'=>0,'purchase_status'=>1,'store_name'=>0],
                      'search_allowed' => ['order_sn'=>2,'order_name'=>2,'supplier_name'=>2,'purchase_status'=>1],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'link','action'=>'view','icorn_name'=>'edit','confirm'=>0],


                          'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete','icorn_name'=>'trash','confirm'=>1]
                                    ],
                      'scope'=>true,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加采购单据','id'=>'purchase_order','type'=>'js','url'=>Url::to(["purchase/create"])],
    'export'=>['label_name'=>'导出','module_name'=>'purchase','type'=>'title'],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],

                                            ])
?>