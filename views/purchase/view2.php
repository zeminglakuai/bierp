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
$this->params['breadcrumbs'][] = $purchase->order_sn;
?>

<?= app\common\widgets\OrderTitle::widget([
                      'model'=>'app\common\models\Purchase',
                      'model_data'=>$purchase,
                      'id'=>$purchase->id,
                      'controller_id'=>$this->context->id,
                      'model_name'=>'purchase',
                      'label_arr' => ['supplier_name'=>['link'=>true,'id'=>'supplier','url'=>'/supplier/edit','id'=>'supplier_id'],
                                      'sellOrder&order_sn'=>['link'=>true,'id'=>'sell-order','url'=>'/sell-order/view','id'=>'id'],
                                      'store_name'=>'',
                                      'contract&order_sn'=>['link'=>true,'id'=>'contract','url'=>'/purchase-contract/view','id'=>'id'],
                                      'invoiceStatus'=>['link'=>true,'id'=>'invoice_status','url'=>Url::to(["purchase/invoice","id"=>$purchase->id])],
                                      'pay_method'=>'',
                                      'payStatus'=>['link'=>true,'id'=>'payment_status','url'=>Url::to(["purchase/payment","id"=>$purchase->id])],
                                      'pay_method'=>'',
                                      'return_status'=>'',
                                      'consignee'=>'',
                                      'consignee_tel'=>'',
                                      'address'=>'',
                                      'remark'=>''
                                      ],
                      'status_label' => 'purchase_status',
                      ])
?>

<?= app\common\widgets\GoodsList::widget([
                      'model'=>'app\common\models\PurchaseGoods',
                      'order_id'=>$purchase->id,
                      'model_name'=>'purchase',
                      'init_condition'=>[['order_id'=>$purchase->id]],
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
                      'update_label_url' => Url::to(['purchase/update-goods-label']),
                      'opration' => [['lable_name'=>'删除','type'=>'js','action'=>'delete-goods','icorn_name'=>'trash','confirm'=>1]],
                      'present_action'=>'view',
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加商品','id'=>'create_purchase_goods','type'=>'js','url'=>Url::to(["purchase/create-goods","order_id"=>$purchase->id])],
                                            'export'=>['label_name'=>'导出','module_name'=>$this->context->id,'type'=>'detail','id'=>$purchase->id],
                                            'admit_process'=>['controller'=>$this->context->id,'url'=>Url::to(['/'.$this->context->id.'/admit','id'=>$purchase->id]),'model'=>$purchase,'status_label'=>'purchase_status'],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            'backup'=>['label_name'=>'返回','type'=>'js','id'=>'','icon'=>'plus','url'=>'/purchase'],
                                            'other_btn'=>[
                                                      ['label_name'=>'生成退货单','type'=>'js','id'=>'create_purchase_return_order','icon'=>'reply'],
                                                      ['label_name'=>'反复核','type'=>'js','id'=>'purchase_unadmit','icon'=>'reply'],
                                                      ['label_name'=>'生成入库单','type'=>'js','id'=>'create_import','icon'=>'reply'],
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


  $("#create_purchase_return_order").click(function(){

    var goods_id_arr = get_checked_goods();
    if (!goods_id_arr) {
      alert('请选择要操作的商品');
      return false;
    };

    if(confirm('要创建采购退货单吗？')){
      $.get('<?= Url::to(["/purchase/create-purchase-return","id"=>$purchase->id])?>',{goods_id_arr:goods_id_arr},function(result){
          if(result.error == 1){
            layer.msg(result.message);
          }else{
            layer.msg(result.message);
          }
        },'json');
    }
  });

  $("#purchase_unadmit").click(function(){
    if(confirm('要创建反复核吗？反复核之后，单据将回到前一次的复核状态')){
      $.get('<?= Url::to(["/purchase/un-admit","id"=>$purchase->id])?>',function(result){
          if(result.error == 1){
            layer.msg(result.message);
          }else{
            layer.msg(result.message);
          }
        },'json');
    }
  });

  $("#create_import").click(function(){
    if(confirm('要创建入库单？')){
      $.get('<?= Url::to(["/purchase/create-import","id"=>$purchase->id])?>',function(result){
          if(result.error == 1){
            layer.msg(result.message);
          }else{
            layer.msg(result.message);
          }
        },'json');
    }
  });
</script>
