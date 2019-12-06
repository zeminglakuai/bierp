<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use app\common\config\sys_config;
?>


<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>
<?= Html::jsFile('@web/js/bootstrap-datetimepicker.min.js') ?>
<?= Html::jsFile('@web/js/bootstrap-datetimepicker.zh-CN.js') ?>
<?= Html::cssFile('@web/css/bootstrap-datetimepicker.min.css') ?>


<div class="tabs-container">
  <form id="data_form" method="get" class="form-horizontal">
  <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 基本信息</a></li>
      <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false">附件</a></li>
  </ul>
  <div class="tab-content">
      <div id="tab-1" class="tab-pane active">
          <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-2 control-label">供货商：</label>
                <div class="col-sm-9">
                      <input type="text" name="GoodsAuth[supplier_id]" id="supplier" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">有效期：</label>
                <div class="col-sm-9">
                  <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd hh:ii:00" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd HH:ii:ss">
                    <input class="form-control" name="GoodsAuth[expire_time]" value="<?= $goods_auth->expire_time?>" size="16" type="text"  readonly="" placeholder="有效期">
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
            </div>
        </div>
      </div>
      <div id="tab-2" class="tab-pane">
          <div class="panel-body">
            <div class="relate_file_list" id="relate_file_list">
              <?php if(isset($goods_auth->authFile)){
                foreach ($goods_auth->datumFile as $key => $value) {
                ?>
                  <div class="relate_file_item" id="custom_file_<?= $value->id ?>">
                    <div class="relate_file_div" id="">
                      <div class="relate_file_operate_bg">
                      </div>
                      <div class="relate_file_operate">
                        <i class="fa fa-trash delete_file"  data-id="<?= $value->id ?>"></i>
                      </div>
                      <a href="/<?= $value->file?>" target="_blank"><img src="<?= Yii::getAlias('@web/').'img/file_icon/file.jpg'?>"></a>
                    </div>
                    <div class="upload_file_desc">
                      <input type="text" name="file_desc[]" class="form-control" placeholder="文件描述" value="<?= $value->desc?>">
                    </div>
                  </div>
                <?php
                }
              }?>
            </div>
            <div class="fl" style="width:20%;">
              <a href="javascript:void();" class="btn btn-danger" id="upload_new_file">点击添加文件</a>
            </div>
            <div class="cl"></div>
          </div>
      </div>
  </div>
  </form>
  </div>
</div>
<?= app\common\widgets\Submit::widget(['model'=>$goods_auth,'model_name'=>"goods-auth",'form_name'=>'data_form']); ?>

<script type="text/javascript">
$("#supplier").tokenInput("<?= Url::to(['/goods-auth/search-supplier'])?>",
  {
    theme:'facebook', 
    hintText:'请输入要搜索的关键字',
    tokenLimit:1
  }
);
  
</script>
<script>
$('.form_date').datetimepicker({
    language:  'zh-CN',
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    minView: 0,
    forceParse: 0
  });
</script>
 <?= Html::jsFile('@web/js/upload_file.js') ?>