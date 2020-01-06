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


$this->params['breadcrumbs'][] = ['label' => $this->context->page_title . '列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $purchase->order_sn;
?>

<?= app\common\widgets\OrderTitle::widget([
    'model' => 'app\common\models\Purchase',
    'model_data' => $purchase,
    'id' => $purchase->id,
    'controller_id' => $this->context->id,
    'model_name' => 'purchase',
    'label_arr' => [
        'order_name' => '',
        'supplier_name' => ['link' => true, 'id' => 'supplier', 'url' => '/supplier/edit', 'id' => 'supplier_id'],
        //'sellOrder&order_sn'=>['link'=>true,'id'=>'sell-order','url'=>'/sell-order/view','id'=>'id'],
        //                                      'contract&order_sn'=>['link'=>true,'id'=>'contract','url'=>'/purchase-contract/view','id'=>'id'],
        'invoiceStatus' => ['link' => true, 'id' => 'invoice_status', 'url' => Url::to(["purchase/invoice", "id" => $purchase->id])],
        'pay_method' => '',
        'payStatus' => ['link' => true, 'id' => 'payment_status', 'url' => Url::to(["purchase/payment", "id" => $purchase->id])],
        'pay_type' => '',
        'return_status' => '',
        'store_name' => '',
        'consignee' => '',
        'consignee_tel' => '',
        'address' => '',
        'remark' => '',
        'remark1' => '',

        // 'platform_beizhu' => '',

        'qcode' => '',
    ],
    'status_label' => 'purchase_status',
])
?>
<!--<a id="create_payment" class="btn btn-warning btn-sm">添加账单</a>-->
<div style="overflow:auto;width:100%;margin-bottom:5em;" id="goods_list_block">
    <div class="ibox" style="">
        <div class="ibox-content" style="padding:10px 5px 5px 10px;">
            <form name="" id="goods_list_form">
                <table class="table table-hover dataTable">
                    <thead id="goods_list_thead" style="">
                        <tr>
                            <th width="50px;">
                                <div class="checkbox i-checks">
                                    <label>
                                        <div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" id="check_all" value="" style="position: absolute; opacity: 0;">
                                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                        </div>
                                    </label>
                                </div>
                            </th>
                            <th align="center" class="<?
                                                        if ($vv['sort_able']) {
                                                            if ($search_data['sortby'] == $kk) {
                                                                if ($search_data['order'] == 4) {
                                                                    echo 'sorting_asc data_sort';
                                                                } else {
                                                                    echo 'sorting_desc data_sort';
                                                                }
                                                            } else {
                                                                echo 'sorting data_sort';
                                                            }
                                                        } ?>" data-type="id" width="4%" style="width:4%">
                                ID
                            </th>
                            <th align="center" class="<?
                                                        if ($vv['sort_able']) {
                                                            if ($search_data['sortby'] == $kk) {
                                                                if ($search_data['order'] == 4) {
                                                                    echo 'sorting_asc data_sort';
                                                                } else {
                                                                    echo 'sorting_desc data_sort';
                                                                }
                                                            } else {
                                                                echo 'sorting data_sort';
                                                            }
                                                        } ?>" data-type="goods_name" width="15%" style="width:15%">
                                商品名称
                            </th>
                            <th align="center" class="<?
                                                        if ($vv['sort_able']) {
                                                            if ($search_data['sortby'] == $kk) {
                                                                if ($search_data['order'] == 4) {
                                                                    echo 'sorting_asc data_sort';
                                                                } else {
                                                                    echo 'sorting_desc data_sort';
                                                                }
                                                            } else {
                                                                echo 'sorting data_sort';
                                                            }
                                                        } ?>" data-type="goods_sn" width="10%" style="width:10%">
                                商品型号
                            </th>
                            <th align="center" class="<?
                                                        if ($vv['sort_able']) {
                                                            if ($search_data['sortby'] == $kk) {
                                                                if ($search_data['order'] == 4) {
                                                                    echo 'sorting_asc data_sort';
                                                                } else {
                                                                    echo 'sorting_desc data_sort';
                                                                }
                                                            } else {
                                                                echo 'sorting data_sort';
                                                            }
                                                        } ?>" data-type="isbn" width="10%" style="width:10%">
                                条形码
                            </th>
                            <th align="center" class="<?
                                                        if ($vv['sort_able']) {
                                                            if ($search_data['sortby'] == $kk) {
                                                                if ($search_data['order'] == 4) {
                                                                    echo 'sorting_asc data_sort';
                                                                } else {
                                                                    echo 'sorting_desc data_sort';
                                                                }
                                                            } else {
                                                                echo 'sorting data_sort';
                                                            }
                                                        } ?>" data-type="market_price" width="8%" style="width:8%">
                                上线平台————平台有效期
                            </th>
                            <th align="center" class="<?
                                                        if ($vv['sort_able']) {
                                                            if ($search_data['sortby'] == $kk) {
                                                                if ($search_data['order'] == 4) {
                                                                    echo 'sorting_asc data_sort';
                                                                } else {
                                                                    echo 'sorting_desc data_sort';
                                                                }
                                                            } else {
                                                                echo 'sorting data_sort';
                                                            }
                                                        } ?>" data-type="purchase_price" width="8%" style="width:8%">
                                采购价
                            </th>
                            <th align="center" class="<?
                                                        if ($vv['sort_able']) {
                                                            if ($search_data['sortby'] == $kk) {
                                                                if ($search_data['order'] == 4) {
                                                                    echo 'sorting_asc data_sort';
                                                                } else {
                                                                    echo 'sorting_desc data_sort';
                                                                }
                                                            } else {
                                                                echo 'sorting data_sort';
                                                            }
                                                        } ?>" data-type="number" width="5%" style="width:5%">
                                数量
                            </th>
                            <th align="center" data-type="number" width="5%" style="width:5%">
                                小计
                            </th>
                            <th align="center">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($purchase_good)) {
                            foreach ($purchase_good as $k => $purchase_goods) {
                        ?>
                                <tr id="goods_row_<?= $purchase_goods['id'] ?>">
                                    <td>
                                        <div class="checkbox i-checks">
                                            <label>
                                                <div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" class="goods_ids" name="goods_id[]" value="7235" style="position: absolute; opacity: 0;">
                                                    <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                                </div>
                                                <?= $k + 1; ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="id_<?= $purchase_goods['id'] ?>" title="">
                                            <?= $purchase_goods['goods_id'] ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="goods_name_<?= $purchase_goods['id'] ?>" title="">
                                            <?= $purchase_goods['goods_name'] ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="goods_sn_<?= $purchase_goods['id'] ?>" title="">
                                            <?= $purchase_goods['goods_sn'] ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="isbn_<?= $purchase_goods['id'] ?>" title="">
                                            <?= $purchase_goods['isbn'] ?>

                                        </div>
                                    </td>
                                    <td>
                                        <div id="market_price_<?= $purchase_goods['id'] ?>" title="">
                                            <select name="platform[][platform_id]" onchange="gradeChange(<?= $purchase_goods['goods_id'] ?>,this.options[this.options.selectedIndex].value,<?= $purchase->id ?>, <?= $purchase_goods['id'] ?>)">
                                                <option selected>选择平台</option>
                                                <?php
                                                if (isset($purchase_goods['platform'])) {
                                                    foreach ($purchase_goods['platform'] as $key => $v) {
                                                ?>
                                                        <option value="<?= $v['id']; ?>" <?php if ($purchase_goods['platform_id'] == $v['id']) {
                                                                                                echo "selected";
                                                                                            }; ?>><?php echo $v['plat_name'] . '————' . $purchase_goods['startdate'] . '—' . $purchase_goods['enddate'] ?></option>

                                                <?php
                                                    }
                                                }
                                                ?>
                                                <option value="5" <?php if ($purchase_goods['platform_id'] == 5) {
                                                                        echo "selected";
                                                                    }; ?>>其他</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="purchase_price_<?= $purchase_goods['id'] ?>" title="">
                                            <div class="lable_edit" data-id="<?= $purchase_goods['id'] ?>" data-type="purchase_price"><?= $purchase_goods['purchase_price'] ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="number_<?= $purchase_goods['id'] ?>" title="">
                                            <div class="lable_edit" data-id="<?= $purchase_goods['id'] ?>" data-type="number"><?= $purchase_goods['number'] ?></div>
                                        </div>
                                    </td>

                                    <td>
                                        <div id="xiaoji_<?= $purchase_goods['id'] ?>" title="">
                                            <?php echo $purchase_goods['number'] * $purchase_goods['purchase_price'] ?>
                                        </div>
                                    </td>

                                    <td>
                                        <a class="delete_goods" data-id="<?= $purchase_goods['id'] ?>" action="delete-goods" href="javascript:void();">
                                            <span class="glyphicon glyphicon-trash"></span>
                                            删除 </a>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>


<?= app\common\widgets\OperateBar::widget([
    'create' => ['label_name' => '添加商品', 'id' => 'create_purchase_goods', 'type' => 'js', 'url' => Url::to(["purchase/create-goods", "order_id" => $purchase->id, "purchase_type" => $purchase->purchase_type, "OrderType" => 1])],

    'export' => ['label_name' => '导出', 'module_name' => $this->context->id, 'type' => 'detail', 'id' => $purchase->id],
    'admit_process' => ['controller' => $this->context->id, 'url' => Url::to(['/' . $this->context->id . '/admit', 'id' => $purchase->id]), 'model' => $purchase, 'status_label' => 'purchase_status'],
    'refresh' => ['label_name' => '刷新', 'type' => 'js', 'id' => 'add_custom', 'icon' => 'plus'],
    'backup' => ['label_name' => '返回', 'type' => 'js', 'id' => '', 'icon' => 'plus', 'url' => '/purchase'],
    'other_btn' => [
        ['label_name' => '生成退货单', 'type' => 'js', 'id' => 'create_purchase_return_order', 'icon' => 'reply'],
        ['label_name' => '反复核', 'type' => 'js', 'id' => 'purchase_unadmit', 'icon' => 'reply'],
        ['label_name' => '生成入库单', 'type' => 'js', 'id' => 'create_import', 'icon' => 'reply'],
    ],
])
?>

<script type="text/javascript">
    //检查选择的商品
    function get_checked_goods() {
        var goods_id_arr = new Array();
        $(".goods_ids").each(function(i) {
            if ($(this).prop('checked')) {
                goods_id_arr.push($(this).val());
            };
        })
        if (goods_id_arr.length > 0) {
            return goods_id_arr;
        } else {
            return false;
        }
    }
    $(".lable_edit").hover(
        function() {
            $(this).addClass('lable_edit_over');
        },
        function() {
            $(this).removeClass('lable_edit_over');
        }
    );
    $(".lable_edit").click(
        function() {
            var id = <?= $purchase->id; ?>;
            var id = <?= $purchase->id; ?>;
            var data_id = $(this).attr("data-id");
            var data_type = $(this).attr("data-type");
            var target = $(this);
            if ($(this).children("input").length > 0) {} else {
                var ima_code = $(this).text();
                var input_html = '<input type="text" value="' + ima_code + '" style="width:100%;" class="edit_input" />';
                $(this).html(input_html);
                $('.edit_input').focus();
                $('.edit_input').select();
            }

            function calculate_value(calculate_value) {
                for (var i in calculate_value) {
                    var inner_div = $("#" + calculate_value[i].label_name + '_' + data_id).children();
                    console.log(inner_div);
                    if (inner_div.length > 0) {
                        inner_div.html(calculate_value[i].new_value);
                    } else {
                        $("#" + calculate_value[i].label_name + '_' + data_id).html(calculate_value[i].new_value);
                    }
                }
            }

            $('.edit_input').blur(function() {
                var value = $(this).val();
                if (value == ima_code) {
                    target.html(ima_code);
                    return false;
                }
                $.get('/purchase/update-goods-label', {
                    value: value,
                    id: id,
                    data_id: data_id,
                    data_type: data_type
                }, function(result) {
                    if (result.error == 1) {
                        target.html(result.content);
                        if (result.calculate_value) {
                            calculate_value(result.calculate_value);
                        };
                    } else if (result.error == 3) {
                        target.html(ima_code);
                    } else {
                        target.html(ima_code);
                        layer.msg(result.message, function() {});
                    }
                }, 'json');
            });

            $('.edit_input').keydown(function(event) {
                if (event.keyCode == 13) {
                    event.stopPropagation();
                    event.preventDefault();
                    var value = $(this).val();
                    if (value == ima_code) {
                        target.html(ima_code);
                        return false;
                    }
                    $.get('/purchase/update-goods-label', {
                        value: value,
                        id: id,
                        data_id: data_id,
                        data_type: data_type
                    }, function(result) {
                        if (result.error == 1) {
                            target.html(result.content);
                            if (result.calculate_value) {
                                calculate_value(result.calculate_value);
                            };
                        } else if (result.error == 3) {
                            target.html(ima_code);
                        } else {
                            target.html(ima_code);
                            layer.msg(result.message, function() {});
                        }
                    }, 'json');
                }
            });

        }
    );

    $("#create_purchase_return_order").click(function() {

        var goods_id_arr = get_checked_goods();
        if (!goods_id_arr) {
            alert('请选择要操作的商品');
            return false;
        };

        if (confirm('要创建采购退货单吗？')) {
            $.get('<?= Url::to(["/purchase/create-purchase-return", "id" => $purchase->id]) ?>', {
                goods_id_arr: goods_id_arr
            }, function(result) {
                if (result.error == 1) {
                    layer.msg(result.message);
                } else {
                    layer.msg(result.message);
                }
            }, 'json');
        }
    });

    $("#purchase_unadmit").click(function() {
        if (confirm('要创建反复核吗？反复核之后，单据将回到前一次的复核状态')) {
            $.get('<?= Url::to(["/purchase/un-admit", "id" => $purchase->id]) ?>', function(result) {
                if (result.error == 1) {
                    layer.msg(result.message);
                } else {
                    layer.msg(result.message);
                }
            }, 'json');
        }
    });

    $("#create_import").click(function() {
        if (confirm('要创建入库单？')) {
            $.get('<?= Url::to(["/purchase/create-import", "id" => $purchase->id]) ?>', function(result) {
                if (result.error == 1) {
                    layer.msg(result.message);
                }
            }, 'json');
        }
    });

    function gradeChange(goods_id, val, purchase_id, purchase_goods_id) {
        var goods_id = goods_id;
        $.get('/purchase/change?purchase_goods_id=' + purchase_goods_id + '&platform_id=' + val, function(result) {
            if (result.error == 1) {
                layer.msg(result.message);
            } else {
                layer.msg(result.message);
            }
        }, 'json');
    }
    $("#create_payment").click(function() {
        //页面层
        layer.open({
            type: 2,
            title: '添加应付账款',
            //skin: 'layui-layer-rim', //加上边框
            area: ['80%', '80%'], //宽高
            maxmin: true,
            content: '../payment/create',
            end: function() {
                location.reload();
            }
        });
    });
</script>