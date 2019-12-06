<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\common\models\Store;
use app\common\config\lang_value_config;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

 
$this->params['breadcrumbs'][] = $this->context->page_title;
?>
<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['storage/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'goods@goods_name','label_name'=>'商品名称'],
                                  ['type'=>'select','label'=>'stock@store_id','label_name'=>'仓库','value_arr'=>ArrayHelper::map(Store::find()->all(), 'id', 'store_name'),'prompt'=>'请选择仓库'],
                                  ]
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\Stock',
                      'model_name'=>'storage',
                      'init_condition'=>$init_condition,
                      'title_arr'=>['goods_id'=>1,'goods&goods_name'=>0,'goods&goods_sn'=>0,'goods&isbn'=>0,'number'=>1,'store&store_name'=>0],
                      'search_allowed' => ['goods@goods_name'=>2,'stock@store_id'=>2,'type'=>2],
                      'opration' => [],
                      'scope'=>true,
                      'slave'=>'goods',
                      'slave_condition'=>[['goods.cat_id'=>Yii::$app->request->get('cat_id')],['like','goods.goods_name','Yii::$app->request->get("goods_name")']],
                      ])
?>

<a>开始盘点</a>
<a>结束盘点</a>

    <form action="<?= Url::to(['/supplier-price/import-stock'])?>" method="post"  class="form-horizontal" enctype="multipart/form-data">

        <div class="row" style="margin-bottom:5em;">
            <div class="col-sm-4">
                <input type="file" name="Goods[ppt_file]"  class="form-control"  align="left"/>

            </div>
            <div class="col-sm-3">
                <input type="submit" name="submit" value="导入产品" />
                                  <a href="/storage/exports?template_id=89&module_name=storage&type=title" class="btn btn-danger btn-sm"><i class="fa fa-plus"></i> 库存导出</a>

            </div>

        </div></form>



<?= app\common\widgets\OperateBar::widget([
//    'create'=>['label_name'=>'添加商品整合','id'=>'add_goods','type'=>'js','icon'=>'plus','url'=>Url::to(["/goods-implode/create"])],
    'export'=>['label_name'=>'库存导出','module_name'=>'storage','type'=>'title'],

                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>



