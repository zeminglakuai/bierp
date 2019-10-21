<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\common\models\Store;
use app\common\models\ApprovalProcess;
use app\common\config\lang_value_config;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

 
$this->params['breadcrumbs'][] = $this->context->page_title;
?>
<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['import-order/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'order_sn','label_name'=>'单号'],
                                  ['type'=>'text','label'=>'custom_name','label_name'=>'客户名称'],
                                  ['type'=>'select','label'=>'store_id','label_name'=>'仓库','value_arr'=>ArrayHelper::map(Store::get_store(), 'id', 'store_name'),'prompt'=>'选择仓库'],
                                  ['type'=>'select','label'=>'other_import_order_status','label_name'=>'单据状态','value_arr'=>ApprovalProcess::getApprovalValue($this->context->id)],
                                  ]
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\OtherImportOrder',
                      'model_name'=>'other-import-order',
                      'title_arr'=>['id'=>1,'order_sn'=>0,'custom_name'=>0,'sell_order_return_sn'=>0,'store_name'=>0,'add_user_name'=>0,'add_time'=>0,'other_import_order_type'=>0,'other_import_order_status'=>0,],
                      'search_allowed' => $this->context->search_allowed,
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'link','action'=>'view','icorn_name'=>'edit','confirm'=>0],
                                      'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete','icorn_name'=>'trash','confirm'=>1]
                                    ],
                      'scope'=>$this->context->scope,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            //'create'=>['label_name'=>'添加'.$this->context->page_title,'id'=>'create_sell_order','type'=>'js','url'=>Url::to(["other-import-order/create"])],
                                             
                                            'export'=>['label_name'=>'导出','module_name'=>$this->context->id,'type'=>'title'],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>