<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\common\config\lang_config;
use app\common\config\lang_value_config;


$this->title = '客户方案';
$this->params['breadcrumbs'][] = ['label'=>$this->context->page_title.'列表','url'=>['index']];
$this->params['breadcrumbs'][] = $b2c_order->order_name.'-'.$b2c_order->order_sn;
?>

<?= app\common\widgets\OrderTitle::widget([
                      'model'=>'app\common\models\CustomOrder',
                      'model_data'=>$b2c_order,
                      'controller_id'=>$this->context->id,
                      'id'=>$id,
                      'model_name'=>'b2c-order',
                      'label_arr' => ['order_name'=>'',
                                      'custom&custom_name'=>['link'=>true,'id'=>'custom','url'=>Url::to(["custom/view"])],
                                      'remark'=>''
                                      ],
                      'status_label' => 'b2c_order_status',
                      ])
?>
 
<?= app\common\widgets\GoodsList::widget([
                      'model'=>'app\common\models\B2cOrderGoods',
                      'order_id'=>$id,
                      'model_name'=>'b2c-order',
                      'init_condition'=>[['order_id'=>$b2c_order->id]],
                      'title_arr'=>['goods_name'=>['sort_able'=>0,'edit_able'=>0,'width'=>'180px'],
                                    'goods_sn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'100px'],
                                    'market_price'=>['sort_able'=>0,'edit_able'=>1,'width'=>'50px'],
                                    'number'=>['sort_able'=>1,'edit_able'=>1,'width'=>'60px','total'=>true,'requir'=>true],
                                    'goods_store_info'=>['sort_able'=>0,'edit_able'=>0,'width'=>'70px'],
                                    'ppt_price'=>['sort_able'=>0,'edit_able'=>0,'width'=>'70px'],
                                    'supplier_price'=>['sort_able'=>0,'edit_able'=>1,'width'=>'90px','requir'=>true],
                                    'supplier_name'=>['sort_able'=>0,'edit_able'=>0,'width'=>'140px','link'=>['id'=>'supplier_id','url'=>Url::to(['supplier/edit'])],],
                                    'supplier_number'=>['sort_able'=>0,'edit_able'=>1,'width'=>'70px'],
                                    'limit_price'=>['sort_able'=>0,'edit_able'=>1,'width'=>'70px'],
                                    'jd_price' => ['sort_able'=>0,'edit_able'=>1,'width'=>'70px','requir'=>true],
                                    'dangdang_price' =>['sort_able'=>0,'edit_able'=>1,'width'=>'70px'],
                                    'tmall_price' => ['sort_able'=>0,'edit_able'=>1,'width'=>'70px','requir'=>true],
                                    'taobao_price' => ['sort_able'=>0,'edit_able'=>1,'width'=>'70px'],
                                    'finalCost' => ['sort_able'=>0,'edit_able'=>0,'width'=>'70px'],
                                    'finalCostTotal' => ['sort_able'=>0,'edit_able'=>0,'width'=>'70px','total'=>true],
                                    'faxPoint' => ['sort_able'=>0,'edit_able'=>0,'width'=>'60px'],
                                    'consultFee' => ['sort_able'=>0,'edit_able'=>1,'width'=>'100px','tips'=>'填写比率，根据(供货商价格*利润系数)得出结果','lable_name'=>'consult'],
                                    'sale_price'=>['sort_able'=>0,'edit_able'=>1,'width'=>'70px','requir'=>true],
                                    'profit'=>['sort_able'=>0,'edit_able'=>0,'width'=>'60px'],
                                    'profitRate'=>['sort_able'=>0,'edit_able'=>0,'width'=>'60px','average'=>true],
                                    'profitTotal'=>['sort_able'=>0,'edit_able'=>0,'width'=>'60px','total'=>true],
                                    'saleTotal'=>['sort_able'=>0,'edit_able'=>0,'width'=>'60px','total'=>true],
                                    'shipping_fee' => ['sort_able'=>0,'edit_able'=>1,'width'=>'60px','requir'=>true],
                                    'materiel_cost' => ['sort_able'=>0,'edit_able'=>1,'width'=>'60px'],
                                    'platformFee' => ['sort_able'=>0,'edit_able'=>1,'width'=>'60px','tips'=>'填写比率，根据(售价*反点比率)得出结果','lable_name'=>'platform_rate'],
                                    'tranformFee' => ['sort_able'=>0,'edit_able'=>1,'width'=>'60px','tips'=>'填写比率，根据(售价*反点比率)得出结果','lable_name'=>'tranform_rate'],
                                    'other_cost' => ['sort_able'=>0,'edit_able'=>1,'width'=>'60px'],
                                    'shipping_place' => ['sort_able'=>0,'edit_able'=>1,'width'=>'60px'],
                                    'huoqi' => ['sort_able'=>0,'edit_able'=>1,'width'=>'70px'],
                                    ],
                      'update_label_url' => Url::to(['b2c-order/update-goods-label']),
                      'width'=>'2400',
                      'present_action'=>'view',
                      'opration' => [['lable_name'=>'删除','type'=>'js','action'=>'delete-goods','icorn_name'=>'trash','confirm'=>1]]
                      ])
?>
 

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加商品','id'=>'create_custom_order_goods','type'=>'js','url'=>Url::to(["b2c-order/create-goods","order_id"=>$b2c_order->id])],
                                            'export'=>['label_name'=>'导出','module_name'=>'b2c-order','type'=>'detail','id'=>$b2c_order->id],
                                            'export_ppt'=>['label_name'=>'导出','module_name'=>'b2c-order','type'=>'detail','id'=>$b2c_order->id],
                                            'admit_process'=>['controller'=>$this->context->id,'url'=>Url::to(['/'.$this->context->id.'/admit','id'=>$b2c_order->id]),'model'=>$b2c_order,'status_label'=>'b2c_order_status'],
                                            'other_btn'=>[
                                            ['label_name'=>'修改历史','id'=>'remend_history'],
                                            ['label_name'=>'询价报备','url'=>Url::to(["b2c-order/create-ask-price","id"=>$b2c_order->id]),'id'=>'create_ask_order','type'=>'js'],
                                            ['label_name'=>'生成销售单','url'=>Url::to(["b2c-order/create-sell-order","id"=>$b2c_order->id]),'id'=>'create_sell_order','type'=>'js'],
                                            ],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'','icon'=>'plus'],
                                            'backup'=>['label_name'=>'返回','url'=>Url::to(['/b2c-order/']),],
                                            ])
?>

<script type="text/javascript">

  //检查选择的商品
  function get_checked_goods(){
    var goods_id_arr = new Array();
    $(".goods_ids").each(function(i){
      if ($(this).prop('checked')) {
        goods_id_arr.push($(this).val());
      };
    })
    if (goods_id_arr.length > 0) {
        return goods_id_arr;
    }else{
      return false;
    }
  }

  function check_if_disabled(ob){
    if (ob.hasClass('disabled')) return true;
    return false;
  }
  $("#create_ask_order").click(function(){
    if (check_if_disabled($(this))) return false;

    if (confirm('确认生成询价单？')) {
      $.get('<?= Url::to(["b2c-order/create-ask-price","id"=>$custom_order->id])?>',function(result){
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
      $.get('<?= Url::to(["b2c-order/admit","id"=>$custom_order->id])?>',function(result){
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
      $.get('<?= Url::to(["b2c-order/second-admit","id"=>$b2c_order->id])?>',function(result){
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

    var goods_id_arr = get_checked_goods();
    if (!goods_id_arr) {
      alert('请选择要操作的商品');
      return false;
    };
    
    if (confirm('确认生成销售单？')) {
      $.get('<?= Url::to(["b2c-order/create-sell-order","id"=>$b2c_order->id])?>',{goods_id_arr:goods_id_arr},function(result){
        if(result.error == 1){
          layer.msg(result.message);
        }else{
          layer.msg(result.message, function(){});
        }
      },'json');
    };
  });

  $("#remend_history").click(function(){
    var view_url = create_url('<?= Url::to(["remend-history","id"=>$b2c_order->id])?>');
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
