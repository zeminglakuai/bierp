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
$this->params['breadcrumbs'][] = $purchase_return->order_sn;
?>

<?= app\common\widgets\OrderTitle::widget([
                      'model'=>'app\common\models\PurchaseReturn',
                      'id'=>$id,
                      'model_name'=>'purchase-return',                      
                      'label_arr' => ['supplier&supplier_name'=>['link'=>true,'id'=>'supplier','url'=>Url::to(["supplier/view"])],
                                      'purchase_sn'=>'',
                                      'purchase_return_status'=>'',
                                      'remark'=>''
                                      ],
                      'status_label' => 'purchase_return_status',
                      ])
?>

<?= app\common\widgets\GoodsList::widget([
                      'model'=>'app\common\models\PurchaseReturnGoods',
                      'order_id'=>$id,
                      'model_name'=>'purchase-return',
                      'init_condition'=>[['order_id'=>$purchase_return->id]],
                      'title_arr'=>['id'=>['sort_able'=>1,'edit_able'=>0,'width'=>'4%'],
                                    'goods_name'=>['sort_able'=>0,'edit_able'=>0,'width'=>'15%'],
                                    'goods_sn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],
                                    'isbn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'market_price'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'purchase_price'=>['sort_able'=>0,'edit_able'=>1,'width'=>'8%'],
                                    'number'=>['sort_able'=>1,'edit_able'=>1,'width'=>'5%','total'=>true],
                                    'xiaoji'=>['sort_able'=>0,'edit_able'=>0,'width'=>'5%','total'=>true],
                                    'return_number'=>['sort_able'=>0,'edit_able'=>1,'width'=>'5%','total'=>true],
                                    ],
                      'update_label_url' => Url::to(['purchase-return/update-goods-label']),
                      'opration' => [['lable_name'=>'删除','type'=>'js','action'=>'delete-goods','icorn_name'=>'trash','confirm'=>1]],
                      'present_action'=>'view',
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
      'create'=>['label_name'=>'添加商品','id'=>'create_purchase_return_goods','type'=>'js','url'=>Url::to(["purchase-return/create-goods","order_id"=>$purchase_return->id])],
      'export'=>['label_name'=>'导出','module_name'=>$this->context->id,'type'=>'detail','id'=>$purchase_return->id],
      'admit_process'=>['controller'=>$this->context->id,'url'=>Url::to(['/'.$this->context->id.'/admit','id'=>$purchase_return->id]),'model'=>$purchase_return,'status_label'=>'purchase_return_status'],
      'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
      'backup'=>['label_name'=>'返回','type'=>'js','id'=>'','icon'=>'plus','url'=>'/purchase-return'],
      ])
?>

<script type="text/javascript">
  // $("#create_purchase_order").click(function(){
  //   layer.open({
  //     type: 2,
  //     title:'创建采购单',
  //     //skin: 'layui-layer-rim', //加上边框
  //     area: ['80%', '80%'], //宽高
  //     maxmin: true,
  //     content: '<?= Url::to(["sell-order/create-purchase-order","id"=>$sell_order->id])?>'
  //   });
  // });

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

    if(confirm('要创建退货单吗？')){
      $.get('<?= Url::to(["/purchase-return/create-purchase-return","id"=>$sell_order->id])?>',{goods_id_arr:goods_id_arr},function(result){
          if(result.error == 1){
            layer.msg(result.message);
          }else{
            layer.msg(result.message);
          }
        },'json');
    }
  });

</script>