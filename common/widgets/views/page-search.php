<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\common\models\Depart;
use yii\helpers\ArrayHelper;
?>

<?= Html::jsFile('@web/js/bootstrap-datetimepicker.min.js') ?>
<?= Html::jsFile('@web/js/bootstrap-datetimepicker.zh-CN.js') ?>
<?= Html::cssFile('@web/css/bootstrap-datetimepicker.min.css') ?>

<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;position:relative;">
    <form action="<?= $url ?>" method="get" class="form-horizontal">
      <div class="row" style="padding:0 -10px;padding:0 10px;" id="basic_search_confition">
        <?php foreach ($condition as $key => $value) {
        ?>
        <div class="col-sm-3">
          <div class="form-group mgb5">
            <label class="col-sm-3 control-label"><?= $value['label_name']?></label>
            <div class="col-sm-9">
              <?php if($value['type'] == 'text'){
              ?>
              <input type="text" name="<?= $value['label']?>" id="<?= $value['id']?>" class="form-control" value="<?= Yii::$app->request->get($value['label'])?>" placeholder="<?= $value['label_name']?>"/> 
              <?php
              }elseif ($value['type'] == 'select') {
              ?>
              <?= Html::dropDownList($value['label'], Yii::$app->request->get($value['label']), $value['value_arr'],['prompt'=>isset($value['prompt'])?$value['prompt']:'请选择状态','class'=>'form-control']) ?>
              <?php
              }elseif ($value['type'] == 'token_input') {
              ?>
              <input type="text" name="<?= $value['label']?>" id="<?= $value['label']?>" class="form-control" placeholder="<?= $value['label_name']?>"/>
              <script type="text/javascript">
                $(document).ready(function() {
                    <?php
                      if(Yii::$app->request->get($value['label']) >= 1){
                        $query = new \yii\db\Query();
                        $token_input_data = $query->select('id,'.$value['name_name'].' as name')
                                ->from($value['table_name'])
                                ->where(['id'=>Yii::$app->request->get($value['label'])])
                                ->one();
                      }
                    ?>
                    $("#<?= $value['label']?>").tokenInput("<?= $value['token_url']?>",
                      {
                        theme:'facebook', 
                        hintText:'请输入要搜索的关键字',
                        tokenLimit:1,
                        <?php if(Yii::$app->request->get($value['label']) >= 1){  ?>prePopulate:[{id:'<?= $token_input_data['id'] ?>',name:'<?= $token_input_data['name'] ?>'}],<?php }?>
                      }
                    );
                });
              </script>
              <?php
              }elseif($value['type'] == 'html_str'){
              ?>
                <?= $value['value'] ?>
              <?php
              }?>
            </div>
          </div>
        </div>
        <?php
        }?>
        </div>

        <?php
        if ($more_search > 0) {
        ?>
        <div class="row" style="padding:0 -10px;padding:0 10px;
        <?php
          if (Yii::$app->request->get('add_user_id') > 0 || Yii::$app->request->get('add_depart_id') > 0 || Yii::$app->request->get('order_start_time') > 0 || Yii::$app->request->get('order_end_time') > 0 ) {
          }else{
            echo 'display:none;';
          }
        ?>
        " id="advance_search">
          <div class="col-sm-3">
            <div class="form-group mgb5">
              <label class="col-sm-3 control-label">添加用户</label>
              <div class="col-sm-9">
                <input type="text" name="add_user_id" id="add_user_id" class="form-control" value="<?= Yii::$app->request->get('add_user_id')?>" placeholder="添加用户"/> 
              </div>
            </div>
          </div>
          <script type="text/javascript">
                $(document).ready(function() {
                    <?php
                      if(Yii::$app->request->get('add_user_id') >= 1){
                        $query = new \yii\db\Query();
                        $token_input_data = $query->select('id,real_name as name')
                                ->from('admin')
                                ->where(['id'=>Yii::$app->request->get('add_user_id')])
                                ->one();
                      }
                    ?>
                    $("#add_user_id").tokenInput("token-user-search",
                      {
                        theme:'facebook', 
                        hintText:'请输入要搜索的关键字',
                        tokenLimit:1,
                        <?php if(Yii::$app->request->get('add_user_id') >= 1){  ?>prePopulate:[{id:'<?= $token_input_data['id'] ?>',name:'<?= $token_input_data['name'] ?>'}],<?php }?>
                      }
                    );
                });
            </script>
          <div class="col-sm-3">
            <div class="form-group mgb5">
              <label class="col-sm-3 control-label">添加部门</label>
              <div class="col-sm-9">
                <?= Depart::get_depart_select('add_depart_id',Yii::$app->request->get('add_depart_id')) ?>
              </div>
            </div>
          </div>

          <div class="col-sm-3">
            <div class="form-group mgb5">
              <label class="col-sm-3 control-label">开始时间</label>
              <div class="col-sm-9">
                <div class="input-group date advance_form_date" data-date="" data-date-format="yyyy-mm-dd hh:ii:00" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd HH:ii:ss">
                    <input class="form-control" name="order_start_time" size="16" value="<?= Yii::$app->request->get('order_start_time')?>" type="text"  placeholder="开始时间">
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-3">
            <div class="form-group mgb5">
              <label class="col-sm-3 control-label">结束时间</label>
              <div class="col-sm-9">
                <div class="input-group date advance_form_date" data-date="" data-date-format="yyyy-mm-dd hh:ii:00" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd HH:ii:ss">
                    <input class="form-control" name="order_end_time" size="16" value="<?= Yii::$app->request->get('order_end_time')?>" type="text" placeholder="结束时间">
                    <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
            </div>
          </div>
        </div>
         
      
      <script>
        $('.advance_form_date').datetimepicker({
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
      <?php
        }
      ?>
      <div class="row">
        <div class="col-sm-11">
        </div>
        <div class="col-sm-1">
          <button type="submit" class="btn btn-primary btn-sm" /><span class="glyphicon glyphicon-search"></span> 搜索</button>
        </div>
      </div>
    </form>

        <?php
        if ($more_search > 0) {
        ?>
    <div id="show_advance_search" title="显示更多搜索条件">
        <?php
          if (Yii::$app->request->get('add_user_id') > 0 || Yii::$app->request->get('add_depart_id') > 0 || Yii::$app->request->get('order_start_time') > 0 || Yii::$app->request->get('order_end_time') > 0 ) {
            echo ' <i class="fa fa-sort-up"></i>';
          }else{
            echo ' <i class="fa fa-sort-down"></i>';
          }
        ?>
       
    </div>
      <?php
        }
      ?>
  </div>

</div>

<script type="text/javascript">
  $("#show_advance_search").click(
    function () {
      if ($("#advance_search").css('display') == 'block') {
        $("#advance_search").hide();
        $(this).html('<i class="fa fa-sort-down"></i>');
        $(this).attr('title','显示更搜索条件');
      }else{
        $("#advance_search").show();
        $(this).html('<i class="fa fa-sort-up"></i>');
        $(this).attr('title','隐藏更搜索条件'); 
      }

    }
  );
</script>