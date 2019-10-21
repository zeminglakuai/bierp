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
<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
    <div class="row">
      <div class="col-sm-6">
          <p>供货商名称:<?= $ask_price_order->supplier_name?></p>
          <p>备注:<?= $ask_price_order->remark?></p>          
      </div>
      <div class="col-sm-6 text-right">
          <p><span class="padding-lr-5">单据编号:<?= $ask_price_order->order_sn?></span></p>
          <p><span class="padding-lr-5">单据状态:<?= lang_value_config::$ask_price_order_status[$ask_price_order->ask_price_order_status]?></span></p>
          <p><span class="padding-lr-5">创建人:<?= $ask_price_order->add_user_name ?></span><span class="padding-lr-5">创建日期:<?= date('Y-m-d H:i:s',$ask_price_order->add_time)?></span></p>
          <?php if($ask_price_order->ask_price_order_status >= 1){
          ?>
          <p><span class="padding-lr-5">复核人:<?= $ask_price_order->admit_user_name ?></span><span class="padding-lr-5">复核日期:<?= $ask_price_order->admit_time>0?date('Y-m-d H:i:s',$ask_price_order->admit_time):'' ?></span></p>
          <?php
          }?>
      </div>
    </div>
  </div>
</div>

<?= app\common\widgets\GoodsList::widget([
                      'model'=>'app\common\models\AskPriceOrderGoods',
                      'order_id'=>$id,
                      'model_name'=>'ask-price',
                      'init_condition'=>[['order_id'=>$id]],
                      'title_arr'=>['id'=>['sort_able'=>1,'edit_able'=>0,'width'=>'4%'],
                                    'goods_name'=>['sort_able'=>0,'edit_able'=>0,'width'=>'18%'],
                                    'goods_sn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],
                                    'isbn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'market_price'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'sale_price'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'number'=>['sort_able'=>1,'edit_able'=>0,'width'=>'5%'],
                                    'return_number'=>['sort_able'=>0,'edit_able'=>1,'width'=>'8%'],
                                    'return_ask_price'=>['sort_able'=>0,'edit_able'=>1,'width'=>'8%'],
                                    ],
                      'update_label_url' => Url::to(['ask-price/update-goods-label']),
                      'opration' => [['lable_name'=>'删除','type'=>'js','action'=>'delete-goods','icorn_name'=>'trash','confirm'=>1]]
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加商品','id'=>'create_custom_order_goods','type'=>'js','url'=>Url::to(["ask-price/create-goods","order_id"=>$ask_price_order->id])],
                                            'export'=>['label_name'=>'导出','id'=>'export','type'=>'js','sublist'=>[['label_name'=>'导出找货单','id'=>'0']]],
                                            'admit'=>['label_name'=>'审核','id'=>'admit','type'=>'js','icon'=>'eye','url'=>Url::to(["ask-price/admit","id"=>$ask_price_order->id])],
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