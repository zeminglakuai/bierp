    <?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\modules\admin\config\config;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客维商品管理';
$this->params['breadcrumbs'][] = '商品品牌';
?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['/brand/index']),
                      'condition'=>[
                                    ['label_name'=>'品牌名称','label'=>'brand_name','type'=>'text'],
                                    ['label_name'=>'供货商','label'=>'supplier_id','name_name'=>'supplier_name','type'=>'token_input','table_name'=>'supplier','id'=>'supplier_id','token_url'=>'/brand/search-supplier']
                      ],
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\Brand',
                      'title_arr'=>['id'=>1,'brand_name'=>0,'is_self_sell'=>0,'remark'=>0],
                      'search_allowed' => ['brand_name'=>2,'supplier_id'=>1],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'js','action'=>'edit','icorn_name'=>'edit'],
                                      'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete','icorn_name'=>'trash','confirm'=>1]
                                    ],
                      'data_list'=>$data_list,
                      'pages'=>$pages,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加品牌','id'=>'create_brand','type'=>'js','url'=>Url::to(["brand/create"])],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>
 