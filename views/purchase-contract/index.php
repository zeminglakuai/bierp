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

$this->params['breadcrumbs'][] = $this->context->page_title;
?>
<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['purchase-contract/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'contract_name','label_name'=>'合同名称'],
                                  ['type'=>'text','label'=>'relate_order_sn','label_name'=>'采购单据'],
                                  ['type'=>'text','label'=>'supplier_name','label_name'=>'供货商名称'],
                                  ['type'=>'select','label'=>'contract_status','label_name'=>'合同状态','value_arr'=>ApprovalProcess::getApprovalValue($this->context->id)],
                                  ]
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\Contract',
                      'model_name'=>'purchase-contract',
                      'init_condition'=>[['type'=>2]],
                      'title_arr'=>['id'=>1,'contract_name'=>0,'order_sn'=>0,'supplier_name'=>0,'purchase&order_sn'=>0,'add_user_name'=>0,'add_time'=>0,'contract_status'=>0],
                      'search_allowed' => ['relate_order_sn'=>2,'supplier_name'=>2,'contract_status'=>1,'contract_name'=>2],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'link','action'=>'view','icorn_name'=>'edit','confirm'=>0],
                                      'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete','icorn_name'=>'trash','confirm'=>1]
                                    ],
                      'scope'=>true,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加'.$this->context->page_title,'id'=>'create_purchase_order','type'=>'js','url'=>Url::to(["purchase-contract/create"])],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>