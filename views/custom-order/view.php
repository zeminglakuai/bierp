<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\common\config\lang_config;
use app\common\config\lang_value_config;


$this->title = '客户方案';
$this->params['breadcrumbs'][] = ['label' => 'B2B方案列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $custom_order->order_name . '-' . $custom_order->order_sn;
?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>
<style>
    .fixed-table-box{
        width: 2730px;
        margin: 50px auto;
    }
    .fixed-table_body-wraper{
        /* height: 260px; */
    }
    .fixed-table-box>.fixed-table_body-wraper{/*内容了表格主体内容有纵向滚动条*/
        max-height: 260px;
    }

    .fixed-table_fixed>.fixed-table_body-wraper{/*为了让两侧固定列能够同步表格主体内容滚动*/
        max-height: 240px;
    }

    .w-150{
        width: 150px;
    }
    .w-120{
        width: 120px;
    }
    .w-300{
        width: 300px;
    }
    .w-100{
        width: 100px;
    }
</style>
<?= app\common\widgets\OrderTitle::widget([
    'model' => 'app\common\models\CustomOrder',
    'model_data' => $custom_order,
    'controller_id' => $this->context->id,
    'id' => $id,
    'model_name' => 'custom-order',
    'label_arr' => ['order_name' => '',
        'custom&custom_name' => ['link' => true, 'id' => 'custom', 'url' => Url::to(["custom/view"])],
        'remark' => ''
    ],
    'status_label' => 'custom_order_status',
])
?>

<div style="overflow:auto;width:100%;margin-bottom:5em;" id="goods_list_block">
    <div class="ibox " style="width:2730px">
        <div class="ibox-content" style="padding:10px 5px 5px 10px;">
            <a  class="btn btn-primary btn-sm" id="create_goods">添加新商品</a>
            <a   class="btn btn-primary btn-sm" id="create_val">添加新字段</a>
            <form name="" id="goods_list_form">
                <table class="table table-hover dataTable">
                    <thead id="goods_list_thead" style="width:2730px">
                    <tr>
                        <th width="50px;" data-fixed="true">
                            <div class="checkbox i-checks">
                                <label class="">
                                    <div class="icheckbox_square-green" style="position: relative;"><input
                                                type="checkbox" id="check_all" value=""
                                                style="position: absolute; opacity: 0;">
                                        <ins class="iCheck-helper"
                                             style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                    </div>
                                </label>
                            </div>
                        </th>
                        <th data-fixed="true" align="center" class="" data-type="goods_img" width="180px" style="width:80px">
                            商品图片
                        </th>
                        <th data-fixed="true" align="center" class="" data-type="goods_name" width="180px" style="width:180px">
                            商品名称
                        </th>
                        <th data-fixed="true" align="center" class="" data-type="goods_sn" width="100px" style="width:100px">
                            商品型号
                        </th>
                        <th align="center" class="" data-type="market_price" width="50px" style="width:50px">
                            市场价
                        </th>
                        <th align="center" class="sorting data_sort" data-type="number" width="60px" style="width:60px">
                            数量 <span style="color:red;">*</span></th>
                        <!--   <th align="center" class="" data-type="goods_store_info" width="90px" style="width:90px">
                               库存状态
                           </th>-->
                        <th align="center" class="" data-type="ppt_price" width="70px" style="width:70px">
                            PPT报价
                        </th>
                        <th align="center" class="" data-type="supplier_name" width="140px" style="width:140px">
                            供货商名称
                        </th>
                        <th align="center" class="" data-type="supplier_price" width="90px" style="width:90px">
                            供货商价格 <span style="color:red;">*</span></th>

                        <th align="center" class="" data-type="finalCost" width="70px" style="width:70px">
                            综合成本
                        </th>
                        <th align="center" class="" data-type="finalCostTotal" width="70px" style="width:70px">
                            成本小计
                        </th>
                        <th align="center" class="" data-type="faxPoint" width="60px" style="width:60px">
                            税费
                        </th>
                        <th align="center" class="" data-type="consultFee" width="100px" style="width:100px">
                            <span class="table_th_tips" data-toggle="tooltip" data-placement="top"
                                  data-original-title="填写比率，根据(供货商价格*利润系数)得出结果">					利润系数参考值										</span>
                        </th>
                        <th align="center" class="" data-type="sale_price" width="70px" style="width:70px">
                            售价 <span style="color:red;">*</span></th>
                        <th align="center" class="" data-type="profit" width="60px" style="width:60px">
                            毛利
                        </th>
                        <th align="center" class="" data-type="profitRate" width="60px" style="width:60px">
                            毛利率
                        </th>
                        <th align="center" class="" data-type="profitTotal" width="60px" style="width:60px">
                            毛利小计
                        </th>
                        <th align="center" class="" data-type="saleTotal" width="60px" style="width:60px">
                            售价小计
                        </th>
                        <th align="center" class="" data-type="shipping_fee" width="60px" style="width:60px">
                            运费 <span style="color:red;">*</span></th>
                        <th align="center" class="" data-type="materiel_cost" width="60px" style="width:60px">
                            物料消耗
                        </th>
                        <th align="center" class="" data-type="platformFee" width="60px" style="width:60px">
                            <span class="table_th_tips" data-toggle="tooltip" data-placement="top"
                                  data-original-title="填写比率，根据(售价*反点比率)得出结果">					平台扣点										</span>
                        </th>
                        <th align="center" class="" data-type="tranformFee" width="60px" style="width:60px">
                            <span class="table_th_tips" data-toggle="tooltip" data-placement="top"
                                  data-original-title="填写比率，根据(售价*反点比率)得出结果">					物流反点										</span>
                        </th>
                        <th align="center" class="" data-type="other_cost" width="60px" style="width:60px">
                            其他成本
                        </th>
                        <th align="center" class="" data-type="shipping_place" width="60px" style="width:60px">
                            发货地
                        </th>
                        <th align="center" class="" data-type="huoqi" width="70px" style="width:70px">
                            货期
                        </th>
                        <th align="center" class="sorting data_sort" data-type="is_priced" width="70px"
                            style="width:70px">
                            盖章报价
                        </th>
                        <th align="center" class="" data-type="shipping_to_place" width="70px" style="width:70px">
                            配送地点
                        </th>
                        <th align="center" class="sorting data_sort" data-type="is_need_temp" width="70px"
                            style="width:70px">
                            需要样板
                        </th>
                        <th align="center" class="" data-type="remark" width="100px" style="width:100px">
                            备注
                        </th>
                        <th align="center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (isset($custom_order_goods)) {
                        foreach ($custom_order_goods as $key => $val) {
                            ?>
                            <tr id="goods_row_<?=$val['goods_id']?>">

                                <td>
                                    <div class="checkbox i-checks">
                                        <label>
                                            <div class="icheckbox_square-green" style="position: relative;"><input
                                                        type="checkbox" class="goods_ids" name="goods_id[]" value="7169"
                                                        style="position: absolute; opacity: 0;">
                                                <ins class="iCheck-helper"
                                                     style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                            </div>
                                            <?=$val['goods_id']?> </label>
                                    </div>
                                </td>
                                <td>
                                    <div id="goods_img_<?=$val['goods_id']?>" title="">
                                        <?php
                                        if (isset($val['goods_img'])){
                                            ?>

                                            <img src="/<?=$val['goods_img']?>" width=30/>
                                            <?php

                                        }
                                        ?>
                                    </div>
                                </td>
                                <td>
                                    <div id="goods_name_<?=$val['goods_id']?>" title="">
                                        <?=$val['goods_name']?>
                                    </div>
                                </td>
                                <td>
                                    <div id="goods_sn_<?=$val['goods_id']?>" title="">
                                        <?=$val['goods_sn']?>
                                    </div>
                                </td>
                                <td>
                                    <div id="market_price_<?=$val['goods_id']?>" title="">
                                        <div class="lable_edit" data-id="<?=$val['goods_id']?>" data-type="market_price"><?=$val['market_price']?></div>
                                    </div>
                                </td>
                                <td>
                                    <div id="number_<?=$val['goods_id']?>" title="">
                                        <div class="lable_edit" data-id="<?=$val['goods_id']?>" data-type="number"><?=$val['number']?></div>
                                    </div>
                                </td>
                                <!--     <td>
                                    <div id="goods_store_info_<?=$val['goods_id']?>" title="">

                                    </div>
                                </td>-->
                                <td>
                                    <div id="ppt_price_<?=$val['goods_id']?>" title="">
                                        <?=$val['ppt_price']?>
                                    </div>
                                </td>

                                <td>
                                    <div id="supplier_name_<?=$val['goods_id']?>" title="">
                                        <a data-id="2358" data-url="/supplier/edit" class="view_label_content"><?=$val['supplier_name']?></a>
                                    </div>
                                </td>
                                <td>
                                    <div id="supplier_price_<?=$val['goods_id']?>" title="">
                                        <div class="lable_edit" data-id="<?=$val['goods_id']?>" data-type="supplier_price"><?=$val['supplier_price']?></div>
                                    </div>
                                </td>
                                <td>
                                    <div id="finalCost_<?=$val['goods_id']?>" title="">
                                        <?=$val['finalCost']?>
                                    </div>
                                </td>
                                <td>
                                    <div id="finalCostTotal_<?=$val['goods_id']?>" title="">
                                        <?=$val['finalCostTotal']?>
                                    </div>
                                </td>
                                <td>
                                    <div id="faxPoint_<?=$val['goods_id']?>" title="">
                                        <?=$val['faxPoint']?>
                                    </div>
                                </td>
                                <td>
                                    <div id="consultFee_<?=$val['goods_id']?>" title="填写比率，根据(供货商价格*利润系数)得出结果">
                                        <div class="lable_edit" data-id="<?=$val['goods_id']?>" data-type="consult"><?=$val['consultFee']?></div>
                                    </div>
                                </td>
                                <td>
                                    <div id="sale_price_<?=$val['goods_id']?>" title="">
                                        <div class="lable_edit" data-id="<?=$val['goods_id']?>" data-type="sale_price"><?=$val['sale_price']?></div>
                                    </div>
                                </td>
                                <td>
                                    <div id="profit_<?=$val['goods_id']?>" title="">
                                        <?=$val['profit']?>
                                    </div>
                                </td>
                                <td>
                                    <div id="profitRate_<?=$val['goods_id']?>" title="">
                                        <?=$val['profitRate']?>
                                    </div>
                                </td>
                                <td>
                                    <div id="profitTotal_<?=$val['goods_id']?>" title="">
                                        <?=$val['profitTotal']?>
                                    </div>
                                </td>
                                <td>
                                    <div id="saleTotal_<?=$val['goods_id']?>" title="">
                                        <?=$val['saleTotal']?>
                                    </div>
                                </td>
                                <td>
                                    <div id="shipping_fee_<?=$val['goods_id']?>" title="">
                                        <div class="lable_edit" data-id="<?=$val['goods_id']?>" data-type="shipping_fee"><?=$val['shipping_fee']?></div>
                                    </div>
                                </td>
                                <td>
                                    <div id="materiel_cost_<?=$val['goods_id']?>" title="">
                                        <div class="lable_edit" data-id="<?=$val['goods_id']?>" data-type="materiel_cost"><?=$val['materiel_cost']?></div>
                                    </div>
                                </td>
                                <td>
                                    <div id="platformFee_<?=$val['goods_id']?>" title="填写比率，根据(售价*反点比率)得出结果">
                                        <div class="lable_edit" data-id="<?=$val['goods_id']?>" data-type="platform_rate"><?=$val['platformFee']?></div>
                                    </div>
                                </td>
                                <td>
                                    <div id="tranformFee_<?=$val['goods_id']?>" title="填写比率，根据(售价*反点比率)得出结果">
                                        <div class="lable_edit" data-id="<?=$val['goods_id']?>" data-type="tranform_rate"><?=$val['tranformFee']?></div>
                                    </div>
                                </td>
                                <td>
                                    <div id="other_cost_<?=$val['goods_id']?>" title="">
                                        <div class="lable_edit" data-id="<?=$val['goods_id']?>" data-type="other_cost"><?=$val['other_cost']?></div>
                                    </div>
                                </td>
                                <td>
                                    <div id="shipping_place_<?=$val['goods_id']?>" title="">
                                        <div class="lable_edit" data-id="<?=$val['goods_id']?>" data-type="shipping_place"><?=$val['shipping_place']?></div>
                                    </div>
                                </td>
                                <td>
                                    <div id="huoqi_<?=$val['goods_id']?>" title="">
                                        <div class="lable_edit" data-id="<?=$val['goods_id']?>" data-type="huoqi"><?=$val['huoqi']?></div>
                                    </div>
                                </td>
                                <td>
                                    <div id="is_priced_<?=$val['goods_id']?>" title="">
                                        <input type="checkbox" class="js-switch" data-url="" data-type="is_priced"
                                               data-id="<?=$val['goods_id']?>" id="is_priced_<?=$val['goods_id']?>" data-switchery="true"
                                               style="display: none;"><span class="switchery" id="is_priced_<?=$val['goods_id']?>"
                                                                            style="box-shadow: rgb(223, 223, 223) 0px 0px 0px 0px inset; border-color: rgb(223, 223, 223); transition: border 0.4s ease 0s, box-shadow 0.4s ease 0s;"><small
                                                    style="left: 0px; transition: left 0.2s ease 0s;">

                                            </small></span>
                                    </div>
                                </td>
                                <td>
                                    <div id="shipping_to_place_<?=$val['goods_id']?>" title="">
                                        <div class="lable_edit" data-id="<?=$val['goods_id']?>" data-type="shipping_to_place">

                                            <?=$val['shipping_to_place']?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div id="is_need_temp_<?=$val['goods_id']?>" title="">
                                        <input type="checkbox" class="js-switch" data-url="" data-type="is_need_temp"
                                               data-id="<?=$val['goods_id']?>" id="is_need_temp_<?=$val['goods_id']?>" data-switchery="true"
                                               style="display: none;"><span class="switchery" id="is_need_temp_<?=$val['goods_id']?>"
                                                                            style="box-shadow: rgb(223, 223, 223) 0px 0px 0px 0px inset; border-color: rgb(223, 223, 223); transition: border 0.4s ease 0s, box-shadow 0.4s ease 0s;"><small
                                                    style="left: 0px; transition: left 0.2s ease 0s;"></small></span>
                                    </div>
                                </td>
                                <td>
                                    <div id="remark_<?=$val['goods_id']?>" title="">
                                        <div class="lable_edit" data-id="<?=$val['goods_id']?>" data-type="remark"><?=$val['remark']?></div>
                                    </div>
                                </td>

                                <td>
                                    <a class="delete_goods" data-id="<?=$val['goods_id']?>" action="delete-goods"
                                       href="javascript:void();">
                                        <span class="glyphicon glyphicon-trash"></span>
                                        删除 </a>
                                </td>
                            </tr>


                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="30" align="center">单据下没有数据</td>
                        </tr>

                        <?php
                    }
                    ?>


                    </tbody>
                </table>
            </form>



        </div>
    </div>
</div>

<?= app\common\widgets\OperateBar::widget([
    'create' => ['label_name' => '添加商品', 'id' => 'create_custom_order_goods', 'type' => 'js', 'url' => Url::to(["custom-order/create-goods", "order_id" => $custom_order->id])],
    // 'export'=>['label_name'=>'导出','module_name'=>'custom-order','type'=>'detail','id'=>$custom_order->id],
    'export_ppt' => ['label_name' => '导出ppt', 'module_name' => 'ExportPpt', 'type' => 'detail', 'id' => $custom_order->id],
    'admit_process' => ['controller' => $this->context->id, 'url' => Url::to(['/' . $this->context->id . '/admit', 'id' => $custom_order->id]), 'model' => $custom_order, 'status_label' => 'custom_order_status'],
    'other_btn' => [
        ['label_name' => '修改历史', 'id' => 'remend_history'],
        ['label_name' => '询价报备', 'url' => Url::to(["custom-order/create-ask-price", "id" => $custom_order->id]), 'id' => 'create_ask_order', 'type' => 'js'],
        ['label_name' => '生成销售单', 'url' => Url::to(["custom-order/create-sell-order", "id" => $custom_order->id]), 'id' => 'create_sell_order', 'type' => 'js'],
    ],
    'refresh' => ['label_name' => '刷新', 'type' => 'js', 'id' => '', 'icon' => 'plus'],
    'backup' => ['label_name' => '返回', 'url' => Url::to(['/custom-order/']),],
])
?>

<script type="text/javascript">

    //检查选择的商品
    function get_checked_goods() {
        var goods_id_arr = new Array();
        $(".goods_ids").each(function (i) {
            if ($(this).prop('checked')) {
                goods_id_arr.push($(this).val());
            }
            ;
        })
        if (goods_id_arr.length > 0) {
            return goods_id_arr;
        } else {
            return false;
        }
    }

    function check_if_disabled(ob) {
        if (ob.hasClass('disabled')) return true;
        return false;
    }

    $("#create_ask_order").click(function () {
        if (check_if_disabled($(this))) return false;

        if (confirm('确认生成询价单？')) {
            $.get('<?= Url::to(["custom-order/create-ask-price", "id" => $custom_order->id])?>', function (result) {
                if (result.error == 1) {
                    layer.msg(result.message);
                } else {
                    layer.msg(result.message, function () {
                    });
                }
            }, 'json');
        }
        ;
    });

    $("#first_admit").click(function () {
        if (check_if_disabled($(this))) return false;
        if (confirm('确认审核通过？')) {
            $.get('<?= Url::to(["custom-order/admit", "id" => $custom_order->id])?>', function (result) {
                if (result.error == 1) {
                    layer.msg(result.message);
                } else {
                    layer.msg(result.message, function () {
                    });
                }
            }, 'json');
        }
        ;
    });

    $("#second_admit").click(function () {
        if (check_if_disabled($(this))) return false;
        if (confirm('确认审核通过？')) {
            $.get('<?= Url::to(["custom-order/second-admit", "id" => $custom_order->id])?>', function (result) {
                if (result.error == 1) {
                    layer.msg(result.message);
                } else {
                    layer.msg(result.message, function () {
                    });
                }
            }, 'json');
        }
        ;
    });


    $("#create_sell_order").click(function () {
        if (check_if_disabled($(this))) return false;

        var goods_id_arr = get_checked_goods();
        if (!goods_id_arr) {
            alert('请选择要操作的商品');
            return false;
        }
        ;

        if (confirm('确认生成销售单？')) {
            $.get('<?= Url::to(["custom-order/create-sell-order", "id" => $custom_order->id])?>', {goods_id_arr: goods_id_arr}, function (result) {
                if (result.error == 1) {
                    layer.msg(result.message);
                } else {
                    layer.msg(result.message, function () {
                    });
                }
            }, 'json');
        }
        ;
    });

    $("#remend_history").click(function () {
        var view_url = create_url('<?= Url::to(["remend-history", "id" => $custom_order->id])?>');
        var view_id = $(this).attr('data-id');
        layer.open({
            type: 2,
            title: '修改历史',
            area: ['80%', '80%'], //宽高
            maxmin: true,
            content: view_url
        });
    });
    $(".fixed-table-box").fixedTable();

    // 创建主题
    $("#create_val").click(function() {
        //页面层
        layer.open({
            type: 2,
            title: '添加字段',
            //skin: 'layui-layer-rim', //加上边框
            area: ['80%', '80%'], //宽高
            maxmin: true,
            content: 'custom-order/create-val?id=<?= $custom_order->id; ?>',
            end: function() {
                // location.reload();
            }
        });
    });
    $("#create_goods").click(function() {
        //页面层
        layer.open({
            type: 2,
            title: '添加新商品',
            //skin: 'layui-layer-rim', //加上边框
            area: ['80%', '80%'], //宽高
            maxmin: true,
            content: '/goods/create?order_id=<?=$custom_order->id;?>&type=7',
            end: function() {
                location.reload();
            }
        });
    });


</script>


