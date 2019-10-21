<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\modules\admin\config\config;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = '参数设置';
$this->params['breadcrumbs'][] = $this->title;
?>

<form action="<?= Url::to(['/sys-config/update'])?>" method="post" class="form-horizontal">
  <div class="ibox">
    <div class="ibox-content" style="padding-bottom:10px;">
      <div class="tabs-container">
        <ul class="nav nav-tabs" id="myTab">
          <?php
            $i = 1;
            foreach($config_title as $kk => $vv){
          ?>
          <li <?php if($i == 1){echo 'class="active"';} ?> > <a data-toggle="tab" href="#<?= $vv['name']?>">
            <?= $vv['desc']?>
            </a> </li>
          <?php
            $i++;
          }?>
        </ul>
        <div class="tab-content">

          <?php 
      	$i = 1;
      	foreach($config_content as $kk => $vv){
      ?>
          <div id="<?= $kk ?>" class="tab-pane <?php if($i == 1) echo 'active' ?>">
            <div class="panel-body">
				<?php foreach($vv as $kkk => $vvv){?>
                <div class="form-group">
                  <label class="col-sm-1 control-label">
                    <?= $vvv['desc']?>
                  </label>
                  <div class="col-sm-7">
                    <input type="text" name="value[<?= $vvv['id']?>]"  value="<?= $vvv['value']?>" class="form-control"/>
                  </div>
                  <div class="col-sm-4 gray control-label" style="text-align:left;">
                    <?= $vvv['tips']?>
                  </div>              
                </div>
                <?php }?>
            </div>
          </div>
          <?php
      $i++;
    	}
      ?>
      
        </div>
      </div>
    </div>
  </div>
  <div class=" " style="padding:10px 50px;">
    <button class="btn btn-danger"><i class="icon-save"></i>保存</button>
  </div>
</form>
