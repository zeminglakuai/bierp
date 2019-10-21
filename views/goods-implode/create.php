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
$this->params['breadcrumbs'][] = ['label'=>'商品列表','url'=>['index']];
$this->params['breadcrumbs'][] = $oparate=='insert'?'添加商品':'编辑商品-'.$goods->goods_name;
?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::cssFile('@web/css/plugins/iCheck/custom.css') ?>
<?= Html::cssFile('@web/css/demo/webuploader-demo.min.css') ?>
<?= Html::cssFile('@web/css/plugins/webuploader/webuploader.css') ?>

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
          <div class="page-container">
            
            <div id="uploader" class="wu-example">
                <div class="filelist">
                  <div class="queueList">
                    <?php if($goods->goods_img <> ''){ ?>
                    <img id="imgShow" class="goods_thumb" style="width:100%;" src="<?php echo Yii::getalias('@web/').$goods->goods_img; ?>" />
                    <?php }?>
                  </div>
                  <div class="statusBar">
                    <div class="row">
                      <div class="col-sm-8"><div id="filePicker"></div></div>
                      <div class="col-sm-4"><div class="btns"><div class="uploadBtn">开始上传</div></div></div>
                    </div>
                  </div>
                </div>
            </div>
          </div>
         <input type="hidden" name="Goods[goods_img]" value="<?= $goods->goods_img ?>" id="goods_img"/>
        </div>
        <div class="col-xs-9">
          <?= app\common\widgets\Input::widget(['label_name'=>'商品名称','name'=>"Goods[goods_name]",'value'=>$goods->goods_name,'tips'=>'','inneed'=>true]); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'商品型号','name'=>"Goods[goods_sn]",'value'=>$goods->goods_sn,'tips'=>'','inneed'=>true]); ?>
          <?= app\common\widgets\Input::widget(['label_name'=>'条形码','name'=>"Goods[isbn]",'value'=>$goods->isbn,'tips'=>'','inneed'=>true]); ?>

          <div class="form-group">
              <label class="col-sm-2 control-label"><span class="red">*</span>商品分类</label>
              <div class="col-sm-9">
                <Select  name="Goods[cat_id]" class="form-control" >
                  <option value="0">商品分类</option>
                  <?= $cat_list?>
                </Select>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-2 control-label"><span class="red">*</span>商品品牌</label>
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
          <?= app\common\widgets\Radio::widget(['label_name'=>'<span class="red">*</span>是否自营','name'=>"Goods[is_self_sell]",'value'=>$goods->is_self_sell,'init_value'=>[['label_name'=>是,'value'=>'1'],['label_name'=>否,'value'=>'0']]]); ?>
          <div class="form-group">
              <label class="col-sm-2 control-label"><span class="red">*</span>市场售价</label>
              <div class="col-sm-9">
                <input type="text" name="Goods[market_price]" size="20" class="form-control"  spaceholder="市场售价" value="<?= $goods->shop_price?>"/>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-2 control-label">指导售价</label>
              <div class="col-sm-9">
                <input type="text" name="Goods[shop_price]" spaceholder="本店售价" class="form-control"  value="<?= $goods->shop_price?>"/>
                <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 自营产品必须填写指导售价</span>
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
                <div class=" " style="margin-bottom:10px;">
                  <div class="col-xs-4">
                    <input type="text" id="supplier_id" class="supplier"  name="Goods[supplier_id]"  class="form-control" > 
                  </div>
                  <div class="col-xs-2">
                    <input type="text" name="Goods[supplier_price]" class="form-control" size="25" placeholder="供货商价格" value="<?= $goods['supplier_price']?>"/>
                  </div>
                  <div class="col-xs-6">
                  </div>
                </div>
                <script type="text/javascript">
                  $(document).ready(function() {
                      $("#supplier_id").tokenInput("<?= Url::to(['/goods/search-supplier'])?>",
                        {
                          theme:'facebook', 
                          hintText:'请输入要搜索的供货商关键字',
                          tokenLimit:1,
                          <?php if($goods->supplier_id >= 1){ ?>prePopulate:[{id:'<?= $goods->supplier_id?>',name:'<?= $goods->supplier_name?>'}],<?php }?>
                        }
                      );
                  });
                </script>
              </div>
          </div>
          <div class="form-group">
              <label class="col-sm-2 control-label">PPT文件</label>
              <label class="col-sm-2 control-label">
                <?php if($goods->ppt_file){?>
                <a href="/<?= $goods->ppt_file?>" target="_blank">查看PPT文件</a>
                <?php }else{echo '未上传PPT文件';}; ?>
              </label>
              <div class="col-sm-7">
                <input type="file" name="Goods[ppt_file]" class="form-control">
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
  <div class="" style="padding:1em;margin-bottom:5em;">
    <input type="hidden" name="goods_id" value="<?= $goods->goods_id?>" />
    <button type="submit" class="btn btn-danger">提交</button>
  </div>
</form>



<?= app\common\widgets\OperateBar::widget([
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'','icon'=>'plus'],
                                            'backup'=>['label_name'=>'返回列表','type'=>'js','id'=>'add_custom','icon'=>'back'],
                                            ])
?>

<script>
 
  $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});

  var BASE_URL = 'js/plugins/webuploader/';
  var UPLOAD_URL = '<?= Url::to(['/goods/upload-img'])?>';  
</script>
<?= Html::jsFile('@web/js/plugins/webuploader/webuploader015.min.js') ?>
<?= Html::jsFile('@web/js/plugins/webuploader/webuploader-user.min.js') ?>