<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\config\sys_config;
use yii\widgets\ActiveForm;
use app\common\models\DictionaryValue;
use app\common\models\UploadForm;

$this->params['breadcrumbs'][] = ['label'=>'客户列表','url'=>['index']];
$this->params['breadcrumbs'][] = $custom->custom_name;

?>

<?= Html::jsFile('@web/js/bootstrap.min.js') ?>

<div class="tabs-container">
  <form id="custom_form" method="post" class="form-horizontal" enctype="multipart/form-data">
  <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 基本信息</a></li>
      <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="true"> 相关图片</a></li>
      <?php if(isset($custom->id) && Yii::$app->session['manage_user']['id'] == $custom->add_user_id){ ?>
      <li class=""><a data-toggle="tab" href="#tab-3" aria-expanded="true"> 联系人列表</a></li>
      <?php } ?>
      <li class=""><a data-toggle="tab" href="#tab-4" aria-expanded="true">财务信息</a></li>
      <?php if(isset($custom->id)  && Yii::$app->session['manage_user']['id'] == $custom->add_user_id){ ?>
      <li class=""><a data-toggle="tab" href="#tab-5" aria-expanded="true">收货地址</a></li>
      <?php } ?>
  </ul>
  <div class="tab-content">
      <div id="tab-1" class="tab-pane active">
          <div class="panel-body">
            <?= app\common\widgets\Input::widget(['label_name'=>'公司名称','name'=>"Custom[custom_name]",'value'=>$custom->custom_name,'tips'=>'']); ?>
            <?= app\common\widgets\Select::widget(['label_name'=>'客户属性','name'=>"Custom[custom_prop]",'value'=>$custom->custom_prop,'tips'=>'','init_value'=>ArrayHelper::map(DictionaryValue::getValueList(8),'dictionary_value','dictionary_value'),]); ?>
            <?php if(Yii::$app->session['manage_user']['id'] == $custom->add_user_id || !$custom){
            ?>
              <?= app\common\widgets\Input::widget(['label_name'=>'联系人','name'=>"Custom[contact]",'value'=>$custom->contact,'tips'=>'']); ?>
              <?= app\common\widgets\Input::widget(['label_name'=>'所属部门','name'=>"Custom[custom_depart]",'value'=>$custom->custom_depart,'tips'=>'']); ?>
              <?= app\common\widgets\Input::widget(['label_name'=>'职位','name'=>"Custom[position]",'value'=>$custom->position,'tips'=>'']); ?>
              <?= app\common\widgets\Input::widget(['label_name'=>'手机','name'=>"Custom[tel]",'value'=>$custom->tel,'tips'=>'']); ?>
              <?= app\common\widgets\Input::widget(['label_name'=>'QQ','name'=>"Custom[qq]",'value'=>$custom->qq,'tips'=>'']); ?>
              <?= app\common\widgets\Input::widget(['label_name'=>'E-mail','name'=>"Custom[email]",'value'=>$custom->email,'tips'=>'']); ?>
            <?php
            }?>
            <?= app\common\widgets\Input::widget(['label_name'=>'地址','name'=>"Custom[address]",'value'=>$custom->address,'tips'=>'']); ?>
            <?= app\common\widgets\Input::widget(['label_name'=>'区域一','name'=>"Custom[area_1]",'value'=>$custom->area_1,'tips'=>'']); ?>
            <?= app\common\widgets\Input::widget(['label_name'=>'区域一','name'=>"Custom[area_2]",'value'=>$custom->area_2,'tips'=>'']); ?>
            <?= app\common\widgets\Input::widget(['label_name'=>'分公司','name'=>"Custom[sub_compny]",'value'=>$custom->sub_compny,'tips'=>'']); ?>          
            <?= app\common\widgets\Input::widget(['label_name'=>'备注','name'=>"Custom[remark]",'value'=>$custom->remark,'tips'=>'']); ?>
            <input type="hidden" name="id" value="<?= $custom->id ?>" />
          </div>
      </div>
      <div id="tab-2" class="tab-pane">
          <div class="panel-body">
            <?= app\common\widgets\FileList::widget(['model'=>'custom','file_list'=>$custom->customFile]); ?>
          </div>
      </div>
      <?php if(isset($custom->id)){ ?>
      <div id="tab-3" class="tab-pane">
          <div class="panel-body">
            <!--显示用户列表-->
              <?= app\common\widgets\ContactList::widget(['contact_list'=>$custom->contactList,'main_body'=>$custom,'extend_type'=>'11']); ?>
              <div class="row" style="margin-top:15px;">
                <div class="col-sm-10"></div>
                <div class="col-sm-2"><a class="btn btn-primary" id="create_contact">+添加联系人</a></div>
              </div>
          </div>
      </div>
      <?php } ?>
      <div id="tab-4" class="tab-pane">
          <div class="panel-body">
            <?= app\common\widgets\Input::widget(['label_name'=>'开户名称','name'=>"Custom[bank_name]",'value'=>$custom->bank_name,'tips'=>'']); ?>
            <?= app\common\widgets\Input::widget(['label_name'=>'开户行','name'=>"Custom[open_bank]",'value'=>$custom->open_bank,'tips'=>'']); ?>
            <?= app\common\widgets\Input::widget(['label_name'=>'银行账号','name'=>"Custom[bank_code]",'value'=>$custom->bank_code,'tips'=>'']); ?>
            <?= app\common\widgets\Input::widget(['label_name'=>'税号','name'=>"Custom[tax_code]",'value'=>$custom->tax_code,'tips'=>'']); ?>
            <?= app\common\widgets\Input::widget(['label_name'=>'其他支付方式','name'=>"Custom[alipay]",'value'=>$custom->alipay,'tips'=>'']); ?>
          </div>
      </div>
      <?php if(isset($custom->id)){ ?>
      <div id="tab-5" class="tab-pane">
          <div class="panel-body">
                <table class="table">
                <thead>
                  <tr>
                    <th>姓名</th>
                    <th>手机号</th>
                    <th>地址</th>
                    <th>备注</th>
                    <th>操作</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($custom->consignee as $key => $value) {
                  ?>
                  <tr>
                    <td><?= $value->consignee?></td>
                    <td><?= $value->tel?></td>
                    <td><?= $value->address?></td>
                    <td><?= $value->remark?></td> 
                    <td>
                      <A  class="btn btn-xs btn-primary edit_consignee" href="javascript:void();" origin-id="<?= $supplier->id?>" data-id="<?= $value->id?>"><span class="glyphicon glyphicon-edit"></span> 编辑</A>
                      <A  class="btn btn-xs btn-danger delete_consignee" href="javascript:void();" origin-id="<?= $supplier->id?>" data-id="<?= $value->id?>"><span class="glyphicon glyphicon-trash"></span> 删除</A>
                    </td>
                  </tr>
                  <?php
                  }?>
                </tbody>
                </table>
              <div class="row" style="margin-top:15px;">
                <div class="col-sm-10"></div>
                <div class="col-sm-2"><a class="btn btn-primary" id="create_consignee">+添加收货地址</a></div>
              </div>
          </div>
      </div>
      <?php } ?>
  </div>
  </form>
</div>

<?= app\common\widgets\Submit::widget(['model'=>$custom,'model_name'=>"custom",'form_name'=>'custom_form','if_has_parent'=>false]); ?>

<?= app\common\widgets\OperateBar::widget([
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            'backup'=>['label_name'=>'返回列表','type'=>'js','url'=>'/custom'],
                                            ])
?>
<script>

  $("#create_consignee").click(function(){
    //页面层
    layer.open({
      type: 2,
      title:'添加收货地址',
      area: ['90%', '80%'], //宽高
      maxmin: true,
      content: '<?= Url::to(["/custom/create-consignee","id"=>$custom->id])?>'
    });
  });

  $(".edit_consignee").click(function(){
    //页面层
    var data_id = $(this).attr('data-id');
    var id = $(this).attr('origin-id');
    var edit_url = create_url('<?= "/custom/edit-consignee" ?>');
    layer.open({
      type: 2,
      title:'编辑收货地址',
      area: ['90%', '80%'], //宽高
      maxmin: true,
      content: edit_url+'consignee_id='+data_id+'&id='+id
    });
  });

  $(".delete_consignee").click(function(){
    var data_id = $(this).attr('data-id');
    var id = $(this).attr('origin-id');
    if (confirm("确认删除？")) {
      $.get('/custom/delete-consignee',{id:id,consignee_id:data_id},function(result){
        if (result.error == 1) {
          window.location.reload();
        }else{
          layer.msg(result.message);
        };
      },'json');
    };
  });


  $("#create_contact").click(function(){
    //页面层
    layer.open({
      type: 2,
      title:'添加联系人',
      area: ['90%', '80%'], //宽高
      maxmin: true,
      content: '<?= Url::to(["/custom/create-contact","id"=>$custom->id])?>'
    });
  });

  $(".edit_contact").click(function(){
    //页面层
    var data_id = $(this).attr('data-id');
    var id = $(this).attr('origin-id');
    var edit_url = create_url('<?= "/custom/edit-contact" ?>');
    layer.open({
      type: 2,
      title:'编辑联系人',
      area: ['90%', '80%'], //宽高
      maxmin: true,
      content: edit_url+'contact_id='+data_id+'&id='+id
    });
  });

  $(".delete_contact").click(function(){
    var data_id = $(this).attr('data-id');
    var id = $(this).attr('origin-id');
    if (confirm("确认删除？")) {
      $.get('/custom/delete-contact',{id:id,contact_id:data_id},function(result){
        if (result.error == 1) {
          window.location.reload();
        }else{
          layer.msg(result.message);
        };
      },'json');
    };
  });


</script>
<?= Html::jsFile('@web/js/preview.js') ?>