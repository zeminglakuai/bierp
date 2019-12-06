<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\common\config\lang_config;
use app\common\config\lang_value_config;

$this->title = '客维商品管理-客户方案';
$this->params['breadcrumbs'][] = ['label'=>'任务列表','url'=>['index']];
$this->params['breadcrumbs'][] = $mession->mession_name;
?> 

<?= app\common\widgets\OrderTitle::widget([
                      'model'=>'app\common\models\Mession',
                      'id'=>$id,
                      'model_name'=>'mession',
                      'label_arr' => ['mession_depart_name'=>'',
                                      'year'=>'',
                                      'remark'=>''
                                      ],
                      'status_label' => 'mession_status',
                      ])
?>

<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
    <table class="table">
      <thead>
        <tr>
          <th>月份</th>
          <th>备注</th>
          <th>计划金额</th>
          <th>年度占比</th>
          <th>已完成金额</th>
          <th>未付款金额</th>
          <th>完成比例</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
    <?php
      foreach ($mession->mession_data as $key => $value) {
    ?>
        <tr>
          <td><?= $value['name']?><input type="hidden" name="" /></td>
          <td><?= $value['desc']?></td>
          <td>计划金额</td>
          <td>年度占比</td>
          <td>已完成金额</td>
          <td>未付款金额</td>
          <td>完成比例</td>
          <td><a href="javascript:void();">查看详情</a></td>         
        </tr>
    <?php
      }
    ?>
      </tbody>
    </table>    
  </div>
</div>


<?= app\common\widgets\OperateBar::widget([
                                            'admit'=>['label_name'=>'复核','id'=>'admit_mession','type'=>'js','url'=>Url::to(["mession/admit"])],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>