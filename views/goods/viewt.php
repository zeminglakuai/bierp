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


$this->params['breadcrumbs'][] = ['label'=>$this->context->page_title.'列表','url'=>['index?type=3']];
$this->params['breadcrumbs'][] = $goods->goods_name;
?>
<div class="ibox">
    <div class="ibox-content" style="padding-bottom:10px;">
        <div class="row">
            <div class="col-sm-9">


                <div class="col-sm-3 m-b-1">

                    商品名称:
                    <a  id="supplier_id" data-id="2278">
                        <?php echo $goods['goods_name']?> </a>
                </div>



                <div class="col-sm-3 m-b-1">
                    条形码 :
                    <span>
                                      <a id="id" data-id=""><?php echo $goods['isbn']?>
                                                                        </a>
                                  </span>
                </div>

                <div class="row">
                    <div class="col-sm-11">

                    </div>
                    <div class="col-sm-1">

                        <!--                        <button class="btn btn-primary btn-sm" id="order_edit">编辑</button>-->
                    </div>
                </div>
            </div>
            <!--  <div class="col-sm-3 text-right">
                  <p><span class="padding-lr-5">单号:<b class="blue">PURC2019090900002</b></span></p>
                  <p><span class="padding-lr-5">状态:</span></p>
                  <p><span class="padding-lr-5">创建人:admin/广州客维总部</span><span
                              class="padding-lr-5">日期:2019-09-09 14:06:31</span></p>
              </div>-->
        </div>


    </div>
</div>



<?= app\common\widgets\GoodsList::widget([
    'model'=>'app\common\models\PurchaseGoods',
    'order_id'=>$goods->goods_id,
    'model_name'=>'purchase',
    'init_condition'=>[['order_id'=>$goods->goods_id]],
    'title_arr'=>['id'=>['sort_able'=>1,'edit_able'=>0,'width'=>'4%'],
        'goods_name'=>['sort_able'=>0,'edit_able'=>0,'width'=>'15%'],
        'goods_sn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],
        'isbn'=>['sort_able'=>0,'edit_able'=>0,'width'=>'10%'],
        'market_price'=>['sort_able'=>0,'edit_able'=>0,'width'=>'8%','sum'=>'market_price*number'],
        'purchase_price'=>['sort_able'=>1,'edit_able'=>1,'width'=>'8%'],
        'number'=>['sort_able'=>1,'edit_able'=>1,'width'=>'5%','total'=>true],
        'xiaoji'=>['sort_able'=>0,'edit_able'=>0,'width'=>'5%','total'=>true],

    ],
    'update_label_url' => Url::to(['purchase/update-goods-label']),
    'opration' => [['lable_name'=>'删除','type'=>'js','action'=>'delete-goods','icorn_name'=>'trash','confirm'=>1]],
    'present_action'=>'view',
])
?>

<?= app\common\widgets\OperateBar::widget([
    'create'=>['label_name'=>'添加商品','id'=>'create_purchase_goods','type'=>'js','url'=>Url::to(["purchase/create-goods","order_id"=>$goods->goods_id,"OrderType"=>2])],

    'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
    'backup'=>['label_name'=>'返回','type'=>'js','id'=>'','icon'=>'plus','url'=>'/purchase'],

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

</script>
