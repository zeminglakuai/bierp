<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\modules\admin\config\config;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客维商品管理-添加供货商';
$this->params['breadcrumbs'][] = ['label' => '供货商列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = !isset($supplier->id) ? '添加供货商' : '编辑供货商-' . $supplier->supplier_name;
?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>
<?= Html::cssFile('@web/css/plugins/iCheck/custom.css') ?>
<?= Html::jsFile('@web/js/plugins/iCheck/icheck.min.js') ?>
<div class="row">
  <div class="col-sm-12">
    <div class="tabs-container">
      <ul class="nav nav-tabs">
        <li class="<?= !$present_panel ? 'active' : '' ?>"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 基本信息</a>
        </li>
        <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false">三证/经营执照</a></li>
        <li class=""><a data-toggle="tab" href="#tab-5" aria-expanded="false">廉洁协议</a></li>
        <li class=""><a data-toggle="tab" href="#tab-6" aria-expanded="false">合同</a></li>
        <li class=""><a data-toggle="tab" href="#tab-3" aria-expanded="false">财务信息</a></li>
        <?php if (isset($supplier->id)) { ?>
          <li class="<?= $present_panel === "contact" ? 'active' : '' ?>"><a data-toggle="tab" href="#tab-4" aria-expanded="true"> 联系人列表</a></li>
        <?php } ?>
      </ul>
      <form action="<?= Url::to(['/brand/update']) ?>" method="post" class="form-horizontal" id="supplier_form" enctype="multipart/form-data">
        <div class="tab-content">
          <div id="tab-1" class="tab-pane <?= !$present_panel ? 'active' : '' ?>">
            <div class="panel-body">
              <?= app\common\widgets\Input::widget(['label_name' => '供货商名称', 'name' => "Supplier[supplier_name]", 'value' => $supplier->supplier_name, 'tips' => '', 'inneed' => true]); ?>
              <?= app\common\widgets\Input::widget(['label_name' => '业务联系人', 'name' => "Supplier[contact]", 'value' => $supplier->contact, 'tips' => '', 'inneed' => true]); ?>
              <?= app\common\widgets\Input::widget(['label_name' => '业务联系人职位', 'name' => "Supplier[position]", 'value' => $supplier->position, 'tips' => '', 'inneed' => true]); ?>
              <?= app\common\widgets\Input::widget(['label_name' => '业务联系人手机', 'name' => "Supplier[tel]", 'value' => $supplier->tel, 'tips' => '', 'inneed' => true]); ?>
              <?= app\common\widgets\Input::widget(['label_name' => '业务联系人固话', 'name' => "Supplier[guhua]", 'value' => $supplier->guhua, 'tips' => '']); ?>
              <?= app\common\widgets\Input::widget(['label_name' => '业务联系人QQ', 'name' => "Supplier[qq]", 'value' => $supplier->qq, 'tips' => '']); ?>
              <?= app\common\widgets\Input::widget(['label_name' => '采购联系人', 'name' => "Supplier[contact2]", 'value' => $supplier->contact2, 'tips' => '', 'inneed' => true]); ?>
              <?= app\common\widgets\Input::widget(['label_name' => '采购联系人手机', 'name' => "Supplier[tel2]", 'value' => $supplier->tel2, 'tips' => '', 'inneed' => true]); ?>
              <?= app\common\widgets\Input::widget(['label_name' => '采购联系人QQ', 'name' => "Supplier[qq2]", 'value' => $supplier->qq2, 'tips' => '']); ?>
              <?= app\common\widgets\Input::widget(['label_name' => '公司地址', 'name' => "Supplier[address]", 'value' => $supplier->address, 'tips' => '']); ?>
              <?= app\common\widgets\Input::widget(['label_name' => '仓库地址', 'name' => "Supplier[store_address]", 'value' => $supplier->store_address, 'tips' => '']); ?>

              <?= app\common\widgets\Radio::widget(
                [
                  'label_name' => '一件代发',
                  'name' => "Supplier[is_daifa]",
                  'value' => $supplier->is_daifa,
                  'init_value' => [['label_name' => 是, 'value' => '1'], ['label_name' => 否, 'value' => '0']]
                ]
              ); ?>

              <?= app\common\widgets\Input::widget(['label_name' => '备注', 'name' => "Supplier[remark]", 'value' => $supplier->remark, 'tips' => '']); ?>

            </div>
          </div>
          <div id="tab-2" class="tab-pane">
            <div class="panel-body">
              <?= app\common\widgets\FileList::widget(['model' => 'supplier', 'file_list' => $supplier->supplierThreez, 'type' => '3z']); ?>
            </div>
          </div>

          <div id="tab-5" class="tab-pane">
            <div class="panel-body">
              <?= app\common\widgets\FileList::widget(['model' => 'supplier', 'file_list' => $supplier->supplierProtocol, 'type' => 'protocol']); ?>
            </div>
          </div>

          <div id="tab-6" class="tab-pane">
            <div class="panel-body">
              <?= app\common\widgets\FileList::widget(['model' => 'supplier', 'file_list' => $supplier->supplierContract, 'type' => 'contract']); ?>
            </div>
          </div>

          <div id="tab-3" class="tab-pane">
            <div class="panel-body">
              <?= app\common\widgets\Input::widget(['label_name' => '开户名称', 'name' => "Supplier[bank_name]", 'value' => $supplier->bank_name, 'tips' => '', 'inneed' => true]); ?>
              <?= app\common\widgets\Input::widget(['label_name' => '开户行', 'name' => "Supplier[bank_open]", 'value' => $supplier->bank_open, 'tips' => '', 'inneed' => true]); ?>
              <?= app\common\widgets\Input::widget(['label_name' => '银行账号', 'name' => "Supplier[bank_code]", 'value' => $supplier->bank_code, 'tips' => '', 'inneed' => true]); ?>
              <?= app\common\widgets\Input::widget(['label_name' => '收款人', 'name' => "Supplier[bank_payee]", 'value' => $supplier->bank_payee, 'tips' => '', 'inneed' => true]); ?>
              <?= app\common\widgets\Input::widget(['label_name' => '税号', 'name' => "Supplier[tax_code]", 'value' => $supplier->tax_code, 'tips' => '']); ?>
              <?= app\common\widgets\Input::widget(['label_name' => '抬头', 'name' => "Supplier[title]", 'value' => $supplier->title, 'tips' => '']); ?>
              <?= app\common\widgets\Input::widget(['label_name' => '账期', 'name' => "Supplier[account_period]", 'value' => $supplier->account_period, 'tips' => '']); ?>
              <?= app\common\widgets\Input::widget(['label_name' => '其他方式', 'name' => "Supplier[alipay]", 'value' => $supplier->alipay, 'tips' => '']); ?>
            </div>
          </div>

          <?php if (isset($supplier->id)) { ?>
            <div id="tab-4" class="tab-pane <?= $present_panel === "contact" ? 'active' : '' ?>">
              <div class="panel-body">
                <!--显示用户列表-->
                <?= app\common\widgets\ContactList::widget(['contact_list' => $supplier->contactList, 'main_body' => $supplier]); ?>
                <div class="row" style="margin-top:15px;">
                  <div class="col-sm-10"></div>
                  <div class="col-sm-2"><a class="btn btn-primary" id="create_contact">+添加联系人</a></div>
                </div>
              </div>
            </div>

          <?php } ?>

        </div>
      </form>
    </div>
  </div>
</div>
<?= app\common\widgets\Submit::widget(['model' => $supplier, 'model_name' => "supplier", 'form_name' => 'supplier_form', 'if_has_parent' => false]); ?>

<?php if (isset($supplier->id)) { ?>
  <?= app\common\widgets\OperateBar::widget([
    'admit' => ['label_name' => '审核', 'id' => 'admit', 'type' => 'js', 'icon' => 'eye', 'url' => Url::to(["supplier/admit", "id" => $supplier->id])],
    'refresh' => ['label_name' => '刷新', 'type' => 'js', 'id' => 'add_custom', 'icon' => 'plus'],
    'backup' => ['label_name' => '返回', 'type' => 'link', 'url' => '/supplier'],
  ])
  ?>
<?php } ?>

<script>
  $("#create_consignee").click(function() {
    //页面层
    layer.open({
      type: 2,
      title: '添加收货地址',
      area: ['90%', '80%'], //宽高
      maxmin: true,
      content: '<?= Url::to(["/supplier/create-consignee", "id" => $supplier->id]) ?>'
    });
  });

  $(".edit_consignee").click(function() {
    //页面层
    var data_id = $(this).attr('data-id');
    var id = $(this).attr('origin-id');
    var edit_url = create_url('<?= "/supplier/edit-consignee" ?>');
    layer.open({
      type: 2,
      title: '编辑收货地址',
      area: ['90%', '80%'], //宽高
      maxmin: true,
      content: edit_url + 'consignee_id=' + data_id + '&id=' + id
    });
  });

  $(".delete_consignee").click(function() {
    var data_id = $(this).attr('data-id');
    var id = $(this).attr('origin-id');
    if (confirm("确认删除？")) {
      $.get('/supplier/delete-consignee', {
        id: id,
        consignee_id: data_id
      }, function(result) {
        if (result.error == 1) {
          window.location.reload();
        } else {
          layer.msg(result.message);
        };
      }, 'json');
    };
  });


  $("#create_contact").click(function() {
    //页面层
    layer.open({
      type: 2,
      title: '添加联系人',
      area: ['90%', '80%'], //宽高
      maxmin: true,
      content: '<?= Url::to(["/supplier/create-contact", "id" => $supplier->id]) ?>'
    });
  });

  $(".edit_contact").click(function() {
    //页面层
    var data_id = $(this).attr('data-id');
    var id = $(this).attr('origin-id');
    var edit_url = create_url('<?= "/supplier/edit-contact" ?>');
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
      $.get('/supplier/delete-contact', {
        id: id,
        contact_id: data_id
      }, function(result) {
        if (result.error == 1) {
          window.location.reload();
        } else {
          layer.msg(result.message);
        };
      }, 'json');
    };
  });



  $(document).ready(function() {
    $(".i-checks").iCheck({
      checkboxClass: "icheckbox_square-green",
      radioClass: "iradio_square-green",
    })
  });
</script>