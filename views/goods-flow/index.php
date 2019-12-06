<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;
use app\common\config\sys_config;
use app\common\models\DictionaryValue;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客维商品管理';
$this->params['breadcrumbs'][] = $this->context->page_title;
?>
 
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['goods-flow/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'goods_id','label_name'=>'商品名称','id'=>'goods_name'],
                                  ],
                       'more_search'=>false
                      ])
?>

<script type="text/javascript">
$(document).ready(function() {
    $("#goods_name").tokenInput('/<?= $this->context->id?>/search-goods',
      {
        theme:'facebook', 
        <?php if($search_data['goods_id'] >= 1){ ?>prePopulate:[{id:'<?= $search_data['goods_id'] ?>',name:'<?= $search_data['goods_name'] ?>'}],<?php }?>
        hintText:'请输入要搜索的关键字,可以是商品名称，型号',
        tokenLimit:1
      }
    );
});
</script>


<?= app\common\widgets\OperateBar::widget([
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>
 