<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\common\config\sys_config;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = $admin_user->admin_name.'权限编辑';
$this->params['breadcrumbs'][] = ['label' => '管理员列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::cssFile('@web/css/plugins/iCheck/custom.css') ?>
<?= Html::jsFile('@web/js/plugins/iCheck/icheck.min.js') ?>
<?php foreach(sys_config::$nav_list as $kk => $vv){	}?>
<form action="<?= Url::to(['/depart/act-privilege']);?>" name="" method="post">
  <table  class="table table-striped">
  	<tbody>
    <?php
    print_r(sys_config::$privilege_desc)    ;exit();
    foreach(sys_config::$nav_list as $kk => $vv){	?>
    <tr>
      <th width="10%"><?= $vv['name']?></th>
      <th></th>
    </tr>
    <?php
			foreach($vv['sub_list'] as $kkk  => $vvv){
	   ?>
    <tr>
      <th>
        <div class="checkbox i-checks sub-module" module-data="<?= $kkk?>">
            <label>
                <input class="sub-module" type="checkbox" module-data="<?= $kkk?>" />
                 <i></i>
                <?= sys_config::$nav_list[$kk]['sub_list'][$kkk]['name']?>
            </label>
        </div>
        </th>
      <td><div class="row">
          <?php foreach(sys_config::$privilege_desc[$kkk] as $kkkk => $vvvv){
					?>
          <?php if($kkkk == 'scope'){
          ?>
          <div class="col-xs-4" style="padding:0px;">
            <div class="form-group">
              <label class="col-sm-2" style="padding:0px;">权限范围</label>
              <?php foreach (sys_config::$scope as $key => $value) {

              ?>
                <div class="col-sm-2" style="padding:0px;">
                  <div class="radio i-checks">
                      <label><input type="radio" value="<?= $key?>" name="<?= $kkk.'['.$kkkk.']'?>" <?= $role->priv_arr[$kkk]['scope'] == $key?'checked="checked"':'' ?>> <i></i><?= $value?></label>
                  </div>
                </div>
              <?php
              }?>
            </div>
          </div>
          <?php
          }else{?>
          <div class="col-xs-1" style="padding:0px;">
            <div class="checkbox i-checks">
                <label>
                    <input name="<?= $kkk.'['.$kkkk.']'?>" class="ace ace-checkbox-2 <?= $kkk?> <?= $kk?>" type="checkbox" <?php if(isset($role->priv_arr[$kkk]) && in_array($kkkk,$role->priv_arr[$kkk]))echo 'checked = "checked"'?> />
                     <i></i>
                     <?= $vvvv['name']?>
                </label>
            </div>
          </div>
          <?php
          }
          ?>

          <?php
					}
				  ?>
        </div></td>
    </tr>
    <?php
			}
	  }
    ?>
    </tbody>
  </table>
  <div class=" " style="padding:10px 50px;">
    <input type="hidden" name="r" value="admin/lock/act-privilege" />
    <input type="hidden" name="id" value="<?=$role->id?>" />
    <button class="btn btn-danger"><i class="icon-save"></i>保存</button>
  </div>
</form>
<script>
    $(".sub-module").on('ifChecked',function(event){
      //得到其下checkbox状态
      var module_data = $(this).attr('module-data');
      $("."+module_data+"").iCheck('check');
    });

    $(".sub-module").on('ifUnchecked',function(event){
      //得到其下checkbox状态
      var module_data = $(this).attr('module-data');
      $("."+module_data+"").iCheck('uncheck');
    });

    $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});

</script>
