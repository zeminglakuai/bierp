<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\modules\admin\config\config;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '线上销售单';
$this->params['breadcrumbs'][] = '线上销售单';
?>

<div class="page_header">
  <div class="fl" >
    <div class="page_title"> 线上销售单列表 </div>
  </div>
  <div class="fr" > </div>
  <div class="cl" ></div>
</div>
<div class="row" style="margin:1em 0;">
  <div class="col-xs-1" align="right">
    <div class="form-group">
      <label class="control-label col-xs-12 col-sm-12">下单时间</label>
    </div>
  </div>
  <div class="col-xs-3" >
    <div class="form-group">
      <label class="col-xs-2 col-sm-2 control-label no-padding-right align-right" >开始</label>
      <div class="col-xs-5 col-sm-4" >
        <div class="input-group input-group-sm">
          <input type="text" id="datepicker" class="form-control hasDatepicker">
          <span class="input-group-addon"> <i class="ace-icon fa fa-calendar"></i> </span> </div>
      </div>
      <label class="col-xs-2 col-sm-2 control-label no-padding-right align-right" >结束</label>
      <div class="col-xs-4" >
        <div class="input-group input-group-sm">
          <input type="text" id="datepicker" class="form-control hasDatepicker">
          <span class="input-group-addon"> <i class="ace-icon fa fa-calendar"></i> </span> </div>
      </div>
    </div>
  </div>
  <div class="col-xs-1" align="right"><div class="form-group"><label class="control-label col-xs-12 col-sm-12">经手人</label></div></div>
  <div class="col-xs-2" >
    <input type="text" class="form-control">
  </div>
  
  
  
   <div class="col-xs-11 align-right" >
     <div class="btn btn-xs btn-light"><i class="glyphicon glyphicon-search"></i> 查询</div>
  </div> 
  
</div>
<div class="" >
  <table id="simple-table" class="table table-striped table-bordered table-hover" style="min-width:1024px;">
    <thead>
      <tr>
        <th class="center" width="3%"> <label class="pos-rel">
            <input type="checkbox" class="ace">
            <span class="lbl"></span> </label>
        </th>
        <th width="10%" class="sorting_desc center" tabindex="0" aria-controls="dynamic-table" rowspan="1" colspan="1" aria-label="Domain: activate to sort column ascending" aria-sort="descending">单号</th>
        <th class="center" width="7%">订单状态</th>
        <th class="center" width="12%">下单时间</th>
        <th class="center" width="7%">总计金额</th>
        <th class="center" width="7%">优惠金额</th>
        <th class="center" width="12%">付款时间 </th>
        <th class="center" width="12%">复核时间</th>
        <th class="center" width="8%">订单类型</th>
        <th class="center" width="8%">支付方式</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
      <tr role="row" class="odd">
        <td class="center"><label class="pos-rel">
            <input type="checkbox" class="ace">
            <span class="lbl"></span> </label></td>
        <td class="sorting_1"><a href="/admin/on-line-order/view?id=1">2012458745174</a></td>
        <td>未复核</td>
        <td>2016-12-12 12:20</td>
        <td>265.00</td>
        <td>20.00</td>
        <td>2016-12-12 12:20</td>
        <td>2016-12-12 12:20</td>
        <td>积分兑换</td>
        <td>易票通</td>
        <td><a href="/admin/on-line-order/view?id=1">
          <div class="btn btn-minier btn-yellow"><i class="glyphicon glyphicon-eye-open"></i> 查看</div>
          </a>
          <div class="btn btn-minier btn-yellow order-delete"><i class="glyphicon glyphicon-trash"></i> 删除</div></td>
      </tr>
      <tr role="row" class="odd">
        <td class="center"><label class="pos-rel">
            <input type="checkbox" class="ace">
            <span class="lbl"></span> </label></td>
        <td class="sorting_1"><a href="/admin/on-line-order/view?id=1">2012458745174</a></td>
        <td>未复核</td>
        <td>2016-12-12 12:20</td>
        <td>265.00</td>
        <td>20.00</td>
        <td>2016-12-12 12:20</td>
        <td>2016-12-12 12:20</td>
        <td>积分兑换</td>
        <td>易票通</td>
        <td><a href="/admin/on-line-order/view?id=1">
          <div class="btn btn-minier btn-yellow"><i class="glyphicon glyphicon-eye-open"></i> 查看</div>
          </a>
          <div class="btn btn-minier btn-yellow order-delete"><i class="glyphicon glyphicon-trash"></i> 删除</div></td>
      </tr>
      <tr role="row" class="odd">
        <td class="center"><label class="pos-rel">
            <input type="checkbox" class="ace">
            <span class="lbl"></span> </label></td>
        <td class="sorting_1"><a href="/admin/on-line-order/view?id=1">2012458745174</a></td>
        <td>未复核</td>
        <td>2016-12-12 12:20</td>
        <td>265.00</td>
        <td>20.00</td>
        <td>2016-12-12 12:20</td>
        <td>2016-12-12 12:20</td>
        <td>积分兑换</td>
        <td>易票通</td>
        <td><a href="/admin/on-line-order/view?id=1">
          <div class="btn btn-minier btn-yellow"><i class="glyphicon glyphicon-eye-open"></i> 查看</div>
          </a>
          <div class="btn btn-minier btn-yellow order-delete"><i class="glyphicon glyphicon-trash"></i> 删除</div></td>
      </tr>
      <tr role="row" class="odd">
        <td class="center"><label class="pos-rel">
            <input type="checkbox" class="ace">
            <span class="lbl"></span> </label></td>
        <td class="sorting_1"><a href="/admin/on-line-order/view?id=1">2012458745174</a></td>
        <td>未复核</td>
        <td>2016-12-12 12:20</td>
        <td>265.00</td>
        <td>20.00</td>
        <td>2016-12-12 12:20</td>
        <td>2016-12-12 12:20</td>
        <td>积分兑换</td>
        <td>易票通</td>
        <td><a href="/admin/on-line-order/view?id=1">
          <div class="btn btn-minier btn-yellow"><i class="glyphicon glyphicon-eye-open"></i> 查看</div>
          </a>
          <div class="btn btn-minier btn-yellow order-delete"><i class="glyphicon glyphicon-trash"></i> 删除</div></td>
      </tr>
      <tr role="row" class="odd">
        <td class="center"><label class="pos-rel">
            <input type="checkbox" class="ace">
            <span class="lbl"></span> </label></td>
        <td class="sorting_1"><a href="/admin/on-line-order/view?id=1">2012458745174</a></td>
        <td>未复核</td>
        <td>2016-12-12 12:20</td>
        <td>265.00</td>
        <td>20.00</td>
        <td>2016-12-12 12:20</td>
        <td>2016-12-12 12:20</td>
        <td>积分兑换</td>
        <td>易票通</td>
        <td><a href="/admin/on-line-order/view?id=1">
          <div class="btn btn-minier btn-yellow"><i class="glyphicon glyphicon-eye-open"></i> 查看</div>
          </a>
          <div class="btn btn-minier btn-yellow order-delete"><i class="glyphicon glyphicon-trash"></i> 删除</div></td>
      </tr>
      <tr role="row" class="odd">
        <td class="center"><label class="pos-rel">
            <input type="checkbox" class="ace">
            <span class="lbl"></span> </label></td>
        <td class="sorting_1"><a href="/admin/on-line-order/view?id=1">2012458745174</a></td>
        <td>未复核</td>
        <td>2016-12-12 12:20</td>
        <td>265.00</td>
        <td>20.00</td>
        <td>2016-12-12 12:20</td>
        <td>2016-12-12 12:20</td>
        <td>积分兑换</td>
        <td>易票通</td>
        <td><a href="/admin/on-line-order/view?id=1">
          <div class="btn btn-minier btn-yellow"><i class="glyphicon glyphicon-eye-open"></i> 查看</div>
          </a>
          <div class="btn btn-minier btn-yellow order-delete"><i class="glyphicon glyphicon-trash"></i> 删除</div></td>
      </tr>
    </tbody>
  </table>
</div>
<div class="oparet_bar">
    <div class="col-xs-5" >
      <div class="btn-group dropup">
        <button data-toggle="dropdown" class="btn btn-xs btn-yellow dropdown-toggle " aria-expanded="false"> <i class="glyphicon glyphicon-print"></i> 打印 <span class="ace-icon fa fa-caret-down icon-on-right"></span> </button>
        <ul class="dropdown-menu ">
          <li> <a href="#">打印单据1</a> </li>
          <li> <a href="#">打印单据2</a> </li>
        </ul>
      </div>
      <div class="btn btn-xs btn-yellow"><i class="glyphicon glyphicon-export"></i> 导出</div>
    </div>
    <div class="col-xs-5 align-right">
      <div class="btn btn-xs btn-yellow"><i class="glyphicon glyphicon-import"></i> 导入</div>
      <div class="btn btn-xs btn-yellow"><i class="glyphicon glyphicon-floppy-save"></i> 保存</div>
      <div class="btn btn-xs btn-yellow"><i class="glyphicon glyphicon-ok-circle"></i> 审核</div>
      <div class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-backward"></i> 红冲</div>
    </div>
</div>
<script>
jQuery(function($) {
	$(".order-delete").on(ace.click_event, function() {
		bootbox.confirm("Are you sure?", function(result) {
			if(result) {
				 alert('ddddddddddddddd');
			}
		});
	});
	/**
	$("#bootbox-confirm").on(ace.click_event, function() {
		bootbox.confirm({
			message: "Are you sure?",
			buttons: {
			  confirm: {
				 label: "OK",
				 className: "btn-primary btn-sm",
			  },
			  cancel: {
				 label: "Cancel",
				 className: "btn-sm",
			  }
			},
			callback: function(result) {
				if(result) alert(1)
			}
		  }
		);
	});
**/
});
</script>