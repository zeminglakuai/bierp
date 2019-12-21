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
    'url'=>Url::to(['export-order/index']),
    'condition'=>[
        ['type'=>'text','label'=>'order_sn','label_name'=>'单号'],
        ['type'=>'text','label'=>'platform_name','label_name'=>'主题名称'],
        ['type'=>'select','label'=>'store_id','label_name'=>'仓库','value_arr'=>ArrayHelper::map(Store::get_store(), 'id', 'store_name'),'prompt'=>'选择仓库'],
        ['type'=>'select','label'=>'export_order_status','label_name'=>'单据状态','value_arr'=>lang_value_config::$export_order_status],
    ]
])
?>

<?= app\common\widgets\DataList::widget([
    'model'=>'app\common\models\ExportOrder',
    'model_name'=>'export-order',
    'title_arr'=>['id'=>1,'order_sn'=>0,'platform_name'=>0,'shipping_method'=>0,'add_user_name'=>0,'add_time'=>0,'export_order_status'=>1,],
    'search_allowed' => ['order_sn'=>2,'platform_name'=>2,'export_order_status'=>1,'custom_order_id'=>2,'store_id'=>1],
    'opration' => ['add'=>['lable_name'=>'添加','type'=>'link','action'=>'add','icorn_name'=>'add','confirm'=>0],
        'edit'=>['lable_name'=>'编辑','type'=>'link','action'=>'view','icorn_name'=>'edit','confirm'=>0],
        'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete','icorn_name'=>'trash','confirm'=>1]
    ],
    'scope'=>true,
])
?>
    <form action="<?= Url::to(['/supplier-price/import-order']) ?>" method="post" class="form-horizontal" enctype="multipart/form-data">

        <div class="row" style="margin-bottom:5em;">
            <select class="form-control" name="id">
                <option value="">选择平台</option>
                <?php
                if (isset($platform)) {
                    foreach ($platform as $key => $val) {
                        ?>

                        <option value="<?= $val['id'] ?>"><?= $val['plat_name'] ?></option>
                        <?php
                    }
                }
                ?>


            </select>
            <div class="col-sm-4">

                <input type="file" name="Platform[ppt_file]" class="form-control" align="left" />

            </div>
            <div class="col-sm-3">
                <input type="submit" name="submit" value="导入产品" />

            </div>

        </div>
    </form>
<?= app\common\widgets\OperateBar::widget([
    'create'=>['label_name'=>'添加单据','id'=>'add','type'=>'js','url'=>Url::to(["export-order/add"])],

    'export'=>['label_name'=>'导出','id'=>'export','type'=>'js','sublist'=>[['label_name'=>'导出找货单','id'=>'1'],['label_name'=>'导出找货单','id'=>'2'],['label_name'=>'导出找货单','id'=>'3']]],
    'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
])
?>