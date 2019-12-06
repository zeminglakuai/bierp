<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\common\config\sys_config;

$this->title = $this->context->config['company_name'];
$this->params['breadcrumbs'][] = '首页';
?>

<?php if($is_edit){?>
<div class="row">
    <DIV class="col-sm-10">
<?php }?>
<div class="sidebar-content">
   <!-- <div class="sidebard-panel">

            <h4>消息 <span class="badge badge-info pull-right">16</span></h4>
            <div class="feed-element">
                <a href="index_3.html#" class="pull-left">
                    <img alt="image" class="img-circle" src="img/a1.jpg">
                </a>
                <div class="media-body">
                    跑步呐，最重要的是要有动力
                    <br>
                    <small class="text-muted">今天 4:21</small>
                </div>
            </div>

     </div>-->

    <div class="wrapper wrapper-content">
        <div class="row">
            <?php
            //如果是第三角色  就处理nav_list
              foreach(sys_config::$nav_list as $kk => $vv){
                if(isset($vv['sub_list'])){
                  foreach($vv['sub_list'] as $kkk => $vvv){

                    if(array_key_exists($kkk,Yii::$app->session['manage_user']['action']) && isset($vvv['is_approval'])){
            ?>
 
                        <div class="col-sm-3 date_show" date-model="<?= $kkk?>" date-name="<?= $vvv['name']?>" date-action="get-approval-order" ></div>
            <?php
                    }
                  }
                }
              }
            ?>
        </div>
    </div>
</div>


<?php if($is_edit){?>
    </DIV>
    <DIV class="col-sm-2">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>部件列表 <small>拖放部件到指定区域</small></h5>
            </div>
            <div class="ibox-content">
                <ol class="dd-list">
                    <li class="dd-item" data-id="4">
                        <button data-action="collapse" type="button">关闭</button><button data-action="expand" type="button" style="display: none;">展开</button>
                        <div class="dd-handle">4 - 列表</div>
                        <ol class="dd-list">
                            <li class="dd-item" data-id="2">
                                <div class="dd-handle">2 - 列表</div>
                            </li>
                            <li class="dd-item" data-id="5">
                                <div class="dd-handle">5 - 列表</div>
                            </li>
                        </ol>
                    </li>
                    <li class="dd-item" data-id="1"><button data-action="collapse" type="button" style="display: block;">关闭</button><button data-action="expand" type="button" style="display: none;">展开</button>
                        <div class="dd-handle">1 - 列表</div>
                        <ol class="dd-list" style="">
                            <li class="dd-item" data-id="3">
                                <div class="dd-handle">3 - 列表</div>
                            </li>
                        </ol>
                    </li>
                </ol>
            </DIV>
        </div>
    </DIV>
</div>
<?php }?>


<div class="oprate_bar">
  <div class="row">
    <div class="col-sm-9">
        <?php if($is_edit){?>
        <button id="save_edit" class="btn btn-warning btn-sm"><i class="fa fa-plus"></i> 保存</button>
        <a id="add_depart" class="btn btn-danger btn-sm" href="<?= Url::to(['/default/user-index'])?>"><i class="fa fa-plus"></i> 退出编辑</a>
        <?php }else{?>
        <a id="add_depart" class="btn btn-warning btn-sm" href="<?= Url::to(['/default/user-index','is_edit'=>1])?>"><i class="fa fa-plus"></i> 编辑首页</a>
        <?php }?>
    </div>
    <div class="col-sm-2"></div>
    <div class="col-sm-1"><button type="button" id="refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i> 刷新</button></div>
  </div>
</div>
 
<script type="text/javascript">
    $(function(){
        $(".date_show").each(  function(i,n){
          var dom = $(this);
          var data_name = $(this).attr('date-name');
          $.get('/'+dom.attr('date-model')+'/'+dom.attr('date-action'),{data_name:data_name},function(result){
            dom.html(result.content)
          },'json')
        });

        $("#refresh").click(function(){
            window.location.reload();
        });

    });
</script>