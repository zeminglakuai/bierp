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
$this->params['breadcrumbs'][] = '客户管理';

?>

<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
    <form action="<?= Url::to(['/custom/index'])?>" method="get" class="form-horizontal">
      <div class="row">
        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">客户名称</label>
            <div class="col-sm-9">
              <input type="text" name="custom_name" class="form-control" value="<?= Yii::$app->request->get('custom_name')?>" placeholder="客户名称"/>
            </div>
          </div>
        </div>        
      </div>
      <div class="row">
        <div class="col-sm-11">

        </div>
        <div class="col-sm-1">
          <input type="submit" class="btn btn-primary btn-sm" value="搜索"/>
        </div>
      </div>
    </form>
  </div>
</div>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\Custom',
                      'model_name'=>'custom',
                      'init_condition'=>[['is_delete'=>0]],
                      'title_arr'=>['id'=>1,'custom_name'=>0,'contact'=>0,'custom_depart'=>0,'tel'=>0,'qq'=>0,'address'=>0,'custom_prop'=>0,'area_1'=>0,'area_2'=>0,'sub_compny'=>'0','add_user_name'=>'0','add_time'=>'0'],
                      'search_allowed' => ['custom_name'=>2],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'link','action'=>'edit','icorn_name'=>'edit'],
                                     'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete','icorn_name'=>'trash','confirm'=>1]
                                    ],
                      'scope'=>true,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加客户','id'=>'create_custom','type'=>'js','url'=>Url::to(["custom/create"])],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>