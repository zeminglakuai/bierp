<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

?>
<?= Html::cssFile('@web/css/plugins/iCheck/custom.css') ?>
<?= Html::jsFile('@web/js/plugins/iCheck/icheck.min.js') ?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>
<div class="ibox">
  <div class="ibox-content">
    <form action="<?= Url::to(['/brand/update']) ?>" method="post" class="form-horizontal" id="brand_form"  enctype="multipart/form-data">
      <?= app\common\widgets\Input::widget(['label_name'=>'品牌名称','name'=>"Brand[brand_name]",'value'=>$brand->brand_name,'tips'=>'']); ?>
      <div class="form-group">
              <label class="col-sm-2 control-label">供货商</label>
              <div class="col-sm-10" id="supplier_choise">
                <?php
                if(count($brand->suppliers) > 0){

                  $i = 0;
                  foreach($brand->suppliers as $kk => $vv){?>
                          <div class="row" style="margin:0 0px;margin-bottom:10px;">
                            <div class="col-xs-6">
                              <input type="text" id="supplier_<?= $i?>" class="supplier"  name="Supplier[]"  class="form-control" >
                            </div>
                            <div class="col-xs-6">
                              <?php if($i == 0){?>
                              <a href="javascript:void();" id="add_supplier">[+]</a>
                              <?php }else{ ?>
                              <a href="javascript:void();" class="reduce_supplier">[-]</a>
                              <?php }?>
                            </div>
                          </div>
                          <script type="text/javascript">
                            $(document).ready(function() {
                                $("#supplier_<?= $i?>").tokenInput("<?= Url::to(['/goods/search-supplier'])?>",
                                  {
                                    theme:'facebook',
                                    hintText:'请输入要搜索的关键字',
                                    tokenLimit:1,
                                    <?php if($vv->id >= 1){ ?>prePopulate:[{id:'<?= $vv->id?>',name:'<?= $vv->supplier_name?>'}],<?php }?>
                                  }
                                );
                            });
                          </script>
                  <?php
                  $i++;
                  }
                }else{
                ?>
                  <div class="row" style="margin:0 0px;margin-bottom:10px;">
                    <div class="col-xs-6">
                      <input type="text" id="supplier_0"  name="Supplier[]"  class="form-control" >
                    </div>
                    <div class="col-xs-6"><a href="javascript:void();" id="add_supplier">[+]</a></div>
                  </div>
                  <script type="text/javascript">
                  $(document).ready(function() {
                      $("#supplier_0").tokenInput("<?= Url::to(['/goods/search-supplier'])?>",
                        {
                          theme:'facebook',
                          hintText:'请输入要搜索的关键字',
                          tokenLimit:1
                        }
                      );
                  });
                  </script>
                  <?php
                }?>

              </div>

          </div>
      <?= app\common\widgets\Radio::widget(['label_name'=>'是否自营','name'=>"Brand[is_self_sell]",'value'=>$brand->is_self_sell,'init_value'=>[['label_name'=>是,'value'=>'1'],['label_name'=>否,'value'=>'0']]]); ?>
      <?= app\common\widgets\Input::widget(['label_name'=>'备注','name'=>"Brand[remark]",'value'=>$brand->remark,'tips'=>'']); ?>
      <input type="hidden" name="id" value="<?= $brand->id ?>" />
    </form>

    </div>
  </div>
 <?= app\common\widgets\Submit::widget(['model'=>$brand,'model_name'=>"brand",'form_name'=>'brand_form']); ?>

<script>
  var supplier_num = <?= count($supplier)?>;
  $("#add_supplier").click(function(){
    supplier_num++;
    var the_row = $('<div class="row" style="margin:0 0px;margin-bottom:10px;">'+
      '<div class="col-xs-6">'+
      '<input type="text" id="supplier_'+supplier_num+'" name="Supplier[]"  class="form-control" >'+
      '</div>'+
      '<div class="col-xs-6">'+
      '<a href="javascript:void();" class="reduce_supplier">[-]</a>'+
      '</div>'+
      '</div>');
    the_row.appendTo("#supplier_choise");

    $("#supplier_"+supplier_num).tokenInput("<?= Url::to(['/goods/search-supplier'])?>",
      {
        theme:'facebook',
        hintText:'请输入要搜索的关键字',
        tokenLimit:1
      }
    );
    $(".reduce_supplier").click(function(){
      $(this).parent("div").parent("div").remove();
    })
  });

  $(".reduce_supplier").click(function(){
    $(this).parent("div").parent("div").remove();
  })

  $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
</script>
