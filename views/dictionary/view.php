<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;
use app\common\config\sys_config;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客维商品管理-数据字典';
$this->params['breadcrumbs'][] = ['label'=>'数据字典','url'=>['index']];
$this->params['breadcrumbs'][] = $dictionary->dictionary_desc;

?>

<?= app\common\widgets\GoodsList::widget([
                      'model'=>'app\common\models\DictionaryValue',
                      'model_name'=>$this->context->id,
                      'init_condition'=>[['dictionary_id'=>$id]],
                      'title_arr'=>['id'=>['sort_able'=>1,'edit_able'=>0,'width'=>'4%'],
                                    'dictionary_value'=>['sort_able'=>0,'edit_able'=>1,'width'=>'10%'],
                                    'sort'=>['sort_able'=>1,'edit_able'=>1,'width'=>'10%'],
                                    ],
                      'present_action'=>'view',
                      'update_label_url' => Url::to(['/'.$this->context->id.'/update-dictionary-value']),
                      'opration' => [['lable_name'=>'删除','type'=>'js','action'=>'delete-label','icorn_name'=>'trash','confirm'=>1]]
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加'.$this->context->page_title.'值','id'=>'add_dictionary_value','type'=>'js','url'=>Url::to(["/dictionary/create-value","id"=>$dictionary->id])],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            'backup'=>['label_name'=>'返回列表','type'=>'js','url'=>'/'.$this->context->id],
                                            ])
?>

<script type="text/javascript">
$("#add_dictionary_value").click(function(){
  //页面层
  layer.open({
    type: 2,
    title:'添加<?= $dictionary->dictionary_desc?>',
    //skin: 'layui-layer-rim', //加上边框
    area: ['80%', '80%'], //宽高
    maxmin: true,
    content: '<?= Url::to(["/dictionary/create-value","id"=>$dictionary->id])?>'
  });
});

</script>