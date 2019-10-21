<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <title>
    </title>

    <link rel="shortcut icon" href="favicon.ico">
    <link href="/css/bootstrap.min.css" rel="stylesheet"><link href="/css/font-awesome.min.css" rel="stylesheet"><link href="/css/animate.min.css" rel="stylesheet"><link href="/css/style.min.css" rel="stylesheet"><link href="/css/custom.css" rel="stylesheet">
    <script src="/js/custom.js"></script><script src="/js/jquery.min.js"></script><script src="/js/plugins/layer/layer.min.js"></script><script src="/js/jquery.jqprint-0.3.js"></script></head>
<body style="height:auto;" class="gray-bg">
<link href="/css/token_input/token-input.css" rel="stylesheet"><link href="/css/token_input/token-input-facebook.css" rel="stylesheet">
<script src="/js/jquery.tokeninput.min.js"></script>
<div class="ibox">
    <div class="ibox-content" style="padding-bottom:10px;">
        <form id="order_form" class="form-horizontal m-t"  enctype="multipart/form-data">
            <div class="form-group">
                <label class="col-sm-2 control-label"> 选择客户</label>
                <div class="col-sm-9">
                    <select class="form-control" name="ExportOrder[custom_id]">
                        <option value="">请选择</option>
                        <?php
                        if ($list){
                        foreach ($list as $k=>$v) {
                            ?>
                            <option value="<?php echo $v['id'] ?>"><?php echo $v['custom_name'] ?></option>
                            <?php
                        } }
?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label"> 配送方式</label>
                <div class="col-sm-9">
                    <select class="form-control" name="ExportOrder[shipping_method]">
                        <option value="">请选择</option>
                        <?php
                        if ($shiplist){
                            foreach ($shiplist as $key=>$val){
                                ?>

                                <option value="<?php echo $val['id'] ?>"><?php echo $val['dictionary_value'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"> 仓库</label>
                <div class="col-sm-9">
                    <select class="form-control" name="ExportOrder[store_id]">
                        <option value="">请选择</option>
                        <?php
                        if ($storelist){
                            foreach ($storelist as $va){
                                ?>

                                <option value="<?php echo $va['id'] ?>"><?php echo $va['store_name'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-2 control-label"> 物料费用</label>
                <div class="col-sm-9">
                    <input type="text" name="ExportOrder[materiel_fee]" class="form-control"  id=""/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"> 运输费用</label>
                <div class="col-sm-9">
                    <input type="text" name="ExportOrder[shipping_fee]" class="form-control"  id=""/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"> 发货单号</label>
                <div class="col-sm-9">
                    <input type="text" name="ExportOrder[shipping_code]" class="form-control"   id=""/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"> 备注</label>
                <div class="col-sm-9">
                    <input type="text" name="ExportOrder[remark]" class="form-control"  id=""/>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row" style="padding:10px 0px;margin-bottom:5em;">
    <div class="form-group">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-10">
            <button class="btn btn-danger" id="edit_export-order"><i class="icon-save"></i>保存</button>
        </div>
    </div>
</div>
<script>
    $("#edit_export-order").click(function(){
        var index = parent.layer.getFrameIndex(window.name);
        var formData = new FormData($("#order_form")[0]);
        var update_url = create_url('/export-order/insert');
        $.ajax({
            url: update_url ,
            type: 'POST',
            data: formData,
            dataType:'json',
            async: true,
            cache: false,
            contentType:false,
            processData: false,
            success: function (result) {
                if(result.error == 1){
                    parent.layer.msg(result.message);
                    parent.layer.close(index);

                }else{
                    layer.msg(result.message);
                }
            },
            error: function (result) {
                layer.msg('发生错误');
            }
        });
    });
</script><script type="text/javascript">
    $("#custom").tokenInput("/custom-order/token-custom-search",
        {
            theme:'facebook',
            hintText:'请输入要搜索的关键字',
            tokenLimit:1
        }
    );

</script>
</body>
</html>
