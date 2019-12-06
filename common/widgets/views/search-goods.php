<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
    <form action="<?= Url::to(['/goods'])?>" method="get" class="form-horizontal">
      <div class="row">
        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">商品分类</label>
            <div class="col-sm-9">
              <Select class="form-control" name="category">
                <option value="0">商品分类</option>
                <?= $cat_list?>
              </Select>
            </div>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">商品名称</label>
            <div class="col-sm-9">
              <input type="text" name="goods_name" class="form-control" value="<?= $goods_name?>" placeholder="商品名称"/>
            </div>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">品牌</label>
            <div class="col-sm-9">
              <?= Html::dropDownList('brand', $brand, ArrayHelper::map($brand_list, 'id', 'brand_name'),['class'=>'form-control']) ?>
            </div>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">供货商</label>
            <div class="col-sm-9">
              <input type="text" name="supplier" id="supplier" class="form-control" placeholder="供货商"/>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">采购价</label>
            <div class="col-sm-9">
              <input type="text" size="8" name="purchase_start"  class="form-control" value="<?= $purchase_start?>" placeholder="采购价开始" style="width:42%;display:inline;"/>
              -
              <input type="text" size="8" name="purchase_end"  class="form-control" value="<?= $purchase_end?>" placeholder="采购价结束" style="width:42%;display:inline;"/>
            </div>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">市场价</label>
            <div class="col-sm-9">
              <input type="text" size="8" name="market_start"  class="form-control" value="<?= $market_start?>" placeholder="市场价开始" style="width:42%;display:inline;"/>
              -
              <input type="text" size="8" name="market_end"  class="form-control" value="<?= $market_end?>" placeholder="市场价结束" style="width:42%;display:inline;"/>
            </div>
          </div>
        </div>

        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">销售价</label>
            <div class="col-sm-9">
              <input type="text" size="8" name="market_start"  class="form-control" value="<?= $sale_start?>" placeholder="销售价开始" style="width:42%;display:inline;"/>
              -
              <input type="text" size="8" name="market_end"  class="form-control" value="<?= $sale_end?>" placeholder="销售价结束" style="width:42%;display:inline;"/>
            </div>
          </div>
        </div>

      </div>
      
      <div class="row">
        <div class="col-sm-11"> </div>
        <div class="col-sm-1">
          <input type="submit" class="btn btn-primary btn-sm" value="搜索"/>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="ibox">
  <div class="ibox-content">
    <table class="table table-hover dataTable">
      <thead>
        <tr>
          <th width="5%" align="center"class="<?php if($sortby == 'goods_id'){if($order==4){echo 'sorting_asc';}else{echo 'sorting_desc';}}else{echo 'sorting';}?> user_sort" data-type="goods_id">ID</th>
          <th width="28%" align="center">商品名称</th>
          <th width="8%" align="center">品牌</th>
          <th width="10%" align="center">型号</th>
          <th width="8%" align="center" class="<?php if($sortby == 'shop_price'){if($order==4){echo 'sorting_asc';}else{echo 'sorting_desc';}}else{echo 'sorting';}?> user_sort" data-type="shop_price">销售价</th>
          <th width="8%" align="center" class="<?php if($sortby == 'market_price'){if($order==4){echo 'sorting_asc';}else{echo 'sorting_desc';}}else{echo 'sorting';}?> user_sort" data-type="market_price">市场价</th>
          <th width="15%" align="center">供货商/进货价</th>
          <th  align="center">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($goods_list as $kk => $vv){?>
        <tr>
          <td><?= $vv['goods_id']?></td>
          <td><span title="<?= $vv['goods_name']?>">
            <?= strlen($vv['goods_name'])>60?mb_substr($vv['goods_name'],0,20,'utf-8').'...':$vv['goods_name'] ?>
            </span></td>
          <td><?= $vv['brand_name'] ?></td>
          <td><?= $vv['goods_sn'] ?></td>
          <td><?= $vv['shop_price']?></td>
          <td><?= $vv['market_price']?></td>
          <td><?php foreach($vv['purchase'] as $kk => $vv){
			echo $vv['simple_name'].'/'.$vv['purchase_price'].'<br>';
		}?></td>
          <td><A href="<?= Url::to(['/goods/view','goods_id'=>$vv['goods_id']])?>"><span class="glyphicon glyphicon-cog"></span> 编辑</A> <A href="javascript:if(confirm('要删除该商品吗？')){location.href='<?= Url::to(['/admin/goods/delete-goods','goods_id'=>$vv['goods_id']])?>'}"><span class="glyphicon glyphicon-trash"></span> 删除</A></td>
        </tr>
        <?php }?>
      </tbody>
    </table>
  </div>
</div>
<?php
echo LinkPager::widget([
    'pagination' => $pages,
]);
?>
<div class="oprate_bar">
  <div class="row">
    <div class="col-sm-1"><a id="add_depart" class="btn btn-warning btn-sm" href="<?= Url::to(['/goods/create'])?>"><i class="fa fa-plus"></i> 新建样品</a></div>
    <div class="col-sm-10"></div>
    <div class="col-sm-1"><button type="button" id="refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i> 刷新</button></div>
  </div>
</div>
<script>
	$(".user_sort").click(function(){
		var goods_name = '<?= $goods_name?>';
		var category = '<?= $category?>';
		var brand = '<?=$brand?>';
    var supplier = '<?=$supplier?>';
		var purchase_start = '<?=$purchase_start?>';
		var purchase_end = '<?=$purchase_end?>';
		var market_start = '<?=$market_start?>';
		var market_end = '<?=$market_end?>';
		var sortby = $(this).attr('data-type');
		var order = $(this).attr("class") == 'sorting_desc user_sort'?'SORT_ASC':'SORT_DESC';
		window.location.href= '<?= Url::to(['/goods/index'])?>?goods_name='+goods_name+'&category='+category+'&brand='+brand+'&supplier='+supplier+'&purchase_start='+purchase_start+'&purchase_end='+purchase_end+'&market_start='+market_start+'&market_end='+market_end+'&sortby='+sortby+'&order='+order;
	});
</script>
<script type="text/javascript">
  $(document).ready(function() {
      $("#supplier").tokenInput("<?= Url::to(['/goods/search-supplier'])?>",
        {
          theme:'facebook', 
          hintText:'请输入要搜索的关键字',
          tokenLimit:1,
          <?php if($supplier >= 1){ ?>prePopulate:[{id:'<?= $supplier?>',name:'<?= $supplier_info->supplier_name?>'}],<?php }?>
        }
      );
  });
</script>
