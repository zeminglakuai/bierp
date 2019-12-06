<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\common\config\lang_config;
use app\common\config\lang_value_config;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->params['breadcrumbs'][] = ['label'=>'销售合同列表','url'=>['index']];
$this->params['breadcrumbs'][] = $contract->order_sn;
?>

<?= app\common\widgets\OrderTitle::widget([
                      'model'=>'app\common\models\Contract',
                      'id'=>$id,
                      'model_name'=>'sell-contract',
                      'label_arr' => ['contract_name'=>'',
                                      'custom_name'=>'',
                                      'sellOrder&order_sn'=>['link'=>true,'id'=>'sellOrder','url'=>'/sell-order/view','id'=>'id'],
                                      'template_name'=>'',
                                      'keeper_user_name'=>'',
                                      'keep_time'=>'',
                                      'remark'=>''
                                      ],
                      'status_label' => 'contract_status',
                      ])
?>

<div class="ibox ">
  <div class="ibox-title">合同打印件</div>
  <div class="ibox-content" style="padding-bottom:10px;">
    <div class="row">
      <div class="col-sm-12">
        <form action="" id="data_form">
          <?= app\common\widgets\FileList::widget(['model'=>'sell-contract','file_list'=>$contract->contractFile]); ?>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="row" style="padding:10px 0px;">
        <div class="form-group">
            <label class="col-sm-2 control-label"></label>
            <div class="col-sm-10">
              <input type="hidden" name="id" value="<?= $contract->id?>" >
              <button class="btn btn-danger" id="contract_file"><i class="icon-save"></i>提交</button>
            </div>
        </div>
      </div>
    </div></div>
</div>
<script type="text/javascript">
  $("#contract_file").click(function(){
   var formData = new FormData($("#data_form")[0]);
   var update_url = create_url('<?= Url::to(["/sell-contract/contract-file"]) ?>');
     $.ajax({  
          url: update_url+'id='+<?= $contract->id?> ,  
          type: 'POST',  
          data: formData,
          dataType:'json',
          async: false,  
          cache: false,  
          contentType: false,
          processData: false,
          success: function (result) {
            if(result.error == 1){
              window.location.reload();
            }else{
              layer.msg(result.message);
            } 
          },  
          error: function (result) {  
              layer.msg('发生错误');
          }
     });
});
</script>
 

<block class="ibox" style="margin-bottom:5em;">
	<div class="ibox-title">合同内容
    <div class="ibox-tools">
    </div>
  </div>
  <div class="ibox-content contract_content" style="background-color: #fff;" id="print_contract">
  	<?= $contract->content?>
  </div>
</block>
<script type="text/javascript">
 
  
</script>
<?= app\common\widgets\OperateBar::widget([
      'admit_process'=>['controller'=>$this->context->id,'url'=>Url::to(['/'.$this->context->id.'/admit','id'=>$contract->id]),'model'=>$contract,'status_label'=>'contract_status'],
      'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
      'other_btn'=>[
          ['label_name'=>'打印合同','type'=>'js','id'=>'print_btn','icon'=>'print'],
          ['label_name'=>'编辑合同','type'=>'js','id'=>'edit_contract','icon'=>'edit'],
          ['label_name'=>'回收合同','type'=>'js','id'=>'keep_contract','icon'=>'reply'],
        ],
      ])
?>

<script type="text/javascript">

  $(".contract_editable").hover(
    function(){
      $(this).addClass('lable_edit_over');
    },function(){
      $(this).removeClass('lable_edit_over');
    }
  );

  $(".contract_editable").click(
    function(){
      var id = '<?= Yii::$app->request->get("id")?>';
      var data_name = $(this).attr("data-name");
      var target = $(this);
      if($(this).children("input").length > 0){
      }else{
        var ima_code = $(this).text();
        var input_html = '<input type="text" value="'+ima_code+'" class="edit_input" />'; 
        $(this).html(input_html);
        $('.edit_input').focus();
        $('.edit_input').select();
      }
      
      $('.edit_input').blur(function(){
        var value = $(this).val();
        if(value == ima_code){
            target.html(ima_code);
            return false;
          }
        $.get('/sell-contract/update-label',{value: value, id:id, data_name: data_name},function(result){
          if(result.error == 1){
            target.html(result.content);
          }else{
            target.html(ima_code);
            layer.msg(result.message,function(){});
          }
        },'json');
      });
      $('.edit_input').keydown(function(event){
        if(event.keyCode == 13) {
          event.stopPropagation();
          event.preventDefault();
          var value = $(this).val();
          if(value == ima_code){
            target.html(ima_code);
            return false;
          }
        $.get('/sell-contract/update-label',{value: value, id:id, data_name: data_name},function(result){
          if(result.error == 1){
            target.html(result.content);
          }else{
            target.html(ima_code);
            layer.msg(result.message,function(){});
          }
        },'json');
        }
      });
   
    }
  );

  $("#print_btn").click(function(){
    $("#print_contract").addClass('print_page');
    $("div").hide();
    $("#print_contract").show();
    window.print();
    $("div").show();
    $("#print_contract").removeClass('print_page');
  });

  $("#edit_contract").click(function(){
    //页面层
    layer.open({
      type: 2,
      title:'编辑合同',
      //skin: 'layui-layer-rim', //加上边框
      area: ['90%', '80%'], //宽高
      content: "<?= Url::to(['sell-contract/edit-contract','id'=>$contract->id])?>"
    });
  });

  $("#keep_contract").click(function(){
    //页面层
    layer.open({
      type: 2,
      title:'回收合同',
      //skin: 'layui-layer-rim', //加上边框
      area: ['90%', '80%'], //宽高
      content: "<?= Url::to(['sell-contract/keep-contract','id'=>$contract->id])?>"
    });
  });  



</script>