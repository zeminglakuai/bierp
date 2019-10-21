<?php

use yii\helpers\Html;
use yii\helpers\Url;
 
use yii\helpers\ArrayHelper;
 
use app\common\config\lang_config;
use app\common\config\lang_value_config;

?>
<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
    <div class="row">
      <div class="col-sm-9">

          <?php 
 
          foreach ($label_arr as $key => $value) {
          ?>
            
              <?php if (strpos($key, '&') > 0) {
                $key_arr = explode('&', $key);
                $key_name = $key_arr[1];
              ?>
              <div class="col-sm-<?= isset($value['col'])?$value['col']:3?> m-b-1">
                <?= isset($model_label[$key])?$model_label[$key]:lang_config::${$key_name} ?>
                :
                <span >
                  <?php
                    if(isset($value['link'])){
                  ?>
                    <a href="javascript:void();" id="<?= $value["id"]?>" data-id="<?= isset($model->$key_arr[0]->id)?$model->$key_arr[0]->id:'' ?>">
                  <?php
                    }
                  ?>
                <?= $model->$key_arr[0]->$key_arr[1];?>
                  <?php
                    if(isset($value['link'])){
                  ?>
                    </a>
                  <?php
                    }
                  ?>
                </span>
              </div>
              <?php
              }elseif($key == 'remark'){
              ?>
                <div class="col-sm-12 m-b-1">
                  <?= isset(lang_config::${$key})?lang_config::${$key}:$model_label[$key] ?>:<?= $model->$key;?>
                </div>

              <?php
              }elseif(strpos($key, '_time') > 0){
              ?>
                  <?= isset(lang_config::${$key})?lang_config::${$key}:$model_label[$key] ?>:<?= date('Y-m-d H:i:s',$model->$key);?>
              <?php
              }elseif(strpos($key, '_status') > 0){
              ?>
                <div class="col-sm-3 m-b-1">
                  <?= isset(lang_config::${$key})?lang_config::${$key}:$model_label[$key] ?>:
                  <span >
                  <?php
                    if(isset($value['link'])){
                  ?>
                    <a href="javascript:void();" id="<?= $value["id"]?>">
                  <?php
                    }
                  ?>
                  <?= lang_value_config::${$key}[$model->$key];?>
                  <?php
                    if(isset($value['link'])){
                  ?>
                    </a>
                  <?php
                    }
                  ?>
                  </span>
                </div>
              <?php
              }else{
              ?>
              <div class="col-sm-<?= isset($value['col'])?$value['col']:3?> m-b-1">

                <?= isset(lang_config::${$key})?lang_config::${$key}:$model_label[$key] ?>:
                <?php
                    if(isset($value['link'])){
                  ?>
                    <a href="javascript:void();" id="<?= $value["id"]?>" data-id="<?= isset($model->$value["id"])?$model->$value["id"]:'' ?>">
                  <?php
                    }
                  ?>
                <?= $model->$key;?>
                  <?php
                    if(isset($value['link'])){
                  ?>
                    </a>
                  <?php
                    }
                  ?>
              </div>
              <?php
              }?>

 
              <?php
              if(isset($value['link'])){
              ?>
                <!--如果存在链接 就设置链接-->
                <script>
                    $('#<?= $value["id"]?>').click(function(){
                      var data_id = $(this).attr('data-id');
                      var update_url = create_url('<?= $value["url"]?>');
                      //页面层
                      layer.open({
                        type: 2,
                        title:'<?= isset(lang_config::${$key})?lang_config::${$key}:$model_label[$key] ?>',
                        skin: 'layui-layer-rim', //加上边框
                        area: ['90%', '80%'], //宽高
                        content: data_id > 0?update_url+'id='+data_id:update_url
                      });
                    });
                </script>

              <?php
              }
              ?>
            
          <?php
          }?>

        <div class="row">
          <div class="col-sm-11">

          </div>
          <div class="col-sm-1">

            <button class="btn btn-primary btn-sm" id="order_edit" >编辑</button>
          </div>
        </div>
      </div>
      <div class="col-sm-3 text-right">
          <?php if(isset($model->order_sn)){?><p><span class="padding-lr-5">单号:<b class="blue"><?= $model->order_sn?></b></span></p><?php }?>
          <p><span class="padding-lr-5">状态:<?= $model->status_name?></span></p>
          <p><span class="padding-lr-5">创建人:<?= $model->add_user_name ?>/<?= $model->depart_name?></span><span class="padding-lr-5">日期:<?= date('Y-m-d H:i:s',$model->add_time)?></span></p>
          <?php
            foreach ($approval_log as $key => $value) {
          ?>
          <p><span class="padding-lr-5"><?= $value->status_name?>:<?= $value->admit_user_name?>/<?= $value->depart_name?></span><span class="padding-lr-5">日期:<?= date('Y-m-d H:i:s',$value->admit_time)?></span></p>
          <?php
            }
          ?>
      </div>
    </div>


  </div>
</div>

<script type="text/javascript">
  $('#order_edit').click(function(){
    var update_url = create_url('<?= $update_url?>');
    //页面层
    layer.open({
      type: 2,
      title:'编辑单据',
      skin: 'layui-layer-rim', //加上边框
      area: ['80%', '80%'], //宽高
      content: update_url
    });
  });

</script>