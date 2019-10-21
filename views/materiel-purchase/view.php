<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\common\config\lang_config;
use app\common\config\lang_value_config;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->params['breadcrumbs'][] = ['label'=>$this->context->page_title.'列表','url'=>['index']];
$this->params['breadcrumbs'][] = $materiel_purchase->order_sn;
?>

<?= app\common\widgets\OrderTitle::widget([
                      'model'=>'app\common\models\MaterielPurchase',
                      'id'=>$materiel_purchase->id,
                      'controller_id'=>$this->context->id,
                      'model_data'=>$materiel_purchase,
                      'model_name'=>'materiel-purchase',
                      'label_arr' => ['supplier_name'=>['link'=>true,'id'=>'supplier','url'=>'/supplier/edit','id'=>'supplier_id'],
                                      'store_name'=>'',
                                      'contract&order_sn'=>['link'=>true,'id'=>'contract','url'=>'/purchase-contract/view','id'=>'id'],
                                      'invoice_status'=>['link'=>true,'id'=>'invoice','url'=>Url::to(["invoice/view"])],
                                      'pay_method'=>'',
                                      'pay_status'=>'',
                                      'remark'=>''
                                      ],
                      'status_label' => 'materiel_purchase_status',
                      ])
?>

<?= app\common\widgets\GoodsList::widget([
                      'model'=>'app\common\models\MaterielPurchaseGoods',
                      'order_id'=>$materiel_purchase->id,
                      'model_name'=>'materiel-purchase',
                      'init_condition'=>[['order_id'=>$materiel_purchase->id]],
                      'title_arr'=>['id'=>['sort_able'=>1,'edit_able'=>0,'width'=>'4%'],
                                    'materiel_name'=>['sort_able'=>0,'edit_able'=>0,'width'=>'15%'],
                                    'use_to'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],
                                    'unit'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],                                    
                                    'purchase_price'=>['sort_able'=>1,'edit_able'=>1,'width'=>'8%'],
                                    'number'=>['sort_able'=>1,'edit_able'=>1,'width'=>'5%','total'=>true],
                                    'xiaoji'=>['sort_able'=>0,'edit_able'=>0,'width'=>'5%','total'=>true],                                  
                                    ],
                      'update_label_url' => Url::to(['materiel-purchase/update-materiel-label']),
                      'opration' => [['lable_name'=>'删除','type'=>'js','action'=>'delete-materiel','icorn_name'=>'trash','confirm'=>1]],
                      'present_action'=>'view',
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加物料','id'=>'create_purchase_materiel','type'=>'js','url'=>Url::to(["materiel-purchase/create-materiel","order_id"=>$materiel_purchase->id])],
                                            'export'=>['label_name'=>'导出','id'=>'export','type'=>'js','sublist'=>[['label_name'=>'导出找货单','id'=>'1']]],
                                            'admit_process'=>['controller'=>$this->context->id,'url'=>Url::to(['/'.$this->context->id.'/admit','id'=>$materiel_purchase->id]),'model'=>$materiel_purchase,'status_label'=>'materiel_purchase_status'],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            'backup'=>['label_name'=>'返回列表','type'=>'link','icon'=>'plus'],
                                            ])
?>
