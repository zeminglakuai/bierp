<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\common\config\lang_config;
use app\common\config\lang_value_config;


$this->title = '客户方案';
$this->params['breadcrumbs'][] = ['label'=>'客户方案列表','url'=>['index']];
$this->params['breadcrumbs'][] = $custom_order->order_name.'-'.$custom_order->order_sn;
?>

<?= app\common\widgets\OrderTitle::widget([
                      'model'=>'app\common\models\CustomOrder',
                      'id'=>$id,
                      'model_name'=>'custom-order',                      
                      'label_arr' => ['order_name'=>'',
                                      'custom&custom_name'=>['link'=>true,'id'=>'custom','url'=>Url::to(["custom/view"])],
                                      'remark'=>''
                                      ],
                      'status_label' => 'custom_order_status',
                      ])
?>

<div class="ibox">
  <div class="ibox-title">
    <h5>审批流程</h5>
    <div class="ibox-tools" style="margin-right:3em;">
        <a class="collapse-link" id="remend_history" data-toggle="tooltip" data-placement="top" data-original-title="修改历史">
            <i class="fa fa-wrench"></i>
        </a>
    </div>
  </div>
  <div class="ibox-content" style="padding-bottom:10px;">
    <div class="row" style="margin-bottom:15px;">
      <div class="col-sm-2"></div>
      <div class="col-sm-1"><button class="btn btn-danger btn-sm <?= $custom_order->custom_order_status >= 1?'disabled':''?>" id="first_admit">1.首次审核</button><br><span class="gray" style="line-height:2em;">由部门经理审核</span></div>
      <div class="col-sm-1"><button class="btn btn-danger  btn-sm <?= $custom_order->custom_order_status >= 2?'disabled':''?>" id="second_admit">2.二次审核</button><br><span class="gray" style="line-height:2em;">由总经理审核</span></div>
      <div class="col-sm-1"><button class="btn <?= $custom_order->custom_order_status >= 2 ?'btn-danger':''?> <?= $custom_order->is_create_ask_price == 1 || $custom_order->custom_order_status < 2 ?'disabled':''?> btn-sm" id="create_ask_order">3.向供货商报备</button></div>
      <div class="col-sm-1"><button class="btn <?= $custom_order->custom_order_status >= 2 ?'btn-danger':''?> <?= $custom_order->custom_order_status <> 2 ?'disabled':''?> btn-sm" id="create_sell_order">4.生成销售单</button></div>
    </div>
  </div>
</div>

<?= app\common\widgets\GoodsList::widget([
                      'model'=>'app\common\models\CustomOrderGoods',
                      'order_id'=>$id,
                      'model_name'=>'custom-order',
                      'init_condition'=>[['order_id'=>$custom_order->id]],
                      'title_arr'=>['goods_name'=>['sort_able'=>0,'edit_able'=>0,'width'=>'5%'],
                                    'goods_sn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'3%'],
                                    'isbn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'4%'],
                                    'market_price'=>['sort_able'=>0,'edit_able'=>1,'width'=>'2%'],
                                    'number'=>['sort_able'=>1,'edit_able'=>1,'width'=>'2%','total'=>true,'requir'=>true],
                                    'goods_store_info'=>['sort_able'=>0,'edit_able'=>0,'width'=>'4%'],
                                    'ppt_price'=>['sort_able'=>0,'edit_able'=>0,'width'=>'3%'],
                                    'supplier_price'=>['sort_able'=>0,'edit_able'=>1,'width'=>'3%','requir'=>true],
                                    'supplier_name'=>['sort_able'=>0,'edit_able'=>0,'width'=>'6%','link'=>['id'=>'supplier_id','url'=>Url::to(['supplier/edit'])],],
                                    'supplier_number'=>['sort_able'=>0,'edit_able'=>1,'width'=>'2%'],
                                    'limit_price'=>['sort_able'=>0,'edit_able'=>1,'width'=>'3%'],
                                    'jd_price' => ['sort_able'=>0,'edit_able'=>1,'width'=>'3%','requir'=>true],
                                    'dangdang_price' =>['sort_able'=>0,'edit_able'=>1,'width'=>'3%'],
                                    'tmall_price' => ['sort_able'=>0,'edit_able'=>1,'width'=>'3%','requir'=>true],
                                    'taobao_price' => ['sort_able'=>0,'edit_able'=>1,'width'=>'3%'],
                                    'finalCost' => ['sort_able'=>0,'edit_able'=>0,'width'=>'3%'],
                                    'finalCostTotal' => ['sort_able'=>0,'edit_able'=>0,'width'=>'3%','total'=>true],                                    
                                    'faxPoint' => ['sort_able'=>0,'edit_able'=>0,'width'=>'2%'],
                                    'consultFee' => ['sort_able'=>0,'edit_able'=>1,'width'=>'3%','tips'=>'填写比率，根据(供货商价格*利润系数)得出结果','lable_name'=>'consult'],
                                    'sale_price'=>['sort_able'=>0,'edit_able'=>1,'width'=>'3%','requir'=>true],
                                    'profit'=>['sort_able'=>0,'edit_able'=>0,'width'=>'2%'],
                                    'profitRate'=>['sort_able'=>0,'edit_able'=>0,'width'=>'2%','average'=>true],
                                    'profitTotal'=>['sort_able'=>0,'edit_able'=>0,'width'=>'2%','total'=>true],
                                    'saleTotal'=>['sort_able'=>0,'edit_able'=>0,'width'=>'2%','total'=>true],
                                    'shipping_fee' => ['sort_able'=>0,'edit_able'=>1,'width'=>'2%','requir'=>true],
                                    'materiel_cost' => ['sort_able'=>0,'edit_able'=>1,'width'=>'3%'],
                                    'platformFee' => ['sort_able'=>0,'edit_able'=>1,'width'=>'3%','tips'=>'填写比率，根据(售价*反点比率)得出结果','lable_name'=>'platform_rate'],
                                    'tranformFee' => ['sort_able'=>0,'edit_able'=>1,'width'=>'3%','tips'=>'填写比率，根据(售价*反点比率)得出结果','lable_name'=>'tranform_rate'],
                                    'other_cost' => ['sort_able'=>0,'edit_able'=>1,'width'=>'3%'],
                                    'shipping_place' => ['sort_able'=>0,'edit_able'=>1,'width'=>'2%'],
                                    'huoqi' => ['sort_able'=>0,'edit_able'=>1,'width'=>'3%'],
                                    ],
                      'update_label_url' => Url::to(['custom-order/update-goods-label']),
                      'width'=>'3500',
                      'present_action'=>'view',
                      'opration' => [['lable_name'=>'删除','type'=>'js','action'=>'delete-goods','icorn_name'=>'trash','confirm'=>1]]
                      ])
?>
 

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加商品','id'=>'create_custom_order_goods','type'=>'js','url'=>Url::to(["custom-order/create-goods","order_id"=>$custom_order->id])],
                                            'export'=>['label_name'=>'导出','id'=>'export','type'=>'js','sublist'=>[['label_name'=>'导出找货单','id'=>'1']]],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'','icon'=>'plus'],
                                            'backup'=>['label_name'=>'返回','url'=>Url::to(['/custom-order/']),],
                                            ])
?>

<script type="text/javascript">
  function check_if_disabled(ob){
    if (ob.hasClass('disabled')) return true;
    return false;
  }
  $("#create_ask_order").click(function(){
    if (check_if_disabled($(this))) return false;

    if (confirm('确认生成询2价单？')) {
      $.get('<?= Url::to(["custom-order/create-ask-price","id"=>$custom_order->id])?>',function(result){
        if(result.error == 1){
          layer.msg(result.message);
        }else{
          layer.msg(result.message, function(){});
        }
      },'json');
    };
  });

  $("#first_admit").click(function(){
    if (check_if_disabled($(this))) return false;
    if (confirm('确认审核通过？')) {
      $.get('<?= Url::to(["custom-order/admit","id"=>$custom_order->id])?>',function(result){
        if(result.error == 1){
          layer.msg(result.message);
        }else{
          layer.msg(result.message, function(){});
        }
      },'json');
    };
  });

  $("#second_admit").click(function(){
    if (check_if_disabled($(this))) return false;
    if (confirm('确认审核通过？')) {
      $.get('<?= Url::to(["custom-order/second-admit","id"=>$custom_order->id])?>',function(result){
        if(result.error == 1){
          layer.msg(result.message);
        }else{
          layer.msg(result.message, function(){});
        }
      },'json');
    };
  });


  $("#create_sell_order").click(function(){
    if (check_if_disabled($(this))) return false;
    if (confirm('确认生成销售单？')) {
      $.get('<?= Url::to(["custom-order/create-sell-order","id"=>$custom_order->id])?>',function(result){
        if(result.error == 1){
          layer.msg(result.message);
        }else{
          layer.msg(result.message, function(){});
        }
      },'json');
    };
  });

  $("#remend_history").click(function(){
    var view_url = create_url('<?= Url::to(["remend-history","id"=>$custom_order->id])?>');
    var view_id = $(this).attr('data-id');    
      layer.open({
        type: 2,
        title:'修改历史',
        area: ['80%', '80%'], //宽高
        maxmin: true,
        content: view_url
      });
  });
</script>
