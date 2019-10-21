<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;
use app\common\config\sys_config;
use app\common\models\DictionaryValue;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->params['breadcrumbs'][] = ['label'=>'样品资料','url'=>['index']];
$this->params['breadcrumbs'][] = '待审核样品';
?>


<div class="ibox">
  <div class="ibox-content">
    <table class="table table-hover dataTable">
      <thead>
        <tr>
          <th width="5%" align="center"class="<?php if($sortby == 'goods_id'){if($order==4){echo 'sorting_asc';}else{echo 'sorting_desc';}}else{echo 'sorting';}?> user_sort" data-type="goods_id">ID</th>
          <th width="23%" align="center">商品名称</th>
          <th width="8%" align="center">品牌</th>
          <th width="10%" align="center">型号</th>
          <th width="10%" align="center">ISBN</th>
          <th width="8%" align="center" class="<?php if($sortby == 'shop_price'){if($order==4){echo 'sorting_asc';}else{echo 'sorting_desc';}}else{echo 'sorting';}?> user_sort" data-type="shop_price">销售价</th>
          <th width="8%" align="center" class="<?php if($sortby == 'market_price'){if($order==4){echo 'sorting_asc';}else{echo 'sorting_desc';}}else{echo 'sorting';}?> user_sort" data-type="market_price">市场价</th>
          <th width="15%" align="center">供货商/进货价</th>
          <th  align="center">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($confirm_list as $kk => $vv){?>
        <tr id="goods_row_<?= $vv->goods_id?>">
          <td><?= $vv->goods_id?></td>
          <td><span title="<?= $vv->goods_name ?>">
            <?= strlen($vv->goods_name)>60?mb_substr($vv->goods_name,0,20,'utf-8').'...':$vv->goods_name ?>
            </span></td>
          <td><?= $vv->brand->brand_name ?></td>
          <td><?= $vv->goods_sn ?></td>
          <td><?= $vv->isbn ?></td>
          <td><?= $vv->shop_price?></td>
          <td><?= $vv->market_price ?></td>
          <td>
            <?php 
    			     echo $vv->supplier_name.'/'.$vv->supplier_price.'<br>';
        		?>
          </td>
          <td>
          <A href="javascript:void();" class="view_goods" data-id="<?= $vv->goods_id?>"><span class="glyphicon glyphicon-cog"></span> 查看</A>
          <A href="javascript:void();" class="admit_goods" data-id="<?= $vv->goods_id?>"><span class="glyphicon glyphicon-eye-open"></span> 通过审核</A>
        </td>
        </tr>
        <?php }?>
      </tbody>
    </table>
  </div>
</div>

<script type="text/javascript">
$(".view_goods").click(function(){
  var data_id = $(this).attr('data-id');
  var to_url = create_url('<?= Url::to(["/goods/edit"])?>');
  //页面层
  layer.open({
    type: 2,
    title:'编辑',
    //skin: 'layui-layer-rim', //加上边框
    area: ['90%', '90%'], //宽高
    content: to_url+'id='+data_id
  });
});


$(".admit_goods").click(function(){
  var data_id = $(this).attr('data-id');
  var to_url = create_url('<?= Url::to(["/goods/admit"])?>');
  if (confirm('确认通过审核？')) {
    $.get(to_url,{id:data_id},function(result){
      if (result.error == 1) {
        $("#goods_row_"+data_id).remove();
        layer.msg(result.message);
      }else{
        layer.msg(result.message);
      }
    },'json')
  }else{
    return false;
  }
});
</script>




