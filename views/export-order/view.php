<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <title>
    </title>
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html"/>
    <![endif]-->

    <link rel="shortcut icon" href="favicon.ico">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/font-awesome.min.css" rel="stylesheet">
    <link href="/css/animate.min.css" rel="stylesheet">
    <link href="/css/style.min.css" rel="stylesheet">
    <link href="/css/custom.css" rel="stylesheet">
    <link href="/css/token_input/token-input.css" rel="stylesheet">
    <link href="/css/token_input/token-input-facebook.css" rel="stylesheet">
    <script src="/js/jquery.min.js"></script>
    <script src="/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/plugins/layer/layer.min.js"></script>
    <script src="/js/custom.js"></script>
    <script src="/js/jquery.tokeninput.min.js"></script>
</head>
<!--  oncontextmenu="window.event.returnValue=false" oncontextmenu="return false" onselectstart="return false" oncopy="return false" -->
<body class="gray-bg">
<div class="row border-bottom white-bg page-heading">
    <div style="padding:5px 0;">
        <ul class="breadcrumb">
            <li class='bread_li'><a href="#">当前位置</a></li>
            <li class='bread_li'><a href="/export-order/index">出库单列表</a></li>
            <li class="active"><?= $export_order['order_sn'] ?></li>
        </ul>
    </div>
</div>

<div class="ibox">
    <div class="ibox-content" style="padding-bottom:10px;">
        <div class="row">
            <div class="col-sm-9">


                <div class="col-sm-3 m-b-1">
                    客户名称 :
                    <span>
                                      <a href="javascript:void();" id="custom" data-id="24">
                                  <?= $export_order['custom_name'] ?>                                         </a>
                                  </span>
                </div>


                <!--如果存在链接 就设置链接-->
                <script>
                    $('#custom').click(function () {
                        var data_id = $(this).attr('data-id');
                        var update_url = create_url('/custom/view');
                        //页面层
                        layer.open({
                            type: 2,
                            title: '',
                            skin: 'layui-layer-rim', //加上边框
                            area: ['90%', '80%'], //宽高
                            content: data_id > 0 ? update_url + 'id=' + data_id : update_url
                        });
                    });
                </script>


                <div class="col-sm-3 m-b-1">

                    配送方式:<?= $export_order['shipping_method'] ?>                              </div>


                <div class="col-sm-3 m-b-1">

                    运费:<?= $export_order['shipping_fee'] ?>                                  </div>


                <div class="col-sm-3 m-b-1">

                    发货单号:<?= $export_order['shipping_code'] ?>                                </div>


                <div class="col-sm-12 m-b-1">
                    备注:<?= $export_order['remark'] ?>              </div>


                <div class="row">
                    <div class="col-sm-11">

                    </div>
                    <div class="col-sm-1">

                        <button class="btn btn-primary btn-sm" id="order_edit">编辑</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 text-right">
                <p><span class="padding-lr-5">单号:<b class="blue"><?= $export_order['order_sn'] ?></b></span></p>
                <p><span class="padding-lr-5">状态:</span></p>
                <p>
                    <span class="padding-lr-5">创建人:<?= $export_order['add_user_name'] ?>/<?= $export_order['depart_name'] ?></span><span
                            class="padding-lr-5">日期:<?php date("Y-m-d H:i", $export_order['add_time']); ?></span></p>
            </div>
        </div>


    </div>
</div>

<script type="text/javascript">
    $('#order_edit').click(function () {
        var update_url = create_url('/export-order/edit?id=' + id);
        //页面层
        layer.open({
            type: 2,
            title: '编辑单据',
            skin: 'layui-layer-rim', //加上边框
            area: ['80%', '80%'], //宽高
            content: update_url
        });
    });

</script>

<link href="/css/plugins/iCheck/custom.css" rel="stylesheet">
<link href="/css/plugins/switchery/switchery.css" rel="stylesheet">
<script src="/js/plugins/iCheck/icheck.min.js"></script>
<div style="overflow:auto;width:100%;margin-bottom:5em;" id="goods_list_block">
    <div class="ibox" style="">
        <div class="ibox-content" style="padding:10px 5px 5px 10px;">
            <form name="" id="goods_list_form">
                <table class="table table-hover dataTable">
                    <thead id="goods_list_thead" style="">
                    <tr>
                        <th width="50px;">
                            <div class="checkbox i-checks">
                                <label><input type="checkbox" id="check_all" value=""></label>
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
                        } ?>" data-type="goods_name" width="20%" style="width:20%">
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
                        } ?>" data-type="isbn" width="8%" style="width:8%">
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
                            市场价
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
                        } ?>" data-type="send_number" width="10%" style="width:10%">
                            实发数量
                        </th>
                        <th align="center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    if ($customers) {
                        foreach ($customers as $k => $v) {
                            ?>
                            <tr id="goods_row_<?= $v['goods_id'] ?>">
                                <td>
                                    <div class="checkbox i-checks">
                                        <label>
                                            <input type="checkbox" class="goods_ids" name="goods_id[]" value="7234">
                                            <?= $k + 1 ?>                    </label>
                                    </div>
                                </td>

                                <td>
                                    <div id="id_<?= $v['goods_id'] ?>" title="">
                                        <?= $v['goods_id'] ?>
                                    </div>
                                </td>
                                <td>
                                    <div id="goods_name_<?= $v['goods_id'] ?>" title="">
                                        <?= $v['goods_name'] ?>
                                    </div>
                                </td>
                                <td>
                                    <div id="goods_sn_<?= $v['goods_id'] ?>" title="">
                                        <?= $v['goods_sn'] ?>
                                    </div>
                                </td>
                                <td>
                                    <div id="isbn_ <?= $v['goods_id'] ?>" title="">
                                        <?= $v['isbn'] ?>

                                    </div>
                                </td>
                                <td>
                                    <div id="market_price_<?= $v['goods_id'] ?>" title="">
                                        <?= $v['market_price'] ?>
                                    </div>
                                </td>
                                <td>
                                    <div id="number_<?= $v['goods_id'] ?>" title="">
                                        <?= $v['num'] ?>
                                    </div>
                                </td>
                                <td>
                                    <div id="send_number_ <?= $v['goods_id'] ?>" title="">
                                        <div class="lable_edit" data-id="<?= $v['goods_id'] ?>"
                                             data-type="send_number"><?php if ($v['number']) {
                                                echo $v['number'];
                                            } ?></div>
                                    </div>
                                </td>

                                <td>
                                    <a class="delete_goods" data-id="<?= $v['goods_id'] ?>" action="delete-goods"
                                       href="javascript:void();">
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

<script type="text/javascript">

    $("#check_all").on('ifChecked', function (event) {
        $(".goods_ids").iCheck('check');
    });

    $("#check_all").on('ifUnchecked', function (event) {
        $(".goods_ids").iCheck('uncheck');
    });

    $('.goods_ids').on('ifChecked', function (event) {
        $(this).parent().parent().parent().parent().parent().css('background-color', '#f5f5f5')
    });
    $('.goods_ids').on('ifUnchecked', function (event) {
        $(this).parent().parent().parent().parent().parent().css('background-color', '')
    });

    $(".data_sort").click(function () {
        var sortby = $(this).attr('data-type');
        var order = $(this).attr("class") == 'sorting_desc data_sort' ? 'SORT_ASC' : 'SORT_DESC';
        var to_url = '/export-order/view?id=' + id;
        to_url = create_url(to_url);
        window.location.href = to_url + 'sortby=' + sortby + '&order=' + order;
    });

    $(".delete_goods").click(function () {
        if (confirm('要删除该记录吗？')) {
            var id = <?=$id?>;
            var data_id = $(this).attr('data-id');
            var action = $(this).attr('action');
            $.get("/export-order/" + action, {id: id, data_id: data_id}, function (result) {
                if (result.error == 1) {
                    $("#goods_row_" + data_id).remove();
                } else {
                    layer.msg(result.message);
                }
            }, 'json');
        }
    });

    $(".lable_edit").hover(
        function () {
            $(this).addClass('lable_edit_over');
        }, function () {
            $(this).removeClass('lable_edit_over');
        }
    );

    $(".goods_list_change").change(function () {
        var id = <?=$id?>;
        var value = $(this).val();
        var data_id = $(this).attr("data-id");
        var data_type = $(this).attr("data-type");
        var target = $(this);
        $.get('/export-order/update-goods-label', {
            value: value,
            id: id,
            data_id: data_id,
            data_type: data_type
        }, function (result) {
            if (result.error == 1) {
                layer.msg(result.message);
            } else {
                layer.msg(result.message, function () {
                });
            }
        }, 'json');
    })


    $(".lable_edit").click(
        function () {
            var id = <?=$id?>;
            var data_id = $(this).attr("data-id");
            var data_type = $(this).attr("data-type");
            var target = $(this);
            if ($(this).children("input").length > 0) {
            } else {
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

            $('.edit_input').blur(function () {
                var value = $(this).val();
                if (value == ima_code) {
                    target.html(ima_code);
                    return false;
                }
                $.get('/export-order/update-goods-label', {
                    value: value,
                    id: id,
                    data_id: data_id,
                    data_type: data_type
                }, function (result) {
                    if (result.error == 1) {
                        target.html(result.content);
                        if (result.calculate_value) {
                            calculate_value(result.calculate_value);
                        }
                        ;
                    } else if (result.error == 3) {
                        target.html(ima_code);
                    } else {
                        target.html(ima_code);
                        layer.msg(result.message, function () {
                        });
                    }
                }, 'json');
            });

            $('.edit_input').keydown(function (event) {
                if (event.keyCode == 13) {
                    event.stopPropagation();
                    event.preventDefault();
                    var value = $(this).val();
                    if (value == ima_code) {
                        target.html(ima_code);
                        return false;
                    }
                    $.get('/export-order/update-goods-label', {
                        value: value,
                        id: id,
                        data_id: data_id,
                        data_type: data_type
                    }, function (result) {
                        if (result.error == 1) {
                            target.html(result.content);
                            if (result.calculate_value) {
                                calculate_value(result.calculate_value);
                            }
                            ;
                        } else if (result.error == 3) {
                            target.html(ima_code);
                        } else {
                            target.html(ima_code);
                            layer.msg(result.message, function () {
                            });
                        }
                    }, 'json');
                }
            });

        }
    );

    $(".view_label_content").click(function () {
        var view_url = $(this).attr('data-url');
        var view_id = $(this).attr('data-id');
        layer.open({
            type: 2,
            title: '查看',
            area: ['90%', '90%'], //宽高
            maxmin: true,
            content: view_url + '?id=' + view_id
        });
    })

    $(function () {
        $("[data-toggle='tooltip']").tooltip();
    });

    $(document).ready(function () {

            $(".i-checks").iCheck({checkboxClass: "icheckbox_square-green", radioClass: "iradio_square-green",})
        }
    );
</script>

<script type="text/javascript">
    var ori_xx = 0
    var ori_yy = 0
    var move_goods_list = function (e) {
        var new_xx = e.originalEvent.x || e.originalEvent.layerX || 0;
        var new_yy = e.originalEvent.y || e.originalEvent.layerY || 0;

        var move_px_x = (new_xx - ori_xx) * 2;
        var move_px_y = (new_yy - ori_yy) * 2;
        if (ori_xx > 0 && e.data.type == 'x') {
            $("#goods_list_block").scrollLeft($("#goods_list_block").scrollLeft() + move_px_x)
            //$("body").scrollTop($("body").scrollTop() + move_px_y)
        }

        if (ori_yy > 0 && e.data.type == 'y') {
            $("body").scrollTop($("body").scrollTop() + move_px_y)
        }

        ori_xx = new_xx;
        ori_yy = new_yy;

        console.log(new_xx + '-------' + new_yy);
    };

    document.onkeydown = function (event) {
        if (event.target.nodeName == 'TEXTAREA' || event.target.nodeName == 'INPUT') {
            return;
        }
        ;

        if (event.keyCode == 32) {
            event.preventDefault();
            $("body").css('cursor', 'move');
            var ori_xx = 0;
            var ori_yy = 0;
            $('#goods_list_block').on('mousemove', {type: 'x'}, move_goods_list);
        }
        ;

        if (event.keyCode == 16) {
            event.preventDefault();
            $("body").css('cursor', 'move');
            var ori_xx = 0;
            var ori_yy = 0;
            $('#goods_list_block').on('mousemove', {type: 'y'}, move_goods_list);
        }
        ;


    }

    document.onkeyup = function (event) {
        if (event.target.nodeName == 'TEXTAREA' || event.target.nodeName == 'INPUT') {
            return;
        }
        ;
        /* Act on the event */
        if (event.keyCode == 32 || event.keyCode == 16) {
            event.preventDefault();
            $("body").css('cursor', 'auto');
            $('#goods_list_block').off()
            ori_xx = 0
            ori_yy = 0
        }
        ;

    }

</script>


<div class="oprate_bar">
    <div class="row">
        <div class="col-sm-3">
            <a id="create_sell_order_goods" class="btn btn-warning btn-sm"><i class="fa fa-plus"></i> 添加商品</a>


            <div class="btn-group dropup">
                <button data-toggle="dropdown" class="btn btn-warning btn-sm dropdown-toggle">导出EXCEL <span
                            class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                </ul>
            </div>


        </div>
        <div class="col-sm-4">
            <?php if( $export_order['export_order_status']>=1){
?><button disabled="disabled" class="btn btn-danger btn-sm admit_btn" data-status="1" data-toggle="tooltip" data-placement="top" data-original-title="由部门经理复核">
                    <i class="fa fa-check-square"></i>
                    已复核
                </button>
            <?php
            }else{ ?>

                <button class="btn btn-danger btn-sm admit_btn" data-status="1" data-toggle="tooltip" data-placement="top" data-original-title="由部门经理复核">
                    <i class="fa fa-check-square"></i>
                    1. 复核
                </button>
            <?php } ?>

        </div>

        <div class="col-sm-3">
            <a id="reload_store_code" class="btn btn-danger btn-sm"><i class="fa fa-yen"></i> 重载库位信息</a>
        </div>

        <div class="col-sm-2">
            <button type="button" id="refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i></button>
            <a href="/export-order" class="btn btn-primary btn-sm"><i class="fa fa-mail-reply"></i></a>
        </div>
    </div>
</div>

<script>
    var id =<?=$id?>;
    $("#create_sell_order_goods").click(function () {
        //页面层
        layer.open({
            type: 2,
            title: '添加商品',
            //skin: 'layui-layer-rim', //加上边框
            area: ['80%', '80%'], //宽高
            maxmin: true,
            content: '/export-order/create-goods?order_id=' + id,
            end: function () {
                location.reload();
            }
        });
    });

    function check_if_disabled(ob) {
        if (ob.hasClass('disabled')) return true;
        return false;
    }

    // $(".admit_btn").click(function(){
    //   if (check_if_disabled($(this))) return false;
    //   if(confirm('确认通过审核？')){
    //     var process_status = $(this).attr('data-status');
    //     var url = '/export-order/admit?id=101';
    //     $.get(url,{process_status:process_status},function(result){
    //       if(result.error == 1){
    //         layer.msg(result.message, function(){
    //           location.reload();
    //         });
    //       }else{
    //         $("#goods_row_"+result.content).addClass('danger');
    //         setTimeout('$("#goods_row_"+'+result.content+').removeClass("danger")',3000)
    //         layer.msg(result.message, function(){});
    //       }
    //     },'json');
    //   }
    // });

    $(".admit_btn").click(function () {
        //页面层
        var process_status = $(this).attr('data-status');
        var url = '/export-order/admit?id=' + id;
        layer.open({
            type: 2,
            title: $(this).text(),
            //skin: 'layui-layer-rim', //加上边框
            area: ['950px', '320px'], //宽高
            content: 'to-admit?process_status=' + process_status + '&url=' + encodeURIComponent(url)
        });
    });


    $('#refresh').click(function () {
        location.reload();
    })
</script>
<script type="text/javascript">
    $("#reload_store_code").click(function () {
        if (confirm('要重载库位信息吗？')) {
            $.get('/export-order/reload-store-code?id=' + id, function (result) {
                if (result.error == 1) {
                    layer.msg(result.message, function () {
                        location.reload();
                    });
                } else {
                    layer.msg(result.message);
                }
            }, 'json');
        }
    });
</script>


<div id="small-chat">
    <span class="badge badge-warning pull-right"></span>
    <a class="open-small-chat">
        <i class="fa fa-h-square"></i>
    </a>
</div>

<script>

    $('#turn_back').click(function () {
        history.go(-1);
    })
    $("#small-chat").click(function () {
        //页面层
        var model = 'export-order';
        layer.open({
            type: 2,
            title: '帮助文档',
            //skin: 'layui-layer-rim', //加上边框
            area: ['80%', '80%'], //宽高
            maxmin: true,
            content: '/help/view?model=export-order'
        });
    });
</script>
</body>
</html>
