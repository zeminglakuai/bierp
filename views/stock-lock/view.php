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


$this->params['breadcrumbs'][] = ['label'=>$this->context->page_title,'url'=>['index']];
$this->params['breadcrumbs'][] = $stock_lock->order_sn;
?>


<?= app\common\widgets\OrderTitle::widget([
                      'model'=>'app\common\models\StockLock',
                      'id'=>$stock_lock->id,
                      'model_name'=>$this->context->id,
                      'label_arr' => [
                                      'store_name'=>'',
                                      'end_time'=>'',
                                      'remark'=>''
                                      ],
                      'status_label' => 'specimen_purchase_status',
                      ])
?>


<?= app\common\widgets\GoodsList::widget([
                      'model'=>'app\common\models\StockLockGoods',
                      'order_id'=>$id,
                      'model_name'=>$this->context->id,
                      'init_condition'=>[['order_id'=>$id]],
                      'title_arr'=>['id'=>['sort_able'=>1,'edit_able'=>0,'width'=>'4%'],
                                    'goods_name'=>['sort_able'=>0,'edit_able'=>0,'width'=>'18%'],
                                    'goods_sn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],
                                    'isbn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'market_price'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%'],
                                    'number'=>['sort_able'=>1,'edit_able'=>1,'width'=>'5%'],
                                    ],
                      'update_label_url' => Url::to(['/'.$this->context->id.'/update-goods-label']),
                      'opration' => [['lable_name'=>'删除','type'=>'js','action'=>'delete-goods','icorn_name'=>'trash','confirm'=>1]]
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加商品','id'=>'add_goods','type'=>'js','url'=>Url::to(['/'.$this->context->id."/create-goods","order_id"=>$stock_lock->id])],
                                            'export'=>['label_name'=>'导出','module_name'=>$this->context->id,'type'=>'detail','id'=>$stock_lock->id],
                                            'admit_process'=>['controller'=>$this->context->id,'url'=>Url::to(['/'.$this->context->id.'/admit','id'=>$stock_lock->id]),'model'=>$stock_lock,'status_label'=>'stock_lock_status'],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            'backup'=>['label_name'=>'返回列表','type'=>'link','icon'=>'plus','url'=>Url::to(['/'.$this->context->id.'/index'])],
                                            'other_btn'=>[
                                                ['label_name'=>'解除锁库','type'=>'js','id'=>'unlock_stock','icon'=>'unlock'],
                                              ],
                                            ])
?>

<script type="text/javascript">
  $("#unlock_stock").click(function(){
    if (confirm('确认解除锁库？')) {
      $.get('<?= Url::to(["/".$this->context->id."/unlock-stock","id"=>$stock_lock->id])?>',function(result){
        if(result.error == 1){
          layer.msg(result.message);
        }else{
          layer.msg(result.message, function(){});
        }
      },'json');
    };
  });
</script>