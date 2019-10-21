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

<form method="post" id="order_form" class="form-horizontal" enctype="multipart/form-data">
  <div class="tabs-container">
    <ul class="nav nav-tabs">
      <li class="active"> <a data-toggle="tab" href="#activity_name">基本信息</a></li>
      <li> <a data-toggle="tab" href="#plat_name">平台网站</a></li>
      <li> <a data-toggle="tab" href="#contract_name">合同信息</a></li>
      <li> <a data-toggle="tab" href="#theme_name">主题活动</a></li>
      <li> <a data-toggle="tab" href="#brand_name">品牌开通</a></li>
      <li> <a data-toggle="tab" href="#period_name">结算信息</a></li>
      <li> <a data-toggle="tab" href="#activity_desc">详情描述</a></li>
    </ul>
    <div class="tab-content">
      <div id="activity_name" class="tab-pane active">
        <div class="panel-body">
          <?= app\common\widgets\Input::widget(['label_name'=>'平台名称','name'=>"Platform[plat_name]",'value'=>$platform->plat_name,'tips'=>'']); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'客户名称','name'=>"Platform[custom_id]",'value'=>$platform->custom_id,'tips'=>'','id'=>'custom',]); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'合作形式','name'=>"Platform[hezuoxingshi]",'value'=>$platform->hezuoxingshi,'tips'=>'','id'=>'',]); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'地址','name'=>"Platform[address]",'value'=>$platform->address,'tips'=>'']); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'备注','name'=>"Platform[remark]",'value'=>$platform->remark,'tips'=>'']); ?>
        </div>
      </div>
      <div id="plat_name" class="tab-pane">
        <div class="panel-body">
          <?= app\common\widgets\Input::widget(['label_name'=>'前端网站','name'=>"Platform[website_front]",'value'=>$platform->website_front,'tips'=>'']); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'客户名称','name'=>"Platform[website_backend]",'value'=>$platform->website_backend,'tips'=>'',]); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'网站后台地址','name'=>"Platform[login_user_name]",'value'=>$platform->login_user_name,'tips'=>'',]); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'登录账号','name'=>"Platform[login_pass]",'value'=>$platform->login_pass,'tips'=>'']); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'品类','name'=>"Platform[web_cate]",'value'=>$platform->web_cate,'tips'=>'']); ?>
        </div>
      </div> 
      <div id="contract_name" class="tab-pane">
        <div class="panel-body">
          <?= app\common\widgets\Input::widget(['label_name'=>'合同开始时间','name'=>"Platform[contract_start_time]",'value'=>$platform->contract_start_time,'tips'=>'']); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'合同结束时间','name'=>"Platform[contract_end_time]",'value'=>$platform->contract_end_time,'tips'=>'',]); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'合同联系人','name'=>"Platform[contract_contact]",'value'=>$platform->contract_contact,'tips'=>'','id'=>'custom',]); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'合同联系人手机','name'=>"Platform[contract_tel]",'value'=>$platform->contract_tel,'tips'=>'']); ?>
        </div>
      </div>
      <div id="theme_name" class="tab-pane">
        <div class="panel-body">
          <?= app\common\widgets\Input::widget(['label_name'=>'主题活动提报','name'=>"Platform[theme_block]",'value'=>$platform->theme_block,'tips'=>'']); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'主题对接人','name'=>"Platform[theme_contact]",'value'=>$platform->theme_contact,'tips'=>'','id'=>'',]); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'主题对接人手机','name'=>"Platform[theme_contact_tel]",'value'=>$platform->theme_contact_tel,'tips'=>'','id'=>'']); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'具体工作内容','name'=>"Platform[theme_content]",'value'=>$platform->theme_content,'tips'=>'']); ?>
        </div>
      </div>
      <div id="brand_name" class="tab-pane">
        <div class="panel-body">
          <?= app\common\widgets\Input::widget(['label_name'=>'品牌开通','name'=>"Platform[brand_open]",'value'=>$platform->brand_open,'tips'=>'']); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'品牌开通对接人','name'=>"Platform[brand_contact]",'value'=>$platform->brand_contact,'tips'=>'','id'=>'']); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'对接人联系方式','name'=>"Platform[brand_contact_tel]",'value'=>$platform->brand_contact_tel,'tips'=>'','id'=>'']); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'备注','name'=>"Platform[brand_remark]",'value'=>$platform->brand_remark,'tips'=>'']); ?>
        </div>
      </div>
      <div id="period_name" class="tab-pane">
        <div class="panel-body">
          <?= app\common\widgets\Input::widget(['label_name'=>'结算周期','name'=>"Platform[period]",'value'=>$platform->period,'tips'=>'']); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'平台佣金','name'=>"Platform[yongjin]",'value'=>$platform->yongjin,'tips'=>'','id'=>'']); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'结算对接人','name'=>"Platform[period_contact]",'value'=>$platform->period_contact,'tips'=>'','id'=>'']); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'联系方式','name'=>"Platform[period_contact_tel]",'value'=>$platform->period_contact_tel,'tips'=>'']); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'结算说明','name'=>"Platform[period_desc]",'value'=>$platform->period_desc,'tips'=>'']); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'工单处理','name'=>"Platform[period_gongdan]",'value'=>$platform->period_gongdan,'tips'=>'']); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'对接人','name'=>"Platform[gongdan_contact]",'value'=>$platform->gongdan_contact,'tips'=>'']); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'联系方式','name'=>"Platform[gongdan_contact_tel]",'value'=>$platform->gongdan_contact_tel,'tips'=>'']); ?>
        </div>
      </div>
      <div id="activity_desc" class="tab-pane">
        <div class="panel-body">
          <Div class="row">
            <div class="col-xs-1">平台信息</div>
            <div class="col-xs-11"> 
              <script type="text/plain" id="editor" name="Platform[plat_info]" style="height:300px;"><?= $platform->plat_info?></script> 
            </div>
          </Div>
          <script type="text/javascript">var editor = UE.getEditor('editor');</script> 
        </div>
      </div>
    </div>
  </div>
</form>

<?= app\common\widgets\Submit::widget(['model'=>$platform,'model_name'=>"platform",'form_name'=>'order_form']); ?>
<script type="text/javascript">
$("#custom").tokenInput("<?= Url::to(['/platform/token-custom-search'])?>",
  {
    theme:'facebook', 
    hintText:'请输入要搜索的关键字',
    tokenLimit:1
    <?php if($platform->custom_id >= 1){ ?>,prePopulate:[{id:'<?= $platform->custom_id?>',name:'<?= $platform->custom_name?>'}],<?php }?>
  }
);
</script>