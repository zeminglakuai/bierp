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
                      'url'=>Url::to(['sell-order-return/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'order_sn','label_name'=>'退货单号'],
                                  ['type'=>'text','label'=>'sell_order_sn','label_name'=>'销售单据'],
                                  ['type'=>'text','label'=>'custom_name','label_name'=>'客户名称'],
                                  ['type'=>'select','label'=>'contract_status','label_name'=>'审核状态','value_arr'=>ApprovalProcess::getApprovalValue($this->context->id)],
                                  ]
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\SellOrderReturn',
                      'model_name'=>'sell-order-return',
                      'title_arr'=>['id'=>1,'order_sn'=>0,'custom_name'=>0,'sellOrder&order_sn'=>0,'add_user_name'=>0,'add_time'=>0,'sell_order_return_status'=>0],
                      'search_allowed' => ['order_sn'=>2,'sell_order_sn'=>2,'custom_name'=>2,'sell_order_return_status'=>1],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'link','action'=>'view','icorn_name'=>'edit','confirm'=>0],
                                     'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete','icorn_name'=>'trash','confirm'=>1]
                                    ],
                      'scope'=>true,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
          'create'=>['label_name'=>'添加销售退货单','id'=>'create_sell_order_return','type'=>'js','url'=>Url::to(["sell-order-return/create"])],
          'export'=>['label_name'=>'导出','module_name'=>$this->context->id,'type'=>'title'],
          'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
          ])
?>