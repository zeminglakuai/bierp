<?php

use yii\widgets\LinkPager;

?>
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
            <li class="active">库存查询</li>
        </ul>
    </div>
</div>

<script src="/js/bootstrap-datetimepicker.min.js"></script>
<script src="/js/bootstrap-datetimepicker.zh-CN.js"></script>
<link href="/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<div class="ibox">
    <div class="ibox-content" style="padding-bottom:10px;position:relative;">
        <form action="/storage/index" method="get" class="form-horizontal">
            <div class="row" style="padding:0 -10px;padding:0 10px;" id="basic_search_confition">
                <div class="col-sm-3">
                    <div class="form-group mgb5">
                        <label class="col-sm-3 control-label">商品名称</label>
                        <div class="col-sm-9">
                            <input type="text" name="goods_name" id="" class="form-control" value=""
                                   placeholder="商品名称"/>
                            <input type="hidden" name="type" id="" class="form-control" value="<?= $type ?>"/>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group mgb5">
                        <label class="col-sm-3 control-label">仓库</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="store_id">
                                <option value="">请选择仓库</option>
                                <option value="1">西库</option>
                                <option value="2">邮政仓库</option>
                                <option value="3">保险</option>
                            </select></div>
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-sm-11">
                </div>
                <div class="col-sm-1">
                    <button type="submit" class="btn btn-primary btn-sm"/>
                    <span class="glyphicon glyphicon-search"></span> 搜索</button>
                </div>
            </div>
        </form>

    </div>

</div>


<div class="ibox">
    <div class="ibox-content" style="padding-bottom:10px;">
        <table class="table table-hover dataTable">
            <thead>
            <tr>
                <th align="center">商品ID</th>
                <th align="center">商品名称</th>
                <th align="center">型号</th>
                <th align="center">条形码</th>
                <th align="center">数量</th>
                <th align="center">仓库</th>
                <th align="center">天数</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($data_list) {
                foreach ($data_list as $kk => $vv) {
                    ?>
                    <tr>
                        <td> <?= $vv['goods_id'] ?></td>
                        <td> <?= $vv['goods_name'] ?></td>
                        <td> <?= $vv['goods_sn'] ?></td>
                        <td> <?= $vv['isbn'] ?></td>
                        <td>
                            <?php
                            if($vv['data']){
                                foreach ($vv['data'] as $k =>$v){
                                    echo $v['number'].'</br>';
                                }
                            }
                            ?>
                            </td>
                        <td> <?php
                            if($vv['data']){
                                foreach ($vv['data'] as $k =>$v){
                                    echo $v['store_name'].'</br>';
                                }
                            }
                            ?></td>
                        <td> <?php
                            if($vv['data']){
                                foreach ($vv['data'] as $k =>$v){
                                    echo $v['time'].'</br>';
                                }
                            }
                            ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>



<script>
    /*$(function () { $("[data-toggle='tooltip']").tooltip(); });*/
</script>
<?php
if ($type != 2) {
    ?>
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
   <!-- <a >开始盘点</a>
    <a>结束盘点</a>-->

    <form action="/supplier-price/import-stock" method="post" class="form-horizontal" enctype="multipart/form-data">

        <div class="row" style="margin-bottom:5em;">
            <div class="col-sm-4">
                <input type="file" name="Goods[ppt_file]" class="form-control" align="left"/>

            </div>
            <div class="col-sm-3">
                <input type="submit" name="submit" value="导入产品"/>
                <a href="/storage/exports?template_id=89&module_name=storage&type=title"
                   class="btn btn-danger btn-sm"><i class="fa fa-plus"></i> 库存导出</a>

            </div>

        </div>
    </form>


    <div class="oprate_bar">
        <div class="row">
            <div class="col-sm-3">


                <div class="btn-group dropup">
                    <button data-toggle="dropdown" class="btn btn-warning btn-sm dropdown-toggle">导出EXCEL <span
                                class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="/storage/export?template_id=89&module_name=storage&type=title"
                               target="_blank">库存盘点</a></li>
                    </ul>
                </div>


            </div>
            <div class="col-sm-4">
            </div>

            <div class="col-sm-3">
            </div>

            <div class="col-sm-2">
                <button type="button" id="refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i></button>
            </div>
        </div>
    </div>
    <?php
}
?>
<script>


    $('#refresh').click(function () {
        location.reload();
    })
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
        var model = 'storage';
        layer.open({
            type: 2,
            title: '帮助文档',
            //skin: 'layui-layer-rim', //加上边框
            area: ['80%', '80%'], //宽高
            maxmin: true,
            content: '/help/view?model=storage'
        });
    });
</script>
</body>
</html>
