<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客维商品管理-供货商商品报备';

?>
<?= Html::cssFile('@web/css/bootstrap.min.css') ?>
<?= Html::cssFile('@web/css/font-awesome.min.css') ?>
<?= Html::cssFile('@web/css/animate.min.css') ?>
<?= Html::cssFile('@web/css/style.min.css') ?>

<?php
  if($error_msg <> ''){
?>
 
<div style="width:500px;margin:5em auto;">
  <div class="ibox animated fadeInDown">
    <div class="ibox-title">错误信息</div>
    <div class="ibox-content">
       <?= $error_msg?>
    </div>
  </div>
</div>

<?php
  }elseif($edit_able){
?>

<div style="width:80%;min-width:1000px;margin:1em auto;">
  <div class="ibox animated fadeInDown">
    <div class="ibox-title">商品报备询价单
        <div class="ibox-tools">
          <a href="<?= Url::to(["/supplier-price",'export'=>'qeqwe','id'=>$id])?>" id="print_btn"><i class="fa fa-print"></i> 导出</a>
        </div>
    </div>
    <div class="ibox-content">
      <table class="table">
        <thead>
          <tr>
            <th width="20%">商品名称</th>
            <th width="20%">商品图片</th>
            <th width="15%">条形码</th>
            <th width="15%">型号</th>        
            <th width="10%">需求数量</th>
            <th width="10%">供货价</th>
            <th>可供货数量</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($order_goods as $key => $vv) {
          ?>
          <tr>
            <td><?= $vv->goods_name?></td>
            <td><img src="/<?= $vv->goods->goods_img?>" alt="" style="width:100px;"></td>
            <td><?= $vv->isbn?></td>
            <td><?= $vv->goods->goods_sn?></td>
            <td><?= $vv->number?></td>
            <td><div class="lable_edit" data-id="<?= $vv->id?>" data-type="return_ask_price"><?= $vv->return_ask_price?></div></td>
            <td><div class="lable_edit" data-id="<?= $vv->id?>" data-type="return_number"><?= $vv->return_number?></div></td>
          </tr>
          <?php
          }?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(".lable_edit").click(
    function(){
      var id = '<?= Yii::$app->request->get("id")?>';
      var data_id = $(this).attr("data-id");
      var data_type = $(this).attr("data-type");
      var target = $(this);
      if($(this).children("input").length > 0){
      }else{
        var ima_code = $(this).text();
        var input_html = '<input type="text" value="'+ima_code+'" style="width:100%;" class="edit_input" />'; 
        $(this).html(input_html);
        $('.edit_input').focus();
        $('.edit_input').select();
      }
      $('.edit_input').blur(function(){
        var value = $(this).val();
        if(value == ima_code){
            target.html(ima_code);
            return false;
          }
        $.get('<?= Url::to(["/supplier-price/update-label"]) ?>',{data_value: value, id:id, data_id: data_id, data_type:data_type},function(result){
          if(result.error == 1){
            target.html(result.content);
          }else{
            target.html(ima_code);
            layer.msg(result.message,function(){});
          }
        },'json');
      });
      $('.edit_input').keydown(function(event){
        if(event.keyCode == 13) {
          event.stopPropagation();
          event.preventDefault();
          var value = $(this).val();
          if(value == ima_code){
            target.html(ima_code);
            return false;
          }
        $.get('<?= Url::to(["/supplier-price/update-label"]) ?>',{value: value, id:id, data_id: data_id, data_type:data_type},function(result){
          if(result.error == 1){
            target.html(result.content);
          }else{
            target.html(ima_code);
            layer.msg(result.message,function(){});
          }
        },'json');
        }
      });
   
    }
  );
</script>
<?php
  }else{
?>

<div style="width:500px;margin:5em auto;">
  <div class="ibox animated fadeInDown">
    <div class="ibox-title">请输入访问密码</div>
    <div class="ibox-content">
      <form action="/supplier-price?id=<?= $ask_price_order->id?>" method="post" class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-2 control-label">密码</label>
            <div class="col-sm-8">
                <input type="text" name="secrect" class="form-control"/>
            </div>
            <div class="col-sm-2">
                <input type="submit" class="btn btn-primary btn-sm" value="提交">
            </div>
        </div>
        
      </form>
    </div>
  </div>
</div>

<?php
  }
?>