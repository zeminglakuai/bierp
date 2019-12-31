<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\config\sys_config;

?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>
<?= Html::jsFile('@web/ueditor/ueditor.config.js') ?>
<?= Html::jsFile('@web/ueditor/ueditor.all.min.js') ?>
<?= Html::jsFile('@web/ueditor/lang/zh-cn/zh-cn.js') ?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<script src="/js/bootstrap-datetimepicker.min.js"></script>
<script src="/js/bootstrap-datetimepicker.zh-CN.js"></script>
<link href="/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

<form method="post" id="order_form" class="form-horizontal" enctype="multipart/form-data">
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"> <a data-toggle="tab" href="#activity_name">基本信息</a></li>
            <?php if (isset($platform->id)) { ?>
                <li> <a data-toggle="tab" href="#pro_name">产品列表</a></li>

            <?php } ?>
            <?php if (isset($platform->id)) { ?>
                <li> <a data-toggle="tab" href="#contract_name">联系人列表</a></li>

            <?php } ?>
            <li> <a data-toggle="tab" href="#billing_name">结算信息</a></li>
            <?php if (isset($platform->id)) { ?>
                <li> <a data-toggle="tab" href="#plat_name">合同信息</a></li>
                <li> <a data-toggle="tab" href="#theme_name">主题活动</a></li>
                <!-- <li> <a data-toggle="tab" href="#period_name">数据分析</a></li> -->
            <?php } ?>
            <li> <a data-toggle="tab" href="#activity_desc">平台摘要</a></li>
        </ul>
        <div class="tab-content">
            <div id="activity_name" class="tab-pane active">
                <div class="panel-body">
                    <?= app\common\widgets\Input::widget(['label_name' => '平台名称', 'name' => "Platform[plat_name]", 'value' => $platform->plat_name, 'tips' => '']); ?>

                    <?= app\common\widgets\Radio::widget(
                        [
                            'label_name' => '合作形式',
                            'name' => "Platform[hezuoxingshi]",
                            'value' => $platform->hezuoxingshi,
                            'init_value' => [['label_name' => '积分', 'value' => '1'], ['label_name' => '现金', 'value' => '2'], ['label_name' => '积分+现金', 'value' => '3'], ['label_name' => '集采', 'value' => '4']]
                        ]
                    ); ?>



                    <?= app\common\widgets\Input::widget(['label_name' => '地址', 'name' => "Platform[address]", 'value' => $platform->address, 'tips' => '']); ?>
                    <?= app\common\widgets\Input::widget(['label_name' => '前端网站', 'name' => "Platform[website_front]", 'value' => $platform->website_front, 'tips' => '']); ?>

                    <?= app\common\widgets\Input::widget(['label_name' => '网站后台地址', 'name' => "Platform[login_user_name]", 'value' => $platform->login_user_name, 'tips' => '',]); ?>
                    <?= app\common\widgets\Input::widget(['label_name' => '登录账号', 'name' => "Platform[login_pass]", 'value' => $platform->login_pass, 'tips' => '']); ?>
                    <?= app\common\widgets\Input::widget(['label_name' => '备注', 'name' => "Platform[remark]", 'value' => $platform->remark, 'tips' => '']); ?>

                </div>
            </div>
            <?php if (isset($platform->id)) { ?>
                <div id="pro_name" class="tab-pane">
                    <div class="panel-body">

                        <form name="" id="goods_list_form">
                            <table class="table table-hover dataTable">
                                <thead id="goods_list_thead">
                                    <tr>
                                        <th width="50px;">
                                            <div class="checkbox i-checks">
                                                <label>
                                                    <div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" id="check_all" value="" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
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
                                            ID </th>
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
                                            商品名称 </th>
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
                                            商品型号 </th>
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
                                            条形码 </th>
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
                                                                    } ?>" data-type="platform_price" width="8%" style="width:8%">
                                            平台价 </th>


                                        <th align="center">操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($pg_list)) {
                                        foreach ($pg_list as $key => $value) {
                                    ?>
                                            <tr id="goods_row_494">
                                                <td>
                                                    <div class="checkbox i-checks"><label>
                                                            <div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" class="goods_ids" name="goods_id[]" value="4874" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div><?= $key + 1 ?>
                                                        </label></div>
                                                </td>
                                                <td>
                                                    <div id="id_<?= $value['goods_id'] ?>" title=""><?= $value['goods_id'] ?></div>
                                                </td>
                                                <td>
                                                    <div id="goods_name_<?= $value['goods_id'] ?>" title=""> <?= $value['goods_name'] ?></div>
                                                </td>
                                                <td>
                                                    <div id="goods_sn_<?= $value['goods_id'] ?>" title=""> <?= $value['goods_sn'] ?> </div>
                                                </td>
                                                <td>
                                                    <div id="isbn_<?= $value['goods_id'] ?>" title=""><?= $value['isbn'] ?> </div>
                                                </td>
                                                <td>
                                                    <div id="number_<?= $purchase_goods['id'] ?>" title="">
                                                        <div class="lable_edit" data-id="<?php echo $platform->id; ?>" id="<?= $value['goods_id'] ?>" data-type="number"><?= $value['platform_price'] ?></div>
                                                    </div>
                                                </td>

                                                <td><a class="delete_goods" data-id="<?= $value['goods_id'] ?>" action="delete-goods" href="javascript:void();">
                                                        <span class="glyphicon glyphicon-trash"></span> 删除</a></td>
                                            </tr>

                                    <?php     }
                                    }     ?>
                                </tbody>
                            </table>
                        </form>
                        <div class="row" style="margin-top:15px;">
                            <div class="col-sm-10"></div>
                            <div class="col-sm-2"><a class="btn btn-primary" id="create_platform_goods">+添加商品</a></div>
                        </div>
                    </div>
                </div>
                <div id="contract_name" class="tab-pane">
                    <div class="panel-body">
                        <?= app\common\widgets\ContactList::widget(['contact_list' => $platform->contactList, 'main_body' => $platform]); ?>
                        <div class="row" style="margin-top:15px;">
                            <div class="col-sm-10"></div>
                            <div class="col-sm-2"><a class="btn btn-primary" id="create_contact">+添加联系人</a></div>
                        </div>
                    </div>
                </div><?php } ?>
            <div id="billing_name" class="tab-pane">
                <div class="panel-body">
                    <?= app\common\widgets\Input::widget(['label_name' => '结算周期', 'name' => "Platform[period]", 'value' => $platform->period, 'tips' => '']); ?>
                    <?= app\common\widgets\Input::widget(['label_name' => '结算金额', 'name' => "Platform[yongjin]", 'value' => $platform->yongjin, 'tips' => '', 'id' => '']); ?>
                    <?= app\common\widgets\Input::widget(['label_name' => '结算说明', 'name' => "Platform[period_desc]", 'value' => $platform->period_desc, 'tips' => '']); ?>

                </div>
            </div>
            <?php if (isset($platform->id)) { ?>
                <div id="plat_name" class="tab-pane">
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td>合同名称</td>
                                    <td>开始日期</td>
                                    <td>结束日期</td>
                                    <td>操作</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($contract_list)) {
                                    foreach ($contract_list as $k => $val) { ?>
                                        <tr>
                                            <td><?= $val['contract_name'] ?></td>
                                            <td><?= $val['start_time'] ?></td>
                                            <td><?= $val['end_time'] ?></td>
                                            <td>
                                                <a class="btn btn-xs btn-primary edit_contract" href="javascript:void();" origin-id="<?= $platform->id ?>" data-id="<?= $val['id'] ?>"><span class="glyphicon glyphicon-edit"></span> 编辑</a>
                                                <a class="btn btn-xs btn-danger delete_contract" href="javascript:void();" origin-id="<?= $platform->id ?>" data-id="<?= $val['id'] ?>"><span class="glyphicon glyphicon-trash"></span> 删除</a>
                                            </td>
                                        </tr>
                                <?php            }
                                }
                                ?>

                            </tbody>

                        </table>
                        <div class="row" style="margin-top:15px;">
                            <div class="col-sm-10"></div>
                            <div class="col-sm-2"><a class="btn btn-primary" id="create_contract">+添加合同</a></div>
                        </div>

                    </div>
                </div>

                <div id="theme_name" class="tab-pane">
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td>主题名称</td>
                                    <td>开始日期</td>
                                    <td>结束日期</td>
                                    <td>操作</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($theme_list)) {
                                    foreach ($theme_list as $key => $value) {
                                ?>
                                        <tr>
                                            <td><?= $value['theme_name'] ?></td>
                                            <td><?= $value['start_time'] ?></td>
                                            <td><?= $value['end_time'] ?></td>
                                            <td>


                                                <a class="btn btn-xs btn-primary edit_theme" href="javascript:void();" origin-id="<?= $platform->id ?>" data-id="<?= $value['id'] ?>"><span class="glyphicon glyphicon-edit"></span> 编辑</a>
                                                <a class="btn btn-xs btn-primary add_theme_goods" href="javascript:void();" origin-id="<?= $platform->id ?>" data-id="<?= $value['id'] ?>"><span class="glyphicon glyphicon-edit"></span> 添加商品</a>
                                                <a class="btn btn-xs btn-danger delete_theme" href="javascript:void();" origin-id="<?= $platform->id ?>" data-id="<?= $value['id'] ?>"><span class="glyphicon glyphicon-trash"></span> 删除</a>
                                            </td>
                                        </tr>
                                <?php            }
                                }
                                ?>

                            </tbody>

                        </table>
                        <div class="row" style="margin-top:15px;">
                            <div class="col-sm-10"></div>
                            <div class="col-sm-2"><a class="btn btn-primary" id="create_theme">+添加主题</a></div>
                        </div>
                    </div>

                </div>
            <?php } ?>


            <!-- <div id="period_name" class="tab-pane">
                <div class="panel-body">

                </div>
            </div> -->
            <div id="activity_desc" class="tab-pane">
                <div class="panel-body">
                    <Div class="row">
                        <div class="col-xs-1">平台信息</div>
                        <div class="col-xs-11">
                            <script type="text/plain" id="editor" name="Platform[plat_info]" style="height:300px;"><?= $platform->plat_info ?></script>
                        </div>
                    </Div>
                    <script type="text/javascript">
                        var editor = UE.getEditor('editor');
                    </script>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="row" style="padding:10px 0px;margin-bottom:5em;">
    <div class="form-group">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-10">
            <input type="hidden" name="id" value="5">
            <button class="btn btn-danger" id="update"><i class="icon-save"></i>
                <?php if (isset($platform->id)) { ?>
                    保存并刷新
                <?php } else { ?>
                    保存
                <?php    } ?>

            </button>
        </div>
    </div>
</div>
<?php // app\common\widgets\Submit::widget(['model' => $platform, 'model_name' => "platform", 'form_name' => 'order_form']); 
?>
<script type="text/javascript">
    $("#update").click(function() {
        var index = parent.layer.getFrameIndex(window.name);
        var formData = new FormData($("#order_form")[0]);
        <?php if (isset($platform->id)) { ?>
            var update_url = create_url('/platform/update') + "id=<?= $platform->id ?>";
        <?php } else { ?>
            var update_url = create_url('/platform/insert');
        <?php    } ?>
        $.ajax({
            url: update_url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function(result) {
                if (result.error == 1) {
                    <?php if (isset($platform->id)) { ?>
                        location.reload();
                    <?php } else { ?>
                        parent.layer.close(index);
                    <?php    } ?>


                } else {
                    layer.msg(result.message);
                }
            },
            error: function(result) {
                layer.msg('发生错误');
            }
        });
    });

    $("#custom").tokenInput("<?= Url::to(['/platform/token-custom-search']) ?>", {
        theme: 'facebook',
        hintText: '请输入要搜索的关键字',
        tokenLimit: 1
        <?php if ($platform->custom_id >= 1) { ?>,
            prePopulate: [{
                id: '<?= $platform->custom_id ?>',
                name: '<?= $platform->custom_name ?>'
            }],
        <?php } ?>
    });


    // 创建合同
    $("#create_contract").click(function() {

        //页面层
        layer.open({
            type: 2,
            title: '添加合同',
            //skin: 'layui-layer-rim', //加上边框
            area: ['80%', '80%'], //宽高
            maxmin: true,
            content: '/platform/create-contract?id=<?= $platform->id; ?>',
            end: function() {
                // location.reload();
            }
        });
    });
    // 编辑合同
    $(".edit_contract").click(function() {
        //页面层
        var data_id = $(this).attr('data-id');
        var id = $(this).attr('origin-id');
        var edit_url = create_url('<?= "/platform/edit-contract" ?>');
        layer.open({
            type: 2,
            title: '编辑合同',
            area: ['90%', '80%'], //宽高
            maxmin: true,
            content: edit_url + 'contract_id=' + data_id + '&id=' + id
        });
    });
    // 创建主题
    $("#create_theme").click(function() {
        //页面层
        layer.open({
            type: 2,
            title: '添加主题',
            //skin: 'layui-layer-rim', //加上边框
            area: ['80%', '80%'], //宽高
            maxmin: true,
            content: '/platform/create-theme?id=<?= $platform->id; ?>',
            end: function() {
                // location.reload();
            }
        });
    });
    $(".edit_theme").click(function() {
        //页面层
        var data_id = $(this).attr('data-id');
        var id = $(this).attr('origin-id');
        var edit_url = create_url('<?= "/platform/edit-theme" ?>');
        layer.open({
            type: 2,
            title: '编辑主题活动',
            area: ['90%', '80%'], //宽高
            maxmin: true,
            content: edit_url + 'theme_id=' + data_id + '&id=' + id
        });
    });
    // $(".add_theme_goods").click(function() {
    // 	//页面层
    // 	var data_id = $(this).attr('data-id');
    // 	var id = $(this).attr('origin-id');
    // 	var edit_url = create_url('<?= "/platform/add-theme-goods" ?>');
    // 	layer.open({
    // 		type: 2,
    // 		title: '添加活动商品',
    // 		area: ['90%', '80%'], //宽高
    // 		maxmin: true,
    // 		content: edit_url + 'theme_id=' + data_id + '&id=' + id
    // 	});
    // });
    $(".add_theme_goods").click(function() {
        var id = $(this).attr('origin-id');
        var data_id = $(this).attr("data-id");
        //页面层
        layer.open({
            type: 2,
            title: '添加商品',
            //skin: 'layui-layer-rim', //加上边框
            area: ['80%', '80%'], //宽高
            maxmin: true,
            content: '/platform/create-goods-platform?theme_id=' + data_id + '&platform_id=' + id,
            end: function() {
                // location.reload();
            }
        });
    });
    $(".create_platform_goods").click(function() {
        var id = $(this).attr('origin-id');
        var data_id = $(this).attr("data-id");
        //页面层
        layer.open({
            type: 2,
            title: '添加商品',
            //skin: 'layui-layer-rim', //加上边框
            area: ['80%', '80%'], //宽高
            maxmin: true,
            content: '/platform/create-goods-platform?theme_id=' + data_id + '&platform_id=' + id,
            end: function() {
                // location.reload();
            }
        });
    });
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

            var data_id = $(this).attr("data-id");
            var id = $(this).attr("id");
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
                $.get('/platform/update-goods-label', {
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
                    $.get('/platform/update-goods-label', {
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

    $("#create_contact").click(function() {
        //页面层
        layer.open({
            type: 2,
            title: '添加联系人',
            area: ['90%', '80%'], //宽高
            maxmin: true,
            content: '<?= Url::to(["/platform/create-contact", "id" => $platform->id]) ?>'
        });
    });

    $(".edit_contact").click(function() {
        //页面层
        var data_id = $(this).attr('data-id');
        var id = $(this).attr('origin-id');
        var edit_url = create_url('<?= "/platform/edit-contact" ?>');
        layer.open({
            type: 2,
            title: '编辑联系人',
            area: ['90%', '80%'], //宽高
            maxmin: true,
            content: edit_url + 'contact_id=' + data_id + '&id=' + id
        });
    });

    $(".delete_contact").click(function() {
        var data_id = $(this).attr('data-id');
        var id = $(this).attr('origin-id');
        if (confirm("确认删除？")) {
            $.get('/platform/delete-contact', {
                id: id,
                contact_id: data_id
            }, function(result) {
                if (result.error == 1) {
                    // window.location.reload();
                } else {
                    layer.msg(result.message);
                };
            }, 'json');
        };
    });
</script>