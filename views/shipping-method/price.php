<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\common\config\lang_value_config;


$this->params['breadcrumbs'][] = $this->context->page_title;
?>


<div class="row border-bottom white-bg page-heading">
    <div style="padding:5px 0;">
        <ul class="breadcrumb">
            <li class='bread_li'><a href="#">当前位置</a></li>
            <li class="active">配送方案</li>
        </ul>
    </div>
</div>
<div class="ibox">
    <div class="ibox-content" style="padding-bottom:10px;">
        <form id="order_form" class="form-horizontal m-t" enctype="multipart/form-data">
            <div class="form-group">
                <label class="col-sm-2 control-label"> 快递名称：</label>
                <div class="col-sm-9">
                    <?= $shipping_config['shipping_name'] ?>
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-2 control-label"> 区域</label>
                <div class="col-sm-9">
                    <input type="text" name="ShippingMethodConfig[area_desc]" class="form-control" value="" id="supplier">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"> 首重金额</label>
                <div class="col-sm-9">
                    <input type="text" name="ShippingMethodConfig[basic_price]" class="form-control" value="" id="supplier">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"> 续重(每千克)</label>
                <div class="col-sm-9">
                    <input type="text" name="ShippingMethodConfig[per_kg_price]" class="form-control" value="" id="">
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row" style="padding:10px 0px;margin-bottom:5em;">
    <div class="form-group">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-10">
            <button class="btn btn-danger" id="add_shipping-method"><i class="icon-save"></i>添加</button>
        </div>
    </div>
</div>
<script>
    $("#add_shipping-method").click(function() {
        var formData = new FormData($("#order_form")[0]);
        $.ajax({
            url: '/shipping-method/insert',
            type: 'POST',
            data: formData,
            dataType: 'json',
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function(result) {
                if (result.error == 1) {
                    parent.layer.msg(result.message);
                    parent.location.reload();
                } else {
                    layer.msg(result.message);
                }
            },
            error: function(result) {
                layer.msg('发生错误');
            }
        });
    });
</script>