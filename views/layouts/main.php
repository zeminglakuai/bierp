<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\common\config\sys_config;
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html>
<head>
<?php $this->head();?>
<meta charset="utf-8">
<meta http-equiv="content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp" />
<title>
<?= $this->title;?>
</title>
<!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
<![endif]-->

<link rel="shortcut icon" href="favicon.ico">
<?= Html::cssFile('@web/css/bootstrap.min.css') ?>
<?= Html::cssFile('@web/css/font-awesome.min.css') ?>
<?= Html::cssFile('@web/css/animate.min.css') ?>
<?= Html::cssFile('@web/css/style.min.css') ?>
</head>
<!--  oncontextmenu="window.event.returnValue=false" -->
<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
<?php
  //print_r(Yii::$app->session['manage_user']['action']);
?>
<div id="wrapper"> 
  <!--左侧导航开始-->
  <nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
      <ul class="nav" id="side-menu">
        <li class="nav-header">
          <div style="color:#fff;font-weight:800px;font-size:14px;text-align:center;" class="profile-element"><?= $this->context->config['company_name']?></div>
          <div class="logo-element" style="color:#fff;font-weight:700px;font-size:20px;">BI</div>
        </li>
        <?php
            $nav_list = [];
            if(Yii::$app->session['manage_user']['type'] == '1'){
              $nav_list = sys_config::$nav_list;
            }else if(Yii::$app->session['manage_user']['type'] == '2'){
              $nav_list = Yii::$app->session['manage_user']['nav_list'];
            }else{
              //如果是第三角色  就处理nav_list
              foreach(sys_config::$nav_list as $kk => $vv){
                if(isset($vv['sub_list'])){
                  foreach($vv['sub_list'] as $kkk => $vvv){
                    if(array_key_exists($kkk,Yii::$app->session['manage_user']['action'])){
 
                      $nav_list[$kk]['sub_list'][$kkk] = $vvv;
                    }
                  }
                }else{
                  if(array_key_exists($kk,Yii::$app->session['manage_user']['action'])){
                      $nav_list[$kk] = $vv;
                  }
                }
              }
            }
            foreach($nav_list as $kk => $vv){
      			  if(isset($vv['sub_list'])){
      					?>
      				<li><a href="#"><i class="fa fa-<?=sys_config::$nav_list[$kk]['label']?>"></i> <span class="nav-label">
      				  <?=sys_config::$nav_list[$kk]['name']?>
      				  </span> <span class="fa arrow"></span> </a>
      				  <ul class="nav nav-second-level  collapse">
      					<?php foreach($vv['sub_list'] as $kkk => $vvv){?>
      					<li><a class="J_menuItem" href="<?= Url::to(['/'.$kkk])?>" data-index="0">
      					  <?= $vvv['name']?>
      					  </a> </li>
      					<?php }?>
      				  </ul>
      				</li>
      				<?php
      					}else{?>
      				<li <?php  if($this->context->id == $kk) echo 'class="active"';?>> <a href="<?= Url::to(['/'.$kk])?>"> <i class="glyphicon glyphicon-<?=sys_config::$nav_list[$kk]['label']?>"></i> <span class="menu-text">
      				  <?=sys_config::$nav_list[$kk]['name']?>
      				  </span> </a> </li>
      				<?php   
      					}
      			}
      			?>
        </li>
      </ul>
    </div>
  </nav>
  <!--左侧导航结束--> 
  <!--右侧部分开始-->
  <div id="page-wrapper" class="gray-bg dashbard-1">

    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
      <div class="navbar-header">
          <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
      </div>
      <ul class="nav navbar-top-links navbar-right">
        <li class="dropdown"> <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#"> <i class="fa fa-user"></i>
          <?= Yii::$app->session['manage_user']['admin_name']?>
          </a>
          <ul class="dropdown-menu ">
            <li> <a href="javascript:void();" id="change_password"><i class="fa fa-lock"></i> 修改密码</a> </li>
            <li> <a href="javascript:void();" id="user_profile"><i class="fa fa-user"></i> 用户资料</a> </li>
            <li> <a href="javascript:void();" id="user_auth"><i class="fa fa-lock"></i> 授权</a> </li>
            <li class="divider"></li>
            <li> <a href="<?= Url::to(['/default/log-out'])?>"><i class="fa fa-sign-out"></i> 安全退出</a> </li>
          </ul>
        </li>
        <li class="hidden-xs">
            <a href="/teamwork" class=" " data-index="0" target="_blank"><i class="fa fa-cart-arrow-down"></i> BUG反馈</a>
        </li>
        <li class="dropdown"> 
          <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#"> 
            <i class="fa fa-bell"></i> <!--<span class="label label-primary">8</span>-->
          </a>
          <ul class="dropdown-menu dropdown-alerts">
            <li> <a href="mailbox.html">
              <div> <i class="fa fa-envelope fa-fw"></i> 您有16条未读消息 <span class="pull-right text-muted small">4分钟前</span> </div>
              </a> </li>
            <li class="divider"></li>
            <li>
              <div class="text-center link-block"> <a class="J_menuItem" href="notifications.html"> <strong>查看所有 </strong> <i class="fa fa-angle-right"></i> </a> </div>
            </li>
          </ul>
        </li>
      </ul>
    </nav>

    <div class="row content-tabs">
      <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-backward"></i> </button>
      <nav class="page-tabs J_menuTabs">
        <div class="page-tabs-content"> <a href="javascript:;" class="active J_menuTab" data-id="index_v1.html">首页</a> </div>
      </nav>
      <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i> </button>
      <div class="btn-group roll-nav roll-right">
        <button class="dropdown J_tabClose" data-toggle="dropdown">关闭操作<span class="caret"></span> </button>
        <ul role="menu" class="dropdown-menu dropdown-menu-right">
          <li class="J_tabShowActive"><a>定位当前选项卡</a> </li>
          <li class="divider"></li>
          <li class="J_tabCloseAll"><a>关闭全部选项卡</a> </li>
          <li class="J_tabCloseOther"><a>关闭其他选项卡</a> </li>
        </ul>
      </div>
    </div>
    <div class="row J_mainContent" id="content-main">
      <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="<?= Url::to('/default/user-index')?>" frameborder="0" data-id="index_v1.html" seamless></iframe>
    </div>
    <!--右侧部分结束--> 
    
  </div>
</div>
<?= Html::jsFile('@web/js/jquery.min.js') ?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>
<?= Html::jsFile('@web/js/plugins/metisMenu/jquery.metisMenu.js') ?>
<?= Html::jsFile('@web/js/plugins/slimscroll/jquery.slimscroll.min.js') ?>
<?= Html::jsFile('@web/js/plugins/layer/layer.min.js') ?>
<?= Html::jsFile('@web/js/hplus.min.js') ?>
<?= Html::jsFile('@web/js/contabs.min.js') ?>
<?= Html::jsFile('@web/js/plugins/pace/pace.min.js') ?>


<script type="text/javascript">

  $("#change_password").click(function(){
    //页面层
    layer.open({
      type: 2,
      title:'修改密码',
      //skin: 'layui-layer-rim', //加上边框
      area: ['50%', '50%'], //宽高
      maxmin: true,
      content: '<?= Url::to(["/default/change-pass"])?>'
    });
  });

  $("#user_auth").click(function(){
    //页面层
    layer.open({
      type: 2,
      title:'用户授权',
      //skin: 'layui-layer-rim', //加上边框
      area: ['60%', '80%'], //宽高
      maxmin: true,
      content: '<?= Url::to(["/default/user-auth"])?>'
    });
  });

  $("#user_profile").click(function(){
    //页面层
    layer.open({
      type: 2,
      title:'用户资料',
      //skin: 'layui-layer-rim', //加上边框
      area: ['50%', '50%'], //宽高
      maxmin: true,
      content: '<?= Url::to(["/default/user-profile"])?>'
    });
  });

</script>
</body>
</html>
<?php $this->endPage() ?>
