<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\common\models\ApprovalProcess;

$this->params['breadcrumbs'][] = $this->context->page_title;
?>
<?= Html::cssFile('@web/css/token_input/token-input.css') ?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<?= app\common\widgets\PageSearch::widget([
                      'url'=>Url::to(['ask-price/index']),
                      'condition'=>[
                                  ['type'=>'text','label'=>'order_sn','label_name'=>'单号'],
                                  ['type'=>'text','label'=>'supplier_name','label_name'=>'供货商名称'],
                                  ['type'=>'token_input','table_name'=>'custom_order','name_name'=>'order_name','label'=>'custom_order_id','label_name'=>'客户方案','token_url'=>Url::to(['ask-price/token-custom-order'])],
                                  ['type'=>'select','label'=>'ask_price_order_status','label_name'=>'审核状态','value_arr'=>ApprovalProcess::getApprovalValue($this->context->id)],
                                  ]
                      ])
?>

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\AskPriceOrder',
                      'model_name'=>'ask-price',
                      'title_arr'=>['id'=>1,'order_name'=>0, 'order_sn'=>0,'customOrder&order_sn'=>0,'customOrder&order_name'=>0,'supplier_name'=>0,'add_user_name'=>0,'add_time'=>0,'ask_price_order_status'=>0,'goodsNumber'=>0],
                      'search_allowed' => ['order_sn'=>2,'supplier_id'=>1,'supplier_name'=>2,'custom_order_id'=>1],
                      'opration' => ['edit'=>['lable_name'=>'编辑','type'=>'link','action'=>'view','icorn_name'=>'edit','confirm'=>0],
                                      'check_url'=>['lable_name'=>'编辑链接','type'=>'js','action'=>'check_url','icorn_name'=>'edit','confirm'=>0,'tips'=>'给供货商查看的地址和编辑密码'],
                                      'delete'=>['lable_name'=>'删除','type'=>'js','action'=>'delete','icorn_name'=>'trash','confirm'=>1],
                                    ],
                      'scope'=>true,
                      ])
?>

<?= app\common\widgets\OperateBar::widget([
                                            'create'=>['label_name'=>'添加'.$this->context->page_title,'id'=>'create_ask_price_order','type'=>'js','url'=>Url::to(["ask-price/create"])],
                                            'refresh'=>['label_name'=>'刷新','type'=>'js','id'=>'add_custom','icon'=>'plus'],
                                            ])
?>

<script type="text/javascript">
$(".check_url_ask-price").click(function(){
  var check_url = create_url('<?= Url::to(["/ask-price/view-check-url"])?>');
  var data_id = $(this).attr('data-id');
  //页面层
  layer.open({
    type: 2,
    title:'供货商查看的地址和编辑密码',
    //skin: 'layui-layer-rim', //加上边框
    area: ['90%', '50%'], //宽高
    maxmin: true,
    content: check_url+'id='+data_id
  });
});
</script>
