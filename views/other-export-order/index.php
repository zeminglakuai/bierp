<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\common\models\Store;
use app\common\models\ApprovalProcess;
use app\common\config\lang_value_config;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->context->page_title;
?>
<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['other-export-order/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'order_sn','label_name'=>'单号'],
                                  ['type'=>'select','label'=>'store_id','label_name'=>'仓库','value_arr'=>ArrayHelper::map(Store::get_store(), 'id', 'store_name'),'prompt'=>'选择仓库'],
                                  ['type'=>'select','label'=>'order_type','label_name'=>'单据类型','value_arr'=>lang_value_config::$other_export_order_type],
                                  ['type'=>'select','label'=>'other_export_order_status','label_name'=>'单据状态','value_arr'=>ApprovalProcess::getApprovalValue($this->context->id)],
                                  ]
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\OtherExportOrder',
                      'model_name'=>'other-export-order',
                      'title_arr'=>['id'=>1,'order_sn'=>0,'store_name'=>0,'add_user_name'=>0,'add_time'=>0,'other_export_order_type'=>0,'other_export_order_status'=>1,],
                      'search_allowed' => ['order_sn'=>2,'custom_name'=>2,'other_export_order_status'=>1,'store_id'=>1],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'link','action'=>'view','icorn_name'=>'edit','confirm'=>0],
                                      'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete','icorn_name'=>'trash','confirm'=>1]
                                    ],
                      'scope'=>true,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加出库单','id'=>'create_export_order','type'=>'js','url'=>Url::to(["other-export-order/create"])],
                                            'export'=>['label_name'=>'导出','id'=>'export','type'=>'js','sublist'=>[['label_name'=>'导出找货单','id'=>'1'],['label_name'=>'导出找货单','id'=>'2'],['label_name'=>'导出找货单','id'=>'3']]],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>




<script>

    $('#button1').click(function(){
        var i = 1;

        <?php
        foreach($all as $k=>$v){
        ?>
        var a_<?=$k?> = $("#first<?=$k?>").attr('class');
        if(a_<?=$k?>.indexof('checked')){
            //alert(1);
        }else{
            //alert(2);
            $("#ul<?=$k?>").remove();
        }
        <?php
        }
        ?>
        $('#formid').submit();

    });

</script>