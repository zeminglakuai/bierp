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
$this->params['breadcrumbs'][] = $specimen_purchase->order_sn;
?>

<?= app\common\widgets\OrderTitle::widget([
                      'model'=>'app\common\models\SpecimenPurchase',
                      'id'=>$specimen_purchase->id,
                      'model_name'=>'specimen-purchase',
                      'label_arr' => ['supplier_name'=>['link'=>true,'id'=>'supplier','url'=>'/supplier/edit','id'=>'supplier_id'],
                                      'contract&order_sn'=>['link'=>true,'id'=>'contract','url'=>'/purchase-contract/view','id'=>'id'],
                                      'pay_method'=>'',
                                      'pay_status'=>'',
                                      'return_status'=>'',
                                      'remark'=>''
                                      ],
                      'status_label' => 'specimen_purchase_status',
                      ])
?>

<?= app\common\widgets\GoodsList::widget([
                      'model'=>'app\common\models\SpecimenPurchaseGoods',
                      'order_id'=>$purchase->id,
                      'model_name'=>'specimen-purchase',
                      'init_condition'=>[['order_id'=>$specimen_purchase->id]],
                      'title_arr'=>['id'=>['sort_able'=>1,'edit_able'=>0,'width'=>'4%'],
                                    'goods_name'=>['sort_able'=>0,'edit_able'=>0,'width'=>'15%'],
                                    'goods_sn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],
                                    'isbn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],
                                    'market_price'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%','sum'=>'market_price*number'],
                                    'purchase_price'=>['sort_able'=>1,'edit_able'=>1,'width'=>'8%'],
                                    'number'=>['sort_able'=>1,'edit_able'=>1,'width'=>'5%','total'=>true],
                                    'xiaoji'=>['sort_able'=>0,'edit_able'=>0,'width'=>'5%','total'=>true],
                                    'goods_store_info'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],                                    
                                    ],
                      'update_label_url' => Url::to(['specimen-purchase/update-goods-label']),
                      'opration' => [['lable_name'=>'删除','type'=>'js','action'=>'delete-goods','icorn_name'=>'trash','confirm'=>1]],
                      'present_action'=>'view',
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加商品','id'=>'create_purchase_goods','type'=>'js','url'=>Url::to(["specimen-purchase/create-goods","order_id"=>$specimen_purchase->id])],
                                            'export'=>['label_name'=>'导出','module_name'=>$this->context->id,'type'=>'detail','id'=>$specimen_purchase->id],
                                            'admit_process'=>['controller'=>$this->context->id,'url'=>Url::to(['/'.$this->context->id.'/admit','id'=>$specimen_purchase->id]),'model'=>$specimen_purchase,'status_label'=>'specimen_purchase_status'],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            'backup'=>['label_name'=>'返回列表','type'=>'link','icon'=>'plus','url'=>Url::to(['specimen-purchase/index'])],
                                            ])
?>

<script type="text/javascript">
  $("#create_purchase_order").click(function(){
    if (confirm('确认生成采购单？')) {
      $.get('<?= Url::to(["custom-order/create-ask-price","id"=>$custom_order->id])?>',function(result){
        if(result.error == 1){
          layer.msg(result.message);
        }else{
          layer.msg(result.message, function(){});
        }
      },'json');
    };
  });
 
</script>
