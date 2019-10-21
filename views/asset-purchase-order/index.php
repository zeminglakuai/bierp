<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\ApprovalProcess;

$this->params['breadcrumbs'][] = $this->context->page_title;
?>
 

<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['ask-price/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'asset_name','label_name'=>'固定资产名称'],
                                  ['type'=>'text','label'=>'asset_sn','label_name'=>'固定资产编号'],
                                  ['type'=>'select','label'=>'asset_purchase_status','label_name'=>'审核状态','value_arr'=>ApprovalProcess::getApprovalValue($this->context->id)],
                                  ]
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\AssetPurchase',
                      'model_name'=>$this->context->id,
                      'title_arr'=>['id'=>1, 'asset_name'=>0,'asset_sn'=>0,'asset_cate'=>0,'add_time'=>0,'purchase_price'=>0,'number'=>0,'total'=>0,'salvage'=>1,'salvage_total'=>0,'depreciation_year_limit'=>1,'depreciation_per_month'=>1,'depreciatedFee'=>0,'expire_time'=>0],
                      'search_allowed' => $this->context->search_allowed,
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'js','action'=>'edit','icorn_name'=>'edit','confirm'=>0],
                                      'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete','icorn_name'=>'trash','confirm'=>1],
                                    ],
                      'scope'=>$this->context->scope,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加'.$this->context->page_title,'id'=>'create_asset','type'=>'js','url'=>Url::to(['/'.$this->context->id."/create"])],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>
