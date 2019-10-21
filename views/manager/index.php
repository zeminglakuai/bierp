<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use app\common\models\Depart;
use app\common\config\sys_config;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = '管理员列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::cssFile('@web/css/plugins/switchery/switchery.css') ?>
<div class="ibox">
  <div class="ibox-content">
    <form action="<?= Url::to(['/manager/index'])?>" method="get">
     <div class="row">
        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">用户名</label>
            <div class="col-sm-9">
              <input type="text" name="admin_name"  class="form-control" value="<?= $admin_name?>"/>
            </div>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">手机号</label>
            <div class="col-sm-9">
              <input type="text" name="tel"  class="form-control" value="<?= $admin_name?>"/>
            </div>
          </div>
        </div>        
        <div class="col-sm-3">
          <div class="form-group">
            <label class="col-sm-3 control-label">部门 </label>
            <div class="col-sm-9">
              <?= Depart::get_depart_select('depart_id',$depart_id) ?>
            </div>
          </div>
        </div>        
        <div class="col-sm-3">
          <input type="submit" class="btn btn-danger btn-sm tooltip-error" value="搜索"/><input type="hidden" name="r" value="admin/lock/index"/>
        </div>        
    </div>
    </form>
  </div>
</div>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\Admin',
                      'model_name'=>'manager',
                      'title_arr'=>['id'=>1,'admin_name'=>0,'real_name'=>0,'tel'=>0,'depart&depart_name'=>0, 'role&role_name'=>0,'add_time'=>0,'last_login_time'=>0,'last_ip'=>0],
                      'search_allowed' => ['admin_name'=>2,'real_name'=>2,'tel'=>2],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'js','action'=>'view','icorn_name'=>'edit','confirm'=>0],
                                      'view_log'=>['lable_name'=>'操作日志','type'=>'link','action'=>'admin-log','icorn_name'=>'list','confirm'=>0,'url'=>Url::to(['/manager/admin-log'])],
                                    ],
                      'scope'=>true,
                      ])
?>
<?= app\common\widgets\OperateBar::widget([
      'create'=>['label_name'=>'添加用户','id'=>'create_admin_user','type'=>'js','url'=>Url::to(["/manager/create"])],

      'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],

      ])
?>

<script>
 
$("#add_admin").click(function(){
  //页面层
  layer.open({
    type: 2,
    title:'添加用户',
    //skin: 'layui-layer-rim', //加上边框
    area: ['50%', '70%'], //宽高
    maxmin: true,
    content: '<?= Url::to(["/manager/create"])?>'
  });
});

</script>
<script>


$(".user_info_span").hover(
	function(){
		$(this).siblings(".glyphicon").show();
	},
	function(){
		$(this).siblings(".glyphicon").hide();
	}
);

$(".user_info_span").click(
	function(){
		var data_id = $(this).attr("data-id");
		var type = $(this).attr("data-type");
		var target = $(this);
		if($(this).children("input").length > 0){
		}else{
			var ima_value = $(this).text();
			var input_html = '<input type="text" value="'+ima_value+'" size="15" class="user_ionfo_input" id="user_info_'+data_id+'" />'; 
			$(this).html(input_html);
			$('.user_ionfo_input').focus();
			$('.user_ionfo_input').select();
		}
		$('#user_info_'+data_id).blur(function(){
			var value = $(this).val();
			$.post('/manager/update-admin',{value: value, id: data_id,type:type},function(result){
				if(result.error == 1){
					target.html(result.content);
					target.tooltip('toggle');
				}else{
					layer.msg(result.message)
				}
			},'json');
		});
	}
);

$(".update_password").click(function(){
  var id = $(this).attr('data-id');
  //页面层
  layer.open({
    type: 2,
    title:'编辑用户',
    //skin: 'layui-layer-rim', //加上边框
    area: ['50%', '70%'], //宽高
    maxmin: true,
    content: '<?= Url::to(["/manager/view"])?>?id='+id
  });
});

$(".user_is_active").change(function(){
	console.log('ssssssssssss');
});

</script>

<?= Html::jsFile('@web/js/plugins/switchery/switchery.js') ?>
<script type="text/javascript">
  var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
  elems.forEach(function(html) {
    var switchery = new Switchery(html,{ color: 'rgb(26, 179, 148)'});
  });
  $(".js-switch").change(function(){
    var value = $(this).prop('checked')?1:0;
    var id = $(this).attr('data-id');
    var type = $(this).attr('data-type');
    $.post('/manager/update-admin',{value: value, id: id,type:type},function(result){
            if(result.error == 1){
              target.html(result.content);
              target.tooltip('toggle');
            }else{
              layer.msg(result.message)
            }
          },'json');
    })
</script>
