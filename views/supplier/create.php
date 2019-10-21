<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\modules\admin\config\config;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客维商品管理-添加供货商';
$this->params['breadcrumbs'][] = ['label' => '供货商列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $oparate=='insert'?'添加供货商':'编辑供货商-'.$supplier->supplier_name;

?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>
<?= Html::cssFile('@web/css/plugins/iCheck/custom.css') ?>
<?= Html::jsFile('@web/js/plugins/iCheck/icheck.min.js') ?>

<div class="row">
  <div class="col-sm-12">
    <div class="tabs-container">
      <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 基本信息</a></li>
        <li class=""><a data-toggle="tab" href="#tab-4" aria-expanded="true">财务信息</a></li>
        <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false">相关图片</a></li>
        <?php if(isset($supplier->id)){ ?>
        <li class=""><a data-toggle="tab" href="#tab-5" aria-expanded="true">收货地址</a></li>
        <li class=""><a data-toggle="tab" href="#tab-6" aria-expanded="true"> 联系人列表</a></li>
        <?php } ?>
      </ul>
      <form method="post" class="form-horizontal" id="supplier_form" enctype="multipart/form-data">
      <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
              <?= app\common\widgets\Input::widget(['label_name'=>'供货商名称','name'=>"Supplier[supplier_name]",'value'=>$supplier->supplier_name,'tips'=>'']); ?>
              <?= app\common\widgets\Input::widget(['label_name'=>'联系人','name'=>"Supplier[contact]",'value'=>$supplier->contact,'tips'=>'']); ?>
              <?= app\common\widgets\Input::widget(['label_name'=>'职位','name'=>"Supplier[position]",'value'=>$supplier->position,'tips'=>'']); ?>
              <?= app\common\widgets\Input::widget(['label_name'=>'手机','name'=>"Supplier[tel]",'value'=>$supplier->tel,'tips'=>'']); ?>
              <?= app\common\widgets\Input::widget(['label_name'=>'固话','name'=>"Supplier[guhua]",'value'=>$supplier->guhua,'tips'=>'']); ?>
              <?= app\common\widgets\Input::widget(['label_name'=>'QQ','name'=>"Supplier[qq]",'value'=>$supplier->qq,'tips'=>'']); ?>
              <?= app\common\widgets\Radio::widget(
              [
              'label_name'=>'一件代发',
              'name'=>"Brand[is_self_sell]",
              'value'=>$brand->is_self_sell,
              'init_value'=>[['label_name'=>是,'value'=>'1'],['label_name'=>否,'value'=>'0']]
              ]
              ); ?>

              <?= app\common\widgets\Input::widget(['label_name'=>'备注','name'=>"Supplier[remark]",'value'=>$supplier->remark,'tips'=>'']); ?>
            </div>
        </div>
        <div id="tab-2" class="tab-pane">
            <div class="panel-body">
                
            </div>
        </div>
        <div id="tab-3" class="tab-pane">
            <div class="panel-body">
                
            </div>
        </div>
        <div id="tab-4" class="tab-pane">
            <div class="panel-body">
              <?= app\common\widgets\Input::widget(['label_name'=>'支付宝账号','name'=>"Supplier[alipay]",'value'=>$supplier->alipay,'tips'=>'']); ?>
              <div class="form-group">
                  <label class="col-sm-2 control-label">银行信息</label>
                  <div class="col-sm-9">
                      <div class="row">
                        <div class="col-xs-4"><input type="text" name="Supplier[bank_name]"  placeholder = '银行名称' class="form-control" value="<?= $supplier->bank_name?>" /></div>
                        <div class="col-xs-4"><input type="text" name="Supplier[bank_open]"  placeholder = '开户行' class="form-control" size="35" value="<?= $supplier->bank_open?>" /></div>
                        <div class="col-xs-4"><input type="text" name="Supplier[bank_code]"  placeholder = '银行账号' class="form-control" size="35"  value="<?= $supplier->bank_code?>"/></div>
                      </div>
                  </div>
              </div>
          </div>
        </div>
        <?php if(isset($supplier->id)){ ?>
        <div id="tab-5" class="tab-pane">
            <div class="panel-body">
                
            </div>
        </div>
        <div id="tab-6" class="tab-pane">
            <div class="panel-body">
                
            </div>
        </div>
        <?php } ?>

      </div>
    </form>
    </div>
  </div>
</div>
<?= app\common\widgets\Submit::widget(['model'=>$supplier,'model_name'=>"supplier",'form_name'=>'supplier_form']); ?>

<?= app\common\widgets\OperateBar::widget([
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'','icon'=>'plus'],
                                            'backup'=>['label_name'=>'返回列表','type'=>'js','id'=>'add_custom','icon'=>'back'],
                                            ])
?> 
<script>
  $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
</script>