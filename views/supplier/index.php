<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\common\config\sys_config;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客维商品管理-供货商管理';
$this->params['breadcrumbs'][] = '供货商管理';
?>
<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['supplier/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'supplier_name','label_name'=>'供货商名称'],
                                  ['type'=>'text','label'=>'contact','label_name'=>'联系人'],
                                  ['type'=>'text','label'=>'tel','label_name'=>'电话'],                           
                                  ]
                      ])
?>
 

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\Supplier',
                      'title_arr'=>['id'=>1,'supplier_name'=>0,'contact'=>0,'tel'=>0,'guhua'=>0,'is_daifa'=>'0'],
                      'search_allowed' => ['supplier_name'=>2,'simple_name'=>2,'contact'=>2,'tel'=>2],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'link','action'=>'edit','icorn_name'=>'edit'],
                                     'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete','icorn_name'=>'trash','confirm'=>1]
                                    ],
                      'scope'=>true,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加供货商','id'=>'create_custom','type'=>'js','url'=>Url::to(["supplier/create"])],
                                            'export'=>['label_name'=>'导出','module_name'=>'supplier','type'=>'title'],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
    'other_btn'=>[
        ['label_name'=>'待审核商品','type'=>'link','icon'=>'plus','url'=>Url::to(["/supplier/admit-list"])],]
                                            ])
?> 
<script>
  $(".supplier_edit").hover(
    function(){
      $(this).addClass('on_mouseover');
    },
    function(){
      $(this).removeClass('on_mouseover');
    }
  );

  $(".supplier_edit").click(
    function(){
      var data_id = $(this).attr("data-id");
	    var data_type = $(this).attr("data-type");
      var target = $(this);
	  
      if($(this).children("input").length > 0){
      }else{
        var value = $(this).text();
		    var string_leng = value.length+5;
        var input_html = '<input type="text" value="'+value+'" size='+string_leng+' class="stor_code_input form-control" style="width:80%;" />'; 
        $(this).html(input_html);
        $('.stor_code_input').focus();
        $('.stor_code_input').select();
      }
	  
	    function response_result(){
  		  var new_value = $(this).val();
  		  $.get('<?= Url::to(["/supplier/update-supplier-info"])?>',{value: new_value,type: data_type,id: data_id},function(result){
  			  if(result.error == 1){
  				  target.html(result.content);
  			  }else{
  				  layer.msg(result.message);
            target.html(value);
  			  }
  		  },'json');
	    }
	  
  	  $('.stor_code_input').keydown(function(event){
  		  if(event.keyCode == '13'){
  			  response_result();
  		  }
  	  });
  	  
      $('.stor_code_input').blur(response_result);
    }
  );

$("#add_supplier").click(function(){
  //页面层
  layer.open({
    type: 2,
    title:'添加部门',
    //skin: 'layui-layer-rim', //加上边框
    area: ['80%', '80%'], //宽高
    maxmin: true,
    content: '<?= Url::to(["/supplier/create"])?>'
  });
});
</script>
