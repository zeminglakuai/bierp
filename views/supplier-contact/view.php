<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\common\config\lang_config;
use app\common\config\lang_value_config;


?> 
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>

<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<div class="row">
  <div class="col-sm-12">
    <div class="tabs-container">
      <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 基本信息</a>
        </li>
        <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false">拓展信息</a>
        </li>
      </ul>
      <form method="post" class="form-horizontal" id="data_form" enctype="multipart/form-data">
      <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
              <?= app\common\widgets\Input::widget(['label_name'=>'用户名称','name'=>"Admin[admin_name]",'value'=>$supplier_contact->admin_name,'tips'=>'']); ?>
              <?= app\common\widgets\Input::widget(['label_name'=>'真实姓名','name'=>"Admin[real_name]",'value'=>$supplier_contact->real_name,'tips'=>'']); ?>
              <?= app\common\widgets\Input::widget(['label_name'=>'手机号','name'=>"Admin[tel]",'value'=>$supplier_contact->tel,'tips'=>'']); ?>

              <div class="form-group">
                  <label class="col-sm-2 control-label">供货商：</label>
                  <div class="col-sm-9">
                        <input type="text" id="supplier" name="Admin[supplier_id]" class="form-control">
                  </div>
              </div>
              <?= app\common\widgets\Input::widget(['label_name'=>'备注','name'=>"Admin[desc]",'value'=>$supplier_contact->desc,'tips'=>'']); ?>
            </div>
        </div>
        <div id="tab-2" class="tab-pane">
            <div class="panel-body">
              <?= app\common\widgets\ExtendInfo::widget(['extend_info'=>$supplier_contact->extendInfo]); ?>
            </div>
        </div>
      </div>
    </form>
    </div>
  </div>
</div>

<?= app\common\widgets\Submit::widget(['model'=>$supplier_contact,'model_name'=>"supplier-contact",'form_name'=>'data_form']); ?>
<script type="text/javascript">
  $(document).ready(function() {
      $("#supplier").tokenInput("<?= Url::to(['supplier-contact/search-supplier'])?>",
        {
          theme:'facebook', 
          hintText:'请输入要搜索的关键字',
          tokenLimit:1,
          <?php if($supplier_contact->supplier_id >= 1){  ?>prePopulate:[{id:'<?= $supplier_contact->supplier_id?>',name:'<?= $supplier_contact->supplier_name?>'}],<?php }?>
        }
      );
  });
</script>