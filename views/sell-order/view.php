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
                                      'invoiceStatus'=>['link'=>true,'id'=>'invoice','url'=>Url::to(["sell-order/invoice","id"=>$sell_order->id])],
                                      'pay_method'=>'',
                                      'payStatus'=>['link'=>true,'id'=>'receipt','url'=>Url::to(["sell-order/receipt","id"=>$sell_order->id])],
                                      'shipping_method'=>'',
                                      'shipping_status'=>'',
                                      'store_name'=>'',
                                      'return_status'=>'',
                                      'total'=>'',
                                      'consignee'=>'',
                                      'tel'=>'',
                                      'address'=>'',
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
                                    'sale_price'=>['sort_able'=>0,'edit_able'=>1,'width'=>'5%'],
                                    'number'=>['sort_able'=>1,'edit_able'=>1,'width'=>'5%','total'=>true],
                                    'xiaoji'=>['sort_able'=>0,'edit_able'=>0,'width'=>'5%','total'=>true],
                                    'is_daifa'=>['sort_able'=>0,'edit_able'=>0,'width'=>'5%','type'=>'switch'],
                                    'goods_store_info'=>['sort_able'=>0,'edit_able'=>0,'width'=>'11%'],
                                    'send_number'=>['sort_able'=>0,'edit_able'=>0,'width'=>'6%'],
                                    'return_number'=>['sort_able'=>0,'edit_able'=>0,'width'=>'6%'],
                                    ],
                      'update_label_url' => Url::to(['sell-order/update-goods-label']),
                      'opration' => [['lable_name'=>'删除','type'=>'js','action'=>'delete-goods','icorn_name'=>'trash','confirm'=>1]],
                      'present_action'=>'view',
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
      'create'=>['label_name'=>'添加商品','id'=>'create_sell_order_goods','type'=>'js','url'=>Url::to(["sell-order/create-goods","order_id"=>$sell_order->id])],
      'export'=>['label_name'=>'导出','module_name'=>$this->context->id,'type'=>'detail','id'=>$sell_order->id],
      'admit_process'=>['controller'=>$this->context->id,'url'=>Url::to(['/'.$this->context->id.'/admit','id'=>$sell_order->id]),'model'=>$sell_order,'status_label'=>'sell_order_status'],
      'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'','icon'=>'plus'],
      'backup'=>['label_name'=>'返回','type'=>'js','id'=>'','icon'=>'plus','url'=>'/sell-order'],
      'other_btn'=>[
                ['label_name'=>'生成采购单','type'=>'js','id'=>'create_purchase_order','icon'=>'yen'],
                ['label_name'=>'生成退货单','type'=>'js','id'=>'create_return_order','icon'=>'reply'],
              ],
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

  $("#create_purchase_order").click(function(){
    var goods_id_arr = get_checked_goods();
    if (!goods_id_arr) {
      alert('请选择要操作的商品');
      return false;
    };

    if(confirm('要创建采购单吗？')){
      $.get('<?= Url::to(["/sell-order/create-purchase-order","id"=>$sell_order->id])?>',{goods_id_arr:goods_id_arr},function(result){
          if(result.error == 1){
            layer.msg(result.message);
          }else{
            layer.msg(result.message);
          }
        },'json');
    }
  });

  $("#create_return_order").click(function(){

    var goods_id_arr = get_checked_goods();
    if (!goods_id_arr) {
      alert('请选择要操作的商品');
      return false;
    };


    if(confirm('要创建退货单吗？')){
      $.get('<?= Url::to(["/sell-order/create-sell-order-return","id"=>$sell_order->id])?>',{goods_id_arr:goods_id_arr},function(result){
          if(result.error == 1){
            layer.msg(result.message);
          }else{
            layer.msg(result.message);
          }
        },'json');
    }
  });

</script>