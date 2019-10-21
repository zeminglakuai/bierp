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
$this->params['breadcrumbs'][] = $sell_order->order_sn;
?>

<?= app\common\widgets\OrderTitle::widget([
                      'model'=>'app\common\models\SellOrder',
                      'id'=>$id,
                      'model_name'=>'sell-order',                      
                      'label_arr' => ['custom&custom_name'=>['link'=>true,'id'=>'custom','url'=>Url::to(["custom/view"])],
                                      'contract&order_sn'=>['link'=>true,'id'=>'contract','url'=>Url::to(["sell-contract/view"])],
                                      'customOrder&order_sn'=>['link'=>true,'id'=>'CustomOrder','url'=>Url::to(["custom-order/view"])],
                                      'invoice_status'=>['link'=>true,'id'=>'invoice','url'=>Url::to(["invoice/view"])],
                                      'pay_method'=>'',
                                      'pay_status'=>'',
                                      'shipping_method'=>'',
                                      'shipping_status'=>'',
                                      'return_status'=>'',
                                      'remark'=>''
                                      ],
                      'status_label' => 'sell_order_status',
                      ])
?>

<?= app\common\widgets\GoodsList::widget([
                      'model'=>'app\common\models\SellOrderGoods',
                      'order_id'=>$id,
                      'model_name'=>'sell-order',
                      'init_condition'=>[['order_id'=>$sell_order->id]],
                      'title_arr'=>['id'=>['sort_able'=>1,'edit_able'=>0,'width'=>'4%'],
                                    'goods_name'=>['sort_able'=>0,'edit_able'=>0,'width'=>'15%'],
                                    'goods_sn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],
                                    'isbn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'market_price'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'sale_price'=>['sort_able'=>0,'edit_able'=>1,'width'=>'8%'],
                                    'number'=>['sort_able'=>1,'edit_able'=>1,'width'=>'5%','total'=>true],
                                    'xiaoji'=>['sort_able'=>0,'edit_able'=>0,'width'=>'5%','total'=>true],
                                    'goods_store_info'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],
                                    ],
                      'update_label_url' => Url::to(['sell-order/update-goods-label']),
                      'opration' => [['lable_name'=>'删除','type'=>'js','action'=>'delete-goods','icorn_name'=>'trash','confirm'=>1]],
                      'present_action'=>'view',
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
      'create'=>['label_name'=>'添加商品','id'=>'create_sell_order_goods','type'=>'js','url'=>Url::to(["sell-order/create-goods","order_id"=>$sell_order->id])],
      'export'=>['label_name'=>'导出','id'=>'export','type'=>'js','sublist'=>[['label_name'=>'导出找货单','id'=>'1']]],
      'admit_process'=>['controller'=>$this->context->id,'url'=>Url::to(['/'.$this->context->id.'/admit','id'=>$ask_price_order->id]),'model'=>$ask_price_order,'status_label'=>'ask_price_order_status'],
      'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
      'other_btn'=>[
                ['label_name'=>'生成采购单','type'=>'js','id'=>'create_purchase_order','icon'=>'yen'],
                ['label_name'=>'生成退货单','type'=>'js','id'=>'create_return_order','icon'=>'reply'],
              ],
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
