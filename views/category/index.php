<?php
use yii\helpers\Html;
use yii\helpers\Url;

use app\modules\admin\config\config;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客维商品管理-商品分类';
$this->params['breadcrumbs'][] = '商品分类';
?>
<div class="ibox" style="margin-bottom:5em;">
  <div class="ibox-content" style="padding-bottom:10px;">
<table class="table dataTable">
  <tr>
    <th width="5%" align="center">ID</th>
    <th width="30%" align="center">分类名称</th>
    <th width="10%" align="center">排序</th>
	  <th  align="center">操作</th>
  </tr>
  <?php foreach($cat_list as $kk => $vv){?>
  <tr>
    <td><?= $vv['id']?>
    </td>
    <td>
      <div class="text-div-<?= $vv['level']?>">
        <span class="text-indent-<?= $vv['level']?>">
          <span class="lable_edit name_edit" data-id="<?= $vv['id']?>" date-lable="cat_name"> <?= str_repeat('&nbsp;', $var['level'] * 4).$vv['cat_name'] ?> </span>
          <span class="glyphicon glyphicon-cog" style="display: none;"> </span>
        </span>
      </div>
    </td>
    <td>
          <span class="lable_edit sort_edit" data-id="<?= $vv['id']?>" date-lable="sort_order"> <?= $vv['sort_order'] ?> </span>
    </td>    
    <td>
      <A href="javascript:void();" data-id="<?= $vv['id']?>" class="btn btn-xs btn-primary edit_cate"><span class="glyphicon glyphicon-edit"></span> 编辑</A>
      <A href="<?= Url::to(['/category/delete-cate','id'=>$vv['id']])?>" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span> 删除</A>
    </td>
  </tr>
  <?php }?>
</table>
 

  </div>
</div>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加分类','id'=>'create_custom','type'=>'js','url'=>Url::to(["category/create"])],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            'backup'=>['label_name'=>'返回','url'=>Url::to(['category/']),],
                                            ])
?>



<script>

  $(".lable_edit").hover(
    function(){
      $(this).addClass('lable_edit_over');
    },function(){
      $(this).removeClass('lable_edit_over');
    }
  );

  $(".name_edit").click(
    function(){
      var data_id = $(this).attr("data-id");
      var target = $(this);
	  
      if($(this).children("input").length > 0){
      }else{
        var ori_value = $(this).text();
		    var string_leng = ori_value.length+5;
        var input_html = '<input type="text" value="'+ori_value+'" size='+string_leng+' class="edit_input"/>'; 
        $(this).html(input_html);
        $('.edit_input').focus();
        $('.edit_input').select();
      }
	  
  	  function response_result(){
  		  var value = $(this).val();
  		  $.get('/category/update-catename',{cate_name: value,id: data_id},function(result){
  			  if(result.error == 1){
  				target.html(result.content);
  			  }else{
            target.html(ori_value);
            layer.msg(result.message,function(){});
  			  }
  		  },'json');
  	  }
  	  
  	  $('.edit_input').keydown(function(event){
  		  if(event.keyCode == '13'){
  			  response_result();
  		  }
  	  });
	  
      $('.edit_input').blur(response_result);
    }
  );

  $(".sort_edit").click(
    function(){
      var data_id = $(this).attr("data-id");
      var target = $(this);
    
      if($(this).children("input").length > 0){
      }else{
        var ori_value = $(this).text();
        var string_leng = ori_value.length+5;
        var input_html = '<input type="text" value="'+ori_value+'" size='+string_leng+' class="edit_input"/>'; 
        $(this).html(input_html);
        $('.edit_input').focus();
        $('.edit_input').select();
      }
    
      function response_result(){
        var value = $(this).val();
        $.get('/category/update-sort',{sort_order: value,id: data_id},function(result){
          if(result.error == 1){
          target.html(result.content);
          }else{
            target.html(ori_value);
            layer.msg(result.message,function(){});
          }
        },'json');
      }
      
      $('.edit_input').keydown(function(event){
        if(event.keyCode == '13'){
          response_result();
        }
      });
    
      $('.edit_input').blur(response_result);
    }
  );
 
$("#add_cate").click(function(){
  //页面层
  layer.open({
    type: 2,
    title:'添加分类',
    area: ['80%', '80%'], //宽高
    maxmin: true,
    content: '<?= Url::to(["/category/create"])?>'
  });
});

$(".edit_cate").click(function(){
  var data_id = $(this).attr('data-id');
  var to_url = create_url('<?= Url::to(["/category/edit"])?>');
  //页面层
  layer.open({
    type: 2,
    title:'编辑分类',
    area: ['90%', '80%'], //宽高
    content: to_url+'id='+data_id
  });
});
</script>