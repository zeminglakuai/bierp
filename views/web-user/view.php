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
$this->params['breadcrumbs'][] = $export_order->order_sn;
?>

<?= app\common\widgets\OrderTitle::widget([
                      'model'=>'app\common\models\ExportOrder',
                      'id'=>$id,
                      'model_name'=>'export-order',                      
                      'label_arr' => ['custom&custom_name'=>['link'=>true,'id'=>'custom','url'=>Url::to(["custom/view"])],
                                      'shipping_method'=>'',
                                      'consignee'=>'',
                                      'tel'=>'', 
                                      'address'=>'',                                                                            
                                      'shipping_fee'=>'',
                                      'materiel_fee'=>'',
                                      'remark'=>''
                                      ],
                      'status_label' => 'export_order_status',
                      ])
?>

<?= app\common\widgets\GoodsList::widget([
                      'model'=>'app\common\models\ExportOrderGoods',
                      'order_id'=>$id,
                      'model_name'=>'export-order',
                      'init_condition'=>[['order_id'=>$export_order->id]],
                      'title_arr'=>['id'=>['sort_able'=>1,'edit_able'=>0,'width'=>'4%'],
                                    'goods_name'=>['sort_able'=>0,'edit_able'=>0,'width'=>'15%'],
                                    'goods_sn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],
                                    'isbn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'market_price'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'number'=>['sort_able'=>1,'edit_able'=>0,'width'=>'5%','total'=>true],
                                    'store_codes'=>['sort_able'=>0,'edit_able'=>0,'width'=>'5%'],
                                    'send_number'=>['sort_able'=>0,'edit_able'=>1,'width'=>'5%'],                                    
                                    ],
                      'update_label_url' => Url::to(['export-order/update-goods-label']),
                      'opration' => [['lable_name'=>'删除','type'=>'js','action'=>'delete-goods','icorn_name'=>'trash','confirm'=>1]],
                      'present_action'=>'view',
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
      'create'=>['label_name'=>'添加商品','id'=>'create_sell_order_goods','type'=>'js','url'=>Url::to(["sell-order/create-goods","order_id"=>$export_order->id])],
      'export'=>['label_name'=>'导出','id'=>'export','type'=>'js','sublist'=>[['label_name'=>'导出找货单','id'=>'1']]],
      'admit'=>['label_name'=>'审核','id'=>'admit','type'=>'js','icon'=>'eye','url'=>Url::to(["export-order/admit","id"=>$custom_order->id])],
      'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
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
