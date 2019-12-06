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


$this->params['breadcrumbs'][] = ['label'=>'报备列表','url'=>['index']];
$this->params['breadcrumbs'][] = $ask_price_order->order_sn;
?>

<?= app\common\widgets\OrderTitle::widget([
                      'model'=>'app\common\models\AskPriceOrder',
                      'model_data'=>$ask_price_order,
                      'controller_id'=>$this->context->id,
                      'id'=>$id,
                      'model_name'=>$this->context->id,
                      'label_arr' => ['supplier_name'=>'',
                                      'customOrder&order_name'=>['link'=>true,'id'=>'custom_order','url'=>Url::to(["custom-order/view",'id'=>$ask_price_order->custom_order_id])],
                                      'area'=>'',
                                      'remark'=>''
                                      ],
                      'status_label' => 'custom_order_status',
                      ])
?>

<?= app\common\widgets\GoodsList::widget([
                      'model'=>'app\common\models\AskPriceOrderGoods',
                      'order_id'=>$id,
                      'model_name'=>'ask-price',
                      'init_condition'=>[['order_id'=>$id]],
                      'title_arr'=>['id'=>['sort_able'=>1,'edit_able'=>0,'width'=>'4%'],
                                    'goods_name'=>['sort_able'=>0,'edit_able'=>0,'width'=>'20%'],
                                    'goods_sn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],
                                    'isbn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'market_price'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'sale_price'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'number'=>['sort_able'=>1,'edit_able'=>0,'width'=>'5%'],
                                    'ask_price'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'return_number'=>['sort_able'=>0,'edit_able'=>1,'width'=>'8%'],
                                    'return_ask_price'=>['sort_able'=>0,'edit_able'=>1,'width'=>'8%'],
                                    ],
                      'update_label_url' => Url::to(['ask-price/update-goods-label']),
                      'opration' => [['lable_name'=>'删除','type'=>'js','action'=>'delete-goods','icorn_name'=>'trash','confirm'=>1]],
                      'present_action'=>'view',
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加商品','id'=>'create_custom_order_goods','type'=>'js','url'=>Url::to(["ask-price/create-goods","order_id"=>$ask_price_order->id])],
                                            'export'=>['label_name'=>'导出','id'=>'export','type'=>'js','sublist'=>[['label_name'=>'导出找货单','id'=>'0']]],
                                            'admit_process'=>['controller'=>$this->context->id,'url'=>Url::to(['/'.$this->context->id.'/admit','id'=>$ask_price_order->id]),'model'=>$ask_price_order,'status_label'=>'ask_price_order_status'],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            'other_btn'=>[],
                                            ])
?>

<script type="text/javascript">
  $("#create_ask_order").click(function(){
    if (confirm('确认生成询价单？')) {
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