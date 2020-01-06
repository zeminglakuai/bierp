<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;
use app\common\config\sys_config;
use app\common\models\DictionaryValue;
use app\common\models\Brand;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客维商品管理';
$this->params['breadcrumbs'][] = $this->context->page_title;
?>

<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<div class="ibox">
    <div class="ibox-content" style="padding-bottom:10px;">
        <form action="<?= Url::to(['/goods']) ?>" method="get" class="form-horizontal">
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">产品分类</label>
                        <div class="col-sm-9">
                            <Select class="form-control" name="category">
                                <option value="0">产品分类</option>
                                <?= $cat_list ?>
                            </Select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">产品ID</label>
                        <div class="col-sm-9">
                            <input type="text" name="goods_id" class="form-control" value="<?= $goods_id ?>" placeholder="产品ID" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">产品名称</label>
                        <div class="col-sm-9">
                            <input type="text" name="goods_name" class="form-control" value="<?= $goods_name ?>" placeholder="产品名称" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">品牌</label>
                        <div class="col-sm-9">
                            <input type="text" name="brand_id" id="brand" class="form-control" size="25" placeholder="商品品牌" value="<?= $brand_id ?>" />
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $("#brand").tokenInput(<?= Brand::getTokeninputlist() ?>, {
                                        theme: 'facebook',
                                        <?php if ($brand_id >= 1) { ?>prePopulate: [{
                                            id: '<?= $brand_id ?>',
                                            name: '<?= $brand->brand_name ?>'
                                        }],
                                    <?php } ?>
                                    hintText: '请输入要搜索的关键字',
                                    tokenLimit: 1
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">供货商</label>
                        <div class="col-sm-9">
                            <input type="hidden" name="type" value="<?= $search_data['type'] ?>" class="form-control" />
                            <input type="text" id="supplier" name="supplier_name" value="<?= $supplier_name ?>" class="form-control" placeholder="供货商" />
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $("#supplier").tokenInput("/goods/search-supplier", {
                                        theme: 'facebook',
                                        hintText: '请输入要搜索的关键字',
                                        tokenLimit: 1,
                                        <?php if ($search_data['supplier'] >= 1) { ?>prePopulate: [{
                                            id: '<?= $search_data['supplier'] ?>',
                                            name: '<?= $supplier_info->supplier_name ?>'
                                        }],
                                    <?php } ?>
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">采购价</label>
                        <div class="col-sm-9">
                            <input type="text" size="8" name="purchase_start" class="form-control" value="<?= $purchase_start ?>" placeholder="采购价开始" style="width:42%;display:inline;" />
                            -
                            <input type="text" size="8" name="purchase_end" class="form-control" value="<?= $purchase_end ?>" placeholder="采购价结束" style="width:42%;display:inline;" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">市场价</label>
                        <div class="col-sm-9">
                            <input type="text" size="8" name="market_start" class="form-control" value="<?= $market_start ?>" placeholder="市场价开始" style="width:42%;display:inline;" />
                            -
                            <input type="text" size="8" name="market_end" class="form-control" value="<?= $market_end ?>" placeholder="市场价结束" style="width:42%;display:inline;" />
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">销售价</label>
                        <div class="col-sm-9">
                            <input type="text" size="8" name="market_start" class="form-control" value="<?= $sale_start ?>" placeholder="销售价开始" style="width:42%;display:inline;" />
                            -
                            <input type="text" size="8" name="market_end" class="form-control" value="<?= $sale_end ?>" placeholder="销售价结束" style="width:42%;display:inline;" />
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-sm-11"> </div>
                <div class="col-sm-1">
                    <input type="submit" class="btn btn-primary btn-sm" value="搜索" />
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
                    <th width="5%" align="center" class="<?php if ($sortby == 'goods_id') {
                                                                if ($order == 4) {
                                                                    echo 'sorting_asc';
                                                                } else {
                                                                    echo 'sorting_desc';
                                                                }
                                                            } else {
                                                                echo 'sorting';
                                                            } ?> user_sort" data-type="goods_id">ID</th>

                    <th width="18%" align="center">品名</th>
                    <th width="13%" align="center">品牌</th>
                    <th width="11%" align="center">型号</th>


                    <th width="11%" align="center" class="<?php if ($sortby == 'shop_price') {
                                                                if ($order == 4) {
                                                                    echo 'sorting_asc';
                                                                } else {
                                                                    echo 'sorting_desc';
                                                                }
                                                            } else {
                                                                echo 'sorting';
                                                            } ?> user_sort" data-type="shop_price">销售价</th>
                    <th width="11%" align="center" class="<?php if ($sortby == 'market_price') {
                                                                if ($order == 4) {
                                                                    echo 'sorting_asc';
                                                                } else {
                                                                    echo 'sorting_desc';
                                                                }
                                                            } else {
                                                                echo 'sorting';
                                                            } ?> user_sort" data-type="market_price">市场价</th>
                    <th width="16%" align="center">条码</th>
                    <?php
                    if ($type == 5) {
                    ?>
                        <th width="10%" align="center">供货商</th>
                        <th width="5%" align="center">采购价</th>
                        <th width="5%" align="center">数量</th>
                        <th width="7%" align="center">平台</th>
                        <th width="5%" align="center">平台有效期</th>
                        <th width="5%" align="center">仓库</th>
                        <th width="5%" align="center">天数</th>
                    <?php } ?>
                    <th align="center">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($goods_list) {
                    foreach ($goods_list as $kk => $vv) { ?>
                        <tr>
                            <td><?= $vv['goods_id'] ?></td>

                            <td><span title="<?= $vv['goods_name'] ?>">
                                    <?= strlen($vv['goods_name']) > 60 ? mb_substr($vv['goods_name'], 0, 20, 'utf-8') . '...' : $vv['goods_name'] ?>
                                </span></td>
                            <td><?= $vv['brand_name'] ?></td>
                            <td><?= $vv['goods_sn'] ?></td>

                            <td><?= $vv['shop_price'] ?></td>
                            <td><?= $vv['market_price'] ?></td>
                            <td><?= $vv['isbn'] ?></td>
                            <?php
                            if ($type == 5) { ?>
                                <td> <?php

                                        if ($vv['goods_supplier']) {
                                            foreach ($vv['goods_supplier'] as $k => $v) {
                                                echo $v['supplier_name'] . '</br>';
                                            }
                                        }
                                        ?>
                                </td>
                                <td>
                                    <?php
                                    if ($vv['goods_supplier']) {
                                        foreach ($vv['goods_supplier'] as $k => $v) {
                                            echo $v['supplier_price'] . '</br>';
                                        }
                                    }
                                    ?>
                                </td>


                                <td>
                                    <?php
                                    if ($vv['data']) {
                                        foreach ($vv['data'] as $k => $v) {
                                            echo $v['number'] . '</br>';
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ($vv['goods_platform']) {
                                        foreach ($vv['goods_platform'] as $k => $v) {
                                            echo '<span style="display: block;">' . $v['plat_name'] . '</span>' . '</br>';
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ($vv['goods_platform']) {
                                        foreach ($vv['goods_platform'] as $k => $v) {
                                            echo '<span style="display: block;">' . $v['startdate'] . '</br>' . $v['enddate'] . '</span>';
                                        }
                                    }
                                    ?>
                                </td>
                                <td> <?php
                                        if ($vv['data']) {
                                            foreach ($vv['data'] as $k => $v) {
                                                echo $v['store_name'] . '</br>';
                                            }
                                        }
                                        ?></td>
                                <td> <?php
                                        if ($vv['data']) {
                                            foreach ($vv['data'] as $k => $v) {
                                                echo $v['time'] . '</br>';
                                            }
                                        }
                                        ?></td>


                            <?php } ?>
                            <td>
                                <A href="<?= Url::to(['/goods/view', 'goods_id' => $vv['goods_id'], 'type' => $type]) ?>"><span class="glyphicon glyphicon-cog"></span> 编辑</A>
                                <?php
                                if ($type == 3) { ?>
                                    <A href="<?= Url::to(['/goods/viewt', 'goods_id' => $vv['goods_id'], 'type' => $type]) ?>"><span class="glyphicon glyphicon-cog"></span> 添加商品</A>
                                <?php }
                                ?>
                                <!--            <A href="javascript:void();" class="show_custom_order" data-id="--><? //= $vv['goods_id']
                                                                                                                    ?>
                                <!--"><span class="glyphicon glyphicon-eye-open"></span> 参加项目</A>-->
                                <A href="javascript:if(confirm('要删除该商品吗？')){location.href='<?= Url::to(['/goods/delete', 'goods_id' => $vv['goods_id']]) ?>'}"><span class="glyphicon glyphicon-trash"></span> 删除</A>
                            </td>
                        </tr>
                    <?php }
                } else {
                    ?>
                    <tr>
                        <td colspan="9" align="center">没有商品资料</td>
                    </tr>
                <?php
                } ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row" style="margin-bottom:5em;">
    <div class="col-sm-3">
    </div>
    <div class="col-sm-5" align="right">
        <?php
        echo LinkPager::widget([
            'pagination' => $pages,
        ]);
        ?>
    </div>
</div>
<?php
if ($type == 1) {
?>
    <form action="<?= Url::to(['/supplier-price/import-goods']) ?>" method="post" class="form-horizontal" enctype="multipart/form-data">

        <div class="row" style="margin-bottom:5em;">

            <div class="col-sm-4">
                <input type="file" name="Goods[ppt_file]" class="form-control" align="left" />

            </div>
            <div class="col-sm-3">
                <input type="submit" name="submit" value="导入产品" />
                <!--                  <a href="/goods/exports" class="btn btn-danger btn-sm"><i class="fa fa-plus"></i> 导出产品</a>-->

                <a href="/goods/exportg?template_id=85&module_name=goods&type=title<?php
                                                                                    $i = 0;
                                                                                    foreach ($search_data as $key => $val) {

                                                                                        if ($val != '' && $val != '0') {
                                                                                            $i++;
                                                                                            echo '&sea[' . $key . ']=' . $val;
                                                                                        }
                                                                                    }
                                                                                    if ($i > 0) {
                                                                                        echo "&seaId=1";
                                                                                    }
                                                                                    ?>" target="_blank">导出产品</a>
            </div>

        </div>
    </form>
<?php } ?>
<?= app\common\widgets\OperateBar::widget([
    'create' => ['label_name' => '添加产品', 'id' => 'add_goods', 'type' => 'link', 'icon' => 'plus', 'url' => Url::to(["/goods/create?type=$type"])],

    'refresh' => ['label_name' => '刷新', 'type' => 'js', 'id' => 'add_custom', 'icon' => 'plus'],
    'other_btn' => [
        ['label_name' => '待审核商品', 'type' => 'link', 'icon' => 'plus', 'url' => Url::to(["/goods/admit-list"])],
    ]
])
?>


<script>
    $(".user_sort").click(function() {
        var goods_name = '<?= $goods_name ?>';
        var category = '<?= $category ?>';
        var brand_id = '<?= $brand_id ?>';
        var supplier = '<?= $supplier ?>';
        var purchase_start = '<?= $purchase_start ?>';
        var purchase_end = '<?= $purchase_end ?>';
        var market_start = '<?= $market_start ?>';
        var market_end = '<?= $market_end ?>';
        var sortby = $(this).attr('data-type');
        var order = $(this).attr("class") == 'sorting_desc user_sort' ? 'SORT_ASC' : 'SORT_DESC';
        window.location.href = '<?= Url::to(['/goods/index']) ?>?goods_name=' + goods_name + '&category=' + category + '&brand_id=' + brand_id + '&supplier=' + supplier + '&purchase_start=' + purchase_start + '&purchase_end=' + purchase_end + '&market_start=' + market_start + '&market_end=' + market_end + '&sortby=' + sortby + '&order=' + order;
    });

    $(".show_custom_order").click(function() {
        var data_id = $(this).attr('data-id');
        var to_url = create_url('<?= "/" . $this->context->id . "/custom-order-goods" ?>');
        //页面层
        layer.open({
            type: 2,
            title: '商品参与项目',
            //skin: 'layui-layer-rim', //加上边框
            area: ['90%', '80%'], //宽高
            content: to_url + 'goods_id=' + data_id
        });
    });
</script>