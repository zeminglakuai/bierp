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
$this->params['breadcrumbs'][] = ['label'=>'商品列表','url'=>['index?type=2']];
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
                                                    <div class="col-sm-4"><div class="btns"><div class="uploadBtn">上传</div></div></div>
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
                                <?= app\common\widgets\Input::widget(['label_name'=>'条形码','name'=>"Goods[isbn]",'value'=>$goods->isbn,'tips'=>'']); ?>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span class="red">*</span>商品分类</label>
                                    <div class="col-sm-9">
                                        <Select  name="Goods[cat_id]" class="form-control" >
                                            <option value="0">商品分类</option>
                                            <?= $cat_list?>
                                        </Select>
                                    </div>
                                </div>
                                <input type="hidden" name="type" value="<?= $type?>" />

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

                                <?= app\common\widgets\Radio::widget(['label_name'=>'<span class="red">*</span>是否自营','name'=>"Goods[is_self_sell]",'value'=>$goods->is_self_sell?$goods->is_self_sell:'1','init_value'=>[['label_name'=>是,'value'=>'1'],['label_name'=>否,'value'=>'0']]]); ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span class="red">*</span>销售指导价</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="Goods[market_price]" size="20" class="form-control"  spaceholder="销售指导价" value="<?= $goods->shop_price?>"/>
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
                                    <label class="col-sm-2 control-label">采购价</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="Goods[supplier_price]" spaceholder="采购价" class="form-control"  value="<?= $goods->supplier_price?>"/>
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
                                    <label class="col-sm-2 control-label">箱规</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="Goods[carton]" spaceholder="箱规" class="form-control"  value="<?= $goods->carton ?>"/>
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
                                        if (isset($goods_supplier)){
                                            foreach ($goods_supplier as $key=>$val){
                                                ?>

                                                <div style="margin-bottom:10px;">

                                                    <div class="col-sm-10" id="supplier_choise">
                                                        <div style="margin-bottom:10px;">
                                                            <div class="col-xs-4">
                                                                <input type="hidden" name="supplier[<?=$key?>][goods_supplier_id]" value="<?= $val['goods_supplier_id']?>" />
                                                                <input type="hidden" name="supplier[<?=$key?>][supplier_id]" value="<?= $val['supplier_id']?>" />
                                                                <input type="text" id="supplier_id<?=$key?>" class="supplier"  name="supplier[<?=$key?>][supplier_id]"   class="form-control" >
                                                            </div>
                                                            <div class="col-xs-2">
                                                                <input type="text" name="supplier[<?=$key?>][supplier_price]" class="form-control" size="20" placeholder="供货商价格" value="<?= $val['supplier_price']?>"/>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <script type="text/javascript">

                                                    $("#supplier_id<?=$key?>").tokenInput("<?= Url::to(['/goods/search-supplier'])?>",
                                                        {
                                                            theme: 'facebook',
                                                            hintText: '请输入要搜索的供货商关键字',
                                                            tokenLimit: 1,
                                                            <?php if($val['supplier_id']>= 1){ ?>prePopulate: [{
                                                                id: '<?=$val['supplier_id']?>',
                                                                name: '<?=$val['supplier_name']?>'
                                                            }],<?php }?>
                                                        }
                                                    );

                                                </script>
                                                <?php
                                            }}
                                        ?>
                                        <div style="margin-bottom:10px;">

                                            <div class="col-sm-10" id="supplier_choise">
                                                <div style="margin-bottom:10px;">
                                                    <div class="col-xs-4">
                                                        <input type="text" id="supplier_id11" class="supplier"  name="supplier[11][supplier_id]"  class="form-control" >
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <input type="text" name="supplier[11][supplier_price]" class="form-control" size="20" placeholder="供货商价格" value=""/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="margin-bottom:10px;">

                                            <div class="col-sm-10" id="supplier_choise">
                                                <div style="margin-bottom:10px;">
                                                    <div class="col-xs-4">
                                                        <input type="text" id="supplier_id12" class="supplier"  name="supplier[12][supplier_id]"  class="form-control" >
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <input type="text" name="supplier[12][supplier_price]" class="form-control" size="20" placeholder="供货商价格"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="margin-bottom:10px;">

                                            <div class="col-sm-10" id="supplier_choise">
                                                <div style="margin-bottom:10px;">
                                                    <div class="col-xs-4">
                                                        <input type="text" id="supplier_id13" class="supplier"  name="supplier[13][supplier_id]"  class="form-control" >
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <input type="text" name="supplier[13][supplier_price]" class="form-control" size="20" placeholder="供货商价格" value=""/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="margin-bottom:10px;">

                                            <div class="col-sm-10" id="supplier_choise">
                                                <div style="margin-bottom:10px;">
                                                    <div class="col-xs-4">
                                                        <input type="text" id="supplier_id14" class="supplier"  name="supplier[14][supplier_id]"  class="form-control" >
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <input type="text" name="supplier[14][supplier_price]" class="form-control" size="20" placeholder="供货商价格" value=""/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script type="text/javascript">
                                        $("#supplier_id11").tokenInput("<?= Url::to(['/goods/search-supplier'])?>",
                                            {
                                                theme: 'facebook',
                                                hintText: '请输入要搜索的供货商关键字',
                                                tokenLimit: 1,
                                            }
                                        );
                                        $("#supplier_id12").tokenInput("<?= Url::to(['/goods/search-supplier'])?>",
                                            {
                                                theme: 'facebook',
                                                hintText: '请输入要搜索的供货商关键字',
                                                tokenLimit: 1,
                                            }
                                        );
                                        $("#supplier_id13").tokenInput("<?= Url::to(['/goods/search-supplier'])?>",
                                            {
                                                theme: 'facebook',
                                                hintText: '请输入要搜索的供货商关键字',
                                                tokenLimit: 1,
                                            }
                                        );
                                        $("#supplier_id14").tokenInput("<?= Url::to(['/goods/search-supplier'])?>",
                                            {
                                                theme: 'facebook',
                                                hintText: '请输入要搜索的供货商关键字',
                                                tokenLimit: 1,
                                            }
                                        );
                                        /*   function xin(i) {
                                               $s = i + 1;
                                               $sol = '<div style="margin-bottom:10px;margin-left: 30px;"><div class="form-group"><div class="col-xs-4" style="width:26.5%;"><input type="text" style="width:100%;" id="supplier_id' + i + '" class="supplier"name="supplier[' + i + '][supplier_id]"class="form-control" > </div><div class="col-xs-2" style="width:13%;"><input type="text" name="supplier[' + i + '][supplier_price]" class="form-control" size="20"  placeholder="供货商价格" /></div><div class="col-xs-3"><span onclick="xin(' + $s + ')">新增</span></div></div></div>';
                                               $('#supplier_choise').after($sol);
                                           }*/
                                    </script>
                                </div>
                                <script src="/js/bootstrap-datetimepicker.min.js"></script>
                                <script src="/js/bootstrap-datetimepicker.zh-CN.js"></script>
                                <link href="/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
                                <?php
                                if (isset($goods_platform)){
                                    foreach ($goods_platform as $key=>$val){
                                        ?>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">上线平台</label>
                                            <div class="col-sm-3">
                                                <select name="platform[<?=$key?>][platform_id]">
                                                    <?php
                                                    foreach ($platform as $k=>$v){
                                                        ?>
                                                        <option value ="<?=$v['id'];?>" <?php if ($val['platform_id']==$v['id']){ echo "selected";};?>><?=$v['plat_name']?></option>

                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                                <label class="col-sm-2 control-label">是否代发</label>
                                                <label><input name="platform[<?=$key?>][daifa]" type="radio" value="0" <?php if ($val['daifa']==0){ echo "checked";};?> />是 </label>
                                                <label><input name="platform[<?=$key?>][daifa]" type="radio" value="1" <?php if ($val['daifa']==1){ echo "checked";};?>/>否 </label>
                                            <input name="platform[<?=$key?>][goods_platform_id]" type="hidden" value="<?=$val['goods_platform_id'];?>"/>
                                        </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label"> 开始日期</label>
                                                <div class="col-sm-9">
                                                    <div class="input-group date form_date11" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                        <input class="form-control" name="platform[<?=$key?>][startdate]" size="16" value="<?=$val['startdate']?>" type="text"  readonly="" placeholder="开始时间">
                                                        <span class="input-group-addon">
		            		<span class="glyphicon glyphicon-calendar"></span>
		              	</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                $('.form_date11').datetimepicker({
                                                    language:  'zh-CN',
                                                    weekStart: 1,
                                                    todayBtn:  1,
                                                    autoclose: 1,
                                                    todayHighlight: 1,
                                                    startView: 2,
                                                    minView: 2,
                                                    forceParse: 0
                                                });
                                            </script>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">结束日期</label>
                                                <div class="col-sm-9">
                                                    <div class="input-group date form_date12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                        <input class="form-control" name="platform[<?=$key?>][enddate]" size="16" value="<?=$val['enddate']?>" type="text"  readonly="" placeholder="结束日期">
                                                        <span class="input-group-addon">
		            		<span class="glyphicon glyphicon-calendar"></span>
		              	</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                $('.form_date12').datetimepicker({
                                                    language:  'zh-CN',
                                                    weekStart: 1,
                                                    todayBtn:  1,
                                                    autoclose: 1,
                                                    todayHighlight: 1,
                                                    startView: 2,
                                                    minView: 2,
                                                    forceParse: 0
                                                });
                                            </script>


                                        <?php
                                    }}
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">上线平台</label>
                                    <div class="col-sm-3">
                                        <select name="platform[11][platform_id]">
                                            <option value="" selected>选择上线平台</option>
                                            <?php
                                            foreach ($platform as $k=>$v){
                                                ?>
                                                <option value ="<?=$v['id'];?>" ><?=$v['plat_name']?></option>

                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <label class="col-sm-2 control-label">是否代发</label>
                                    <label><input name="platform[11][daifa]" type="radio" value="0" checked/>是 </label>
                                    <label><input name="platform[11][daifa]" type="radio" value="1" />否 </label>
                                 </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"> 开始日期</label>
                                        <div class="col-sm-9">
                                            <div class="input-group date form_date11" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                <input class="form-control" name="platform[11][startdate]" size="16" value="" type="text"  readonly="" placeholder="开始时间">
                                                <span class="input-group-addon">
		            		<span class="glyphicon glyphicon-calendar"></span>
		              	</span>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        $('.form_date11').datetimepicker({
                                            language:  'zh-CN',
                                            weekStart: 1,
                                            todayBtn:  1,
                                            autoclose: 1,
                                            todayHighlight: 1,
                                            startView: 2,
                                            minView: 2,
                                            forceParse: 0
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">结束日期</label>
                                        <div class="col-sm-9">
                                            <div class="input-group date form_date12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                <input class="form-control" name="platform[11][enddate]" size="16" value="" type="text"  readonly="" placeholder="结束日期">
                                                <span class="input-group-addon">
		            		<span class="glyphicon glyphicon-calendar"></span>
		              	</span>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        $('.form_date12').datetimepicker({
                                            language:  'zh-CN',
                                            weekStart: 1,
                                            todayBtn:  1,
                                            autoclose: 1,
                                            todayHighlight: 1,
                                            startView: 2,
                                            minView: 2,
                                            forceParse: 0
                                        });
                                    </script>


                                <div class="form-group">
                                    <label class="col-sm-2 control-label">上线平台</label>
                                    <div class="col-sm-3">

                                        <select name="platform[12][platform_id]">
                                            <option value="" selected>选择上线平台</option>
                                            <?php
                                            foreach ($platform as $k=>$v){
                                                ?>
                                                <option value ="<?=$v['id'];?>"><?=$v['plat_name']?></option>

                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <label class="col-sm-2 control-label">是否代发</label>
                                    <label><input name="platform[12][daifa]" type="radio" value="0" checked/>是 </label>
                                    <label><input name="platform[12][daifa]" type="radio" value="1" />否 </label>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"> 开始日期</label>
                                    <div class="col-sm-9">
                                        <div class="input-group date form_date13" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control" name="platform[12][startdate]" size="16" value="" type="text"  readonly="" placeholder="开始时间">
                                            <span class="input-group-addon">
		            		<span class="glyphicon glyphicon-calendar"></span>
		              	</span>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    $('.form_date13').datetimepicker({
                                        language:  'zh-CN',
                                        weekStart: 1,
                                        todayBtn:  1,
                                        autoclose: 1,
                                        todayHighlight: 1,
                                        startView: 2,
                                        minView: 2,
                                        forceParse: 0
                                    });
                                </script>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">结束日期</label>
                                    <div class="col-sm-9">
                                        <div class="input-group date form_date14" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control" name="platform[12][enddate]" size="16" value="" type="text"  readonly="" placeholder="结束日期">
                                            <span class="input-group-addon">
		            		<span class="glyphicon glyphicon-calendar"></span>
		              	</span>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    $('.form_date14').datetimepicker({
                                        language:  'zh-CN',
                                        weekStart: 1,
                                        todayBtn:  1,
                                        autoclose: 1,
                                        todayHighlight: 1,
                                        startView: 2,
                                        minView: 2,
                                        forceParse: 0
                                    });
                                </script>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">上线平台</label>
                                    <div class="col-sm-3">
                                        <select name="platform[13][platform_id]">
                                            <option value="" selected>选择上线平台</option>
                                            <?php
                                            foreach ($platform as $k=>$v){
                                                ?>
                                                <option value="<?=$v['id'];?>" ><?=$v['plat_name']?></option>

                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <label class="col-sm-2 control-label">是否代发</label>
                                    <label><input name="platform[13][daifa]" type="radio" value="0" checked/>是 </label>
                                    <label><input name="platform[13][daifa]" type="radio" value="1" />否 </label>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"> 开始日期</label>
                                    <div class="col-sm-9">
                                        <div class="input-group date form_date15" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control" name="platform[13][startdate]" size="16" value="" type="text"  readonly="" placeholder="开始时间">
                                            <span class="input-group-addon">
		            		<span class="glyphicon glyphicon-calendar"></span>
		              	</span>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    $('.form_date15').datetimepicker({
                                        language:  'zh-CN',
                                        weekStart: 1,
                                        todayBtn:  1,
                                        autoclose: 1,
                                        todayHighlight: 1,
                                        startView: 2,
                                        minView: 2,
                                        forceParse: 0
                                    });
                                </script>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">结束日期</label>
                                    <div class="col-sm-9">
                                        <div class="input-group date form_date16" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control" name="platform[13][enddate]" size="16" value="" type="text"  readonly="" placeholder="结束日期">
                                            <span class="input-group-addon">
		            		<span class="glyphicon glyphicon-calendar"></span>
		              	</span>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    $('.form_date16').datetimepicker({
                                        language:  'zh-CN',
                                        weekStart: 1,
                                        todayBtn:  1,
                                        autoclose: 1,
                                        todayHighlight: 1,
                                        startView: 2,
                                        minView: 2,
                                        forceParse: 0
                                    });
                                </script>
                                <?= app\common\widgets\Radio::widget(['label_name'=>'<span class="red">*</span>含税','name'=>"Goods[is_clude_tax]",'value'=>$goods->is_clude_tax?$goods->is_clude_tax:1,'init_value'=>[['label_name'=>是,'value'=>'1'],['label_name'=>否,'value'=>'0']]]); ?>
                                <?= app\common\widgets\Input::widget(['label_name'=>'<span class="red">*</span>含运费','name'=>"Goods[clude_shipping_fee]",'value'=>$goods->clude_shipping_fee]); ?>
                                <?= app\common\widgets\Radio::widget(['label_name'=>'<span class="red">*</span>商品有效','name'=>"Goods[is_active]",'value'=>$goods->is_active?$goods->is_active:1,'init_value'=>[['label_name'=>是,'value'=>'1'],['label_name'=>否,'value'=>'0']],'tips'=>'asdas']); ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">PPT文件</label>
                                    <div class="col-sm-7">
                                        <input type="file" name="Goods[ppt_file]" class="form-control">
                                    </div>
                                    <label class="col-sm-2 control-label">
                                        <?php if($goods->ppt_file){?>
                                            <a href="/<?= $goods->ppt_file?>" target="_blank">查看PPT文件</a>
                                        <?php }else{echo '未上传PPT文件';}; ?>
                                    </label>
                                </div>


                                <div class="form-group">
                                    <label class="col-sm-2 control-label">呆销期</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="Goods[duty_period]" spaceholder="呆销期" class="form-control"  value="<?= $goods->duty_period ?>"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">备注</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="Goods[remarks]" spaceholder="备注" class="form-control"  value="<?= $goods->remarks ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div id="activity_desc" class="tab-pane">
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-1 control-label">商品简介</label>
                            <label class="col-sm-11 control-label">
                                <textarea id="ccomment" name="Goods[goods_brief]" spaceholder="商品简介" class="form-control" ><?= $goods->goods_brief?></textarea>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-1 control-label">商品详情</label>
                            <label class="col-sm-11 control-label">
                                <script type="text/plain" id="editor" name="Goods[goods_desc]" style="height:300px;"><?= $goods->goods_desc?></script>
                            </label>
                        </div>
                        <script type="text/javascript">var editor = UE.getEditor('editor');</script>
                    </div>
                </div>
                <div id="relative_file" class="tab-pane">
                    <div class="panel-body">
                        <?= app\common\widgets\FileList::widget(['model'=>'goods','file_list'=>$goods->goodsFile]); ?>
                    </div>
                </div>
            </div>
            <div class="" style="padding:1em;margin-bottom:10em;">
                <label class="col-sm-2 control-label"></label>
                <label class="col-sm-2 control-label">
                    <button type="submit" class="btn btn-danger">提交</button>
                </label>
                <input type="hidden" name="goods_id" value="<?= $goods->goods_id?>" />

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