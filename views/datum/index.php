<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\common\config\config;
use app\common\config\lang_value_config;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客维商品管理';
$this->params['breadcrumbs'][] = '合同管理';
?>
<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['datum/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'datum_name','label_name'=>'合同名称'],
                                  ]
                      ])
?>


<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\Datum',
                      'model_name'=>'datum',
                      'init_condition' => $init_condition,
                      'title_arr'=>['id'=>1,'datum_name'=>0,'add_user_name'=>0,'add_time'=>0],
                      'search_allowed' => ['datum_name'=>2],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'js','action'=>'edit','icorn_name'=>'edit','confirm'=>0],
                                     'view'=>['lable_name'=>'查看内容','type'=>'js','action'=>'view','icorn_name'=>'eye-open','confirm'=>0],
                                      'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete','icorn_name'=>'trash','confirm'=>1]
                                    ],
                      'scope'=>true,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加文档','id'=>'create_datum','type'=>'js','url'=>Url::to(["datum/create"])],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],

                                            ])
?>

<script type="text/javascript">
  $(".view_datum").click(function(){
    var data_id = $(this).attr('data-id');
    var view_url = create_url('<?= Url::to(["/datum/view"])?>');
    layer.open({
      type: 2,
      title:'文档预览',
      //skin: 'layui-layer-rim', //加上边框
      area: ['100%', '100%'], //宽高
      maxmin: true,
      content: view_url+'id='+data_id
    });
  });
</script>