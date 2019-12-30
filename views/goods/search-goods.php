<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use app\common\models\Brand;

?>
<?= Html::cssFile('@web/css/plugins/iCheck/custom.css') ?>
<?= Html::jsFile('@web/js/plugins/iCheck/icheck.min.js') ?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
    <form action="<?= $url ?>" method="get" class="form-horizontal">
      <div class="row">
        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">商品分类</label>
            <div class="col-sm-9">
              <Select class="form-control" name="category">
                <option value="0">商品分类</option>
                <?= $cat_list ?>
              </Select>
            </div>
          </div>
        </div>



        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">商品ID</label>
            <div class="col-sm-9">
              <input type="text" name="goods_id" class="form-control" value="<?= $search_data['goods_id'] ?>" placeholder="商品ID" />
            </div>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">商品名称</label>
            <div class="col-sm-9">
              <input type="text" name="goods_name" class="form-control" value="<?= $search_data['goods_name'] ?>" placeholder="商品名称" />
            </div>
          </div>
        </div>

        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">品牌</label>
            <div class="col-sm-9">
              <input type="text" name="brand_id" id="brand" class="form-control" size="25" placeholder="商品品牌" value="<?= $search_data['brand_id'] ?>" />
              <script type="text/javascript">
                $(document).ready(function() {
                  $("#brand").tokenInput(<?= Brand::getTokeninputlist() ?>, {
                    theme: 'facebook',
                    <?php if ($search_data['brand_id'] >= 1) { ?>prePopulate: [{
                      id: '<?= $search_data['brand_id'] ?>',
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
        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">商品类型</label>
            <div class="col-sm-9">
              <Select class="form-control" name="type">
                <option value="0">商品类型</option>
                <option value="1" <?php if ($search_data['type'] == 1) {
                                    echo 'selected="selected"';
                                  } ?>>商品</option>
                <option value="2" <?php if ($search_data['type'] == 2) {
                                    echo 'selected="selected"';
                                  } ?>>样品</option>
              </Select>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">供货商</label>
            <div class="col-sm-9">
              <input type="text" name="supplier" id="supplier" class="form-control" placeholder="供货商" />
              <script type="text/javascript">
                $(document).ready(function() {
                  $("#supplier").tokenInput("<?= $supplier_url ?>", {
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
              <input type="text" size="8" name="purchase_start" class="form-control" value="<?= $search_data['purchase_start'] ?>" placeholder="采购价开始" style="width:42%;display:inline;" />
              -
              <input type="text" size="8" name="purchase_end" class="form-control" value="<?= $search_data['purchase_end'] ?>" placeholder="采购价结束" style="width:42%;display:inline;" />
            </div>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">市场价</label>
            <div class="col-sm-9">
              <input type="text" size="8" name="market_start" class="form-control" value="<?= $search_data['market_start'] ?>" placeholder="市场价开始" style="width:42%;display:inline;" />
              -
              <input type="text" size="8" name="market_end" class="form-control" value="<?= $search_data['market_end'] ?>" placeholder="市场价结束" style="width:42%;display:inline;" />
            </div>
          </div>
        </div>

        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">销售价</label>
            <div class="col-sm-9">
              <input type="text" size="8" name="sale_start" class="form-control" value="<?= $search_data['sale_start'] ?>" placeholder="销售价开始" style="width:42%;display:inline;" />
              -
              <input type="text" size="8" name="sale_end" class="form-control" value="<?= $search_data['sale_end'] ?>" placeholder="销售价结束" style="width:42%;display:inline;" />
            </div>
          </div>
        </div>

      </div>

      <div class="row">
        <div class="col-sm-11"> </div>
        <div class="col-sm-1">
          <input type="hidden" name="order_id" value="<?= $order_id ? $order_id : $search_data['order_id'] ?>" />
          <input type="submit" class="btn btn-primary btn-sm" value="搜索" />
        </div>
      </div>
    </form>
  </div>
</div>
<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
    <form name="" id="goods_list_form">
      <table class="table">
        <thead>
          <tr>
            <th width="5%">
              <div class="checkbox i-checks">
                <label><input type="checkbox" id="check_all" value=""></label>
              </div>
            </th>
            <th width="5%">商品ID</th>
            <th width="10%">商品图片</th>
            <th width="25%">商品名称</th>
            <th width="10%">品牌</th>
            <th width="10%">货号</th>
            <th width="10%">条形码</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($goods_list as $kk => $vv) { ?>
            <tr>
              <td>
                <div class="checkbox i-checks">
                  <label><input type="checkbox" class="goods_ids" name="goods_id[]" value="<?= $vv['goods_id'] ?>"></label>
                </div>
              </td>

              <td><?= $vv['goods_id'] ?></td>
              <td><img width="50px" src="/<?= $vv['goods_img'] ?>" /></td>
              <td><?= $vv['goods_name'] ?></td>
              <td><?= $vv['brand_name'] ?></td>
              <td><?= $vv['goods_sn'] ?></td>
              <td><?= $vv['isbn'] ?></td>

              <td>
                <a href="javascript:insert_into_order(<?= $vv['goods_id'] ?>)">选定</a>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
  </div>
</div>

<div class="row" style="margin-bottom:5em;">
  <div class="col-sm-6"></div>
  <div class="col-sm-5" align="right">
    <?php
    echo LinkPager::widget([
      'pagination' => $pages,
    ]);
    ?>
  </div>
</div>

<div class="oprate_bar">
  <div class="row">
    <div class="col-sm-10">
      <button type="button" id="insert_goods" class="btn btn-primary btn-sm" disabled="disabled"><i class="fa fa-plus"></i> 选中添加</button>
      <!-- <button type="button" id="insert_all_goods" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> 当前条件全部添加</button> -->
    </div>
  </div>
</div>

<script>
  var order_id = '<?= $order_id ? $order_id : $search_data['order_id'] ?>';

  function insert_into_order(goods_id) {

    $.get('<?= $insert_url ?>', {
      order_id: order_id,
      goods_id: goods_id,
      order_type: <?= $OrderType ?>
    }, function(result) {
      if (result.error == 1) {
        //console.log(result.message);
        layer.msg(result.message);
      } else {

        layer.msg(result.message);
      }
    }, 'json')
  }

  $("#insert_goods").click(function() {
    var goods_id = $("#goods_list_form").serialize();
    goods_id = goods_id + '&order_id=' + order_id + '&order_type=' + <?= $OrderType ?>;
    $.get('<?= $insert_url ?>', goods_id, function(result) {
      if (result.error == 1) {
        var message = result.message;
        var retrun_message = '';
        if (typeof(message) == 'string') {
          retrun_message = message;
        } else {
          retrun_message = '<p>添加成功</p>';
          for (var o in message) {
            retrun_message = retrun_message + message[o] + "<br>";
          }
        }
        layer.alert(retrun_message, {
          skin: 'layui-layer',
          closeBtn: 0
        })
      } else {
        layer.msg(result.message);
      }
    }, 'json')
  });

  $("#insert_all_goods").click(function() {

    $.get('<?= $insert_url ?>', {
      order_id: order_id,
      search_data: goods_ids
    }, function(result) {
      if (result.error == 1) {
        layer.msg(result.message);
      } else {
        layer.msg(result.message);
      }
    }, 'json')
  });


  $("#check_all").on('ifChecked', function(event) {

    $(".goods_ids").iCheck('check');
    set_insert_true();
  });

  $("#check_all").on('ifUnchecked', function(event) {
    $(".goods_ids").iCheck('uncheck');
    set_insert_true();
  });

  $(".goods_ids").on('ifUnchecked', function(event) {
    set_insert_true();
  })

  $(".goods_ids").on('ifChecked', function(event) {
    set_insert_true();
  })


  function set_insert_true() {
    //检查是不是有选中数据
    var goods_ids = $("#goods_list_form").serialize();
    if (goods_ids !== '') {
      //设置插入按钮可点击
      $("#insert_goods").prop('disabled', false);
    } else {
      $("#insert_goods").prop('disabled', true);
    }
  }



  $(document).ready(function() {
    $(".i-checks").iCheck({
      checkboxClass: "icheckbox_square-green",
      radioClass: "iradio_square-green",
    })
  });
</script>