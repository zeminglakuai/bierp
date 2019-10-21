<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<title>登录</title>
</head>
<body class="gray-bg">

<div class="row animated fadeInDown" style="margin-top:10em;">
  <div class="col-sm-4"></div>
  <div class="col-sm-4" style="text-align:center;">
    <p style="font-size:5em;"></p>
  </div>
  <div class="col-sm-4"></div>
</div>

<div class="row">
  <div class="col-sm-4"></div>
  <div class="col-sm-4" style="padding:0 5em;">
    <div class="panel panel-default animated fadeInDown">
      <div class="panel-heading">
          欢迎使用 <?= $this->context->config['company_name']?>
      </div>
      <div class="panel-body">
 
          <div>
            <form class="m-t" role="form" method="post" action="<?= Url::to(['/default/login'])?>">

              <div class="form-group">
                <div class="input-group m-b">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" name="user_name" class="form-control" placeholder="用户名" required>
                </div>
              </div>
              <div class="form-group">
                <div class="input-group m-b">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password"  name="password" class="form-control" placeholder="密码" required>
                </div>
              </div>
              <button type="submit" id="login" class="btn btn-primary block full-width m-b">登 录</button>
            </form>
          </div>
 
      </div>
    </div>
  </div>
  <div class="col-sm-4"></div>
</div>

<?= Html::jsFile('@web/js/jquery.min.js') ?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>

<script>
$("#login").click(function(){
  $("#login_form").submit();
});
</script>
</body>
</html>