<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\common\models\DictionaryValue;
use app\common\models\Brand;
use app\modules\admin\config\config;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客维商品管理-添加商品';
$this->params['breadcrumbs'][] = ['label'=>'商品列表','url'=>['index/type=2']];
$this->params['breadcrumbs'][] = $oparate=='insert'?'添加商品':'编辑商品-'.$goods->goods_name;
?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::cssFile('@web/css/plugins/iCheck/custom.css') ?>
<?= Html::jsFile('@web/js/plugins/iCheck/icheck.min.js') ?>
<?= Html::jsFile('@web/ueditor/ueditor.config.js') ?>
<?= Html::jsFile('@web/ueditor/ueditor.all.min.js') ?>
<?= Html::jsFile('@web/ueditor/lang/zh-cn/zh-cn.js') ?>
<?= Html::jsFile('@web/js/uploadPreview.js') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>


<form action="<?= $oparate=='insert'?Url::to(['/goods/insert']):Url::to(['/goods/update']) ?>" method="post"  class="form-horizontal" enctype="multipart/form-data">
  <div class="tabs-container">
  <ul class="nav nav-tabs">
    <li class="active"> <a data-toggle="tab" href="#activity_name">基本信息</a></li>
    <li> <a data-toggle="tab" href="#activity_desc">详情描述</a></li>
    <li> <a data-toggle="tab" href="#relative_file">相关文档</a></li>
  </ul>
  <div class="tab-content">
    <div id="activity_name" class="tab-pane active">
      <div class="panel-body">
      <div class="row">
        <div class="col-xs-3">
        	<div class="profile-picture" id="imgdiv">
         		<img id="imgShow" class="goods_thumb" style="width:100%;" src="<?php if($goods->goods_img <> ''){echo Yii::getalias('@web/').$goods->goods_img;}else{echo Yii::getalias('@web/img/profile_small.jpg');}?>" />
        	</div>
         <input type="file" name="Goods[goods_img]" id="up_img" style="display:none;" />
        </div>
        <div class="col-xs-9">
          <div class="form-group">
              <label class="col-sm-2 control-label">商品名称</label>
              <div class="col-sm-9">
                <input type="text" name="Goods[goods_name]" class="form-control"  value="<?= $goods->goods_name?>"/>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-2 control-label">商品型号</label>
              <div class="col-sm-9">
                <input type="text" name="Goods[goods_sn]" class="form-control"  value="<?= $goods->goods_sn?>"/>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-2 control-label">条形码</label>
              <div class="col-sm-9">
                <input type="text" name="Goods[isbn]" class="form-control"  value="<?= $goods->isbn?>"/>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-2 control-label">商品分类</label>
              <div class="col-sm-9">
                <Select  name="Goods[cat_id]" class="form-control" >
                  <option value="0">商品分类</option>
                  <?= $cat_list?>
                </Select>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-2 control-label">商品品牌</label>
              <div class="col-sm-9">
                <input type="text" name="Goods[brand_id]" id="brand" class="form-control" size="25" placeholder="商品品牌" value="<?= $goods->brand_id?>"/>
                  <script type="text/javascript">
                    $(document).ready(function() {
                        $("#brand").tokenInput(<?= Brand::getTokeninputlist() ?>,
                          {
                            theme:'facebook', 
                            <?php if($goods->brand_id >= 1){ ?>prePopulate:[{id:'<?= $goods->brand_id?>',name:'<?= $goods->brand->brand_name?>'}],<?php }?>
                            hintText:'请输入要搜索的关键字',
                            tokenLimit:1
                          }
                        );
                    });
                  </script>
              </div>
          </div>
          <?= app\common\widgets\Radio::widget(['label_name'=>'是否自营','name'=>"Goods[is_self_sell]",'value'=>$goods->is_self_sell,'init_value'=>[['label_name'=>是,'value'=>'1'],['label_name'=>否,'value'=>'0']]]); ?>
          <div class="form-group">
                                    <label class="col-sm-2 control-label"><span class="red">*</span>市场价</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="Goods[market_price]" size="20" class="form-control"  spaceholder="市场价" value="<?= $goods->market_price?>"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">特批价格</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="Goods[special_price]" spaceholder="特批价格" class="form-control"  value="<?= $goods->special_price?>"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">固定零售价</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="Goods[fixed_price]" spaceholder="固定零售价" class="form-control"  value="<?= $goods->fixed_price?>"/>
                                    </div>
                                </div>

          <div class="form-group">
              <label class="col-sm-2 control-label">产品保质期</label>
              <label class="col-sm-2 control-label"><input type="text" name="Goods[expire]" spaceholder="产品保质期" class="form-control"  value="<?= $goods->expire?>"/></label>
              <div class="col-sm-7">
                <?= Html::dropDownList('Goods[expire_unit]', $goods->expire_unit, ArrayHelper::map(DictionaryValue::getValueList(3), 'id', 'dictionary_value'),['textlabel' => '请选择时间单位','class'=>'form-control']) ?>
              </div>

          </div>

          <div class="form-group">
              <label class="col-sm-2 control-label">警告库存</label>
              <div class="col-sm-9">
                <input type="text" name="Goods[warn_number]" spaceholder="本店售价" class="form-control"  value="<?= $goods->warn_number?$goods->warn_number:$this->context->curr_config['default_warn_number'] ?>"/>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-2 control-label">商品单位</label>
              <div class="col-sm-9">
                <?= Html::dropDownList('Goods[goods_unit]', $goods->goods_unit, ArrayHelper::map(DictionaryValue::getValueList(1), 'id', 'dictionary_value'),['class'=>'form-control']) ?>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-2 control-label">商品重量</label>
              <label class="col-sm-2 control-label"><input type="text" name="Goods[goods_weight]" spaceholder="商品重量" class="form-control"  value="<?= $goods->goods_weight?>"/></label>
              <div class="col-sm-7">
                <?= Html::dropDownList('Goods[weight_unit]', $goods->weight_unit, ArrayHelper::map(DictionaryValue::getValueList(2), 'id', 'dictionary_value'),['textlabel' => '请选择重量单位','class'=>'form-control']) ?>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-2 control-label">供货商价格</label>
              <div class="col-sm-10" id="supplier_choise">
                <?php 
                if(count($supplier_price) > 0){
                  $i = 0;
                  foreach($supplier_price as $kk => $vv){?>
                          <div class="row" style="margin-bottom:10px;">
                            <div class="col-xs-4">
                              <input type="text" id="supplier_<?= $i?>" class="supplier"  name="supplier[]"  class="form-control" > 
                            </div>
                            <div class="col-xs-2">
                              <input type="text" name="purchase_price[]" class="form-control" size="25" placeholder="供货商价格" value="<?= $vv['purchase_price']?>"/>
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
                                    <?php if($vv->supplier_id >= 1){ ?>prePopulate:[{id:'<?= $vv->supplier_id?>',name:'<?= $vv->supplier->supplier_name?>'}],<?php }?>

                                  }
                                );
                            });
                          </script>
                  <?
                  $i++;
                  }
                }else{
                ?>
                  <div class="row" style="margin-bottom:10px;">
                    <div class="col-xs-4">
                      <input type="text" id="supplier_0"  name="supplier[]"  class="form-control" >
                    </div>
                    <div class="col-xs-2">
                      <input type="text" name="purchase_price[]" class="form-control" size="25" placeholder="供货商价格" value="<?= $vv['purchase_price']?>"/>
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

        </div>
      </div>
      </div>
    </div>
    <div id="activity_desc" class="tab-pane">
      <div class="panel-body">
      <Div class="row">
        <div class="col-xs-10"> 
          <script type="text/plain" id="editor" name="Goods[goods_desc]" style="height:300px;"><?= $goods->goods_desc?></script> 
        </div>
      </Div>
      <script type="text/javascript">var editor = UE.getEditor('editor');</script> 
    </div>
    </div>
    <div id="relative_file" class="tab-pane">
      <div class="panel-body">
        <?= app\common\widgets\FileList::widget(['model'=>'goods','file_list'=>$goods->goodsFile]); ?>
        
      </div>
    </div>
  </div>
  <div class="" style="padding:1em;">
    <input type="hidden" name="goods_id" value="<?= $goods->goods_id?>" />
    <button type="submit" class="btn btn-danger">提交</button>
  </div>
</form>

<script>
  var supplier_num = <?= count($supplier_price)?>;
	$("#add_supplier").click(function(){
    supplier_num++;
		var the_row = $('<div class="row" style="margin-bottom:10px;"><div class="col-xs-4"><input type="text" id="supplier_'+supplier_num+'" name="supplier[]"  class="form-control" ></div><div class="col-xs-2"><input type="text" name="purchase_price[]" class="form-control" placeholder="供货商价格"/></div><div class="col-xs-6"><a href="javascript:void();" class="reduce_supplier">[-]</a></div></div>');
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
	window.onload = function () { 
      new uploadPreview({ UpBtn: "up_img", DivShow: "imgdiv", ImgShow: "imgShow" });
  }
  $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});

  $("#imgShow").click(
    function(){
      $("#up_img").trigger('click');
    }
  );
</script>
