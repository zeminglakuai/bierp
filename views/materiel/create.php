<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

?>

<?= app\common\widgets\OrderForm::widget(['form_data'=>[['type'=>'text','label_name'=>'物料名称','name'=>'Materiel[materiel_name]','value'=>$materiel->materiel_name,'tips','id','init_value',],
                                                        ['type'=>'text','label_name'=>'用途','name'=>'Materiel[use_to]','value'=>$materiel->use_to,'tips','id','init_value',],
                                                        ['type'=>'text','label_name'=>'单位','name'=>'Materiel[unit]','value'=>$materiel->unit,'tips','id','init_value',],   
                                                        ['type'=>'text','label_name'=>'单价','name'=>'Materiel[materiel_price]','value'=>$materiel->materiel_price,'tips','id','init_value',],                                                                                                             
                                                        ['type'=>'text','label_name'=>'备注','name'=>'Materiel[remark]','value'=>$materiel->remark,'tips','id','init_value',],
                                                      ]
                                        ]);
?>
<?= app\common\widgets\Submit::widget(['model'=>$materiel,'model_name'=>"materiel",'form_name'=>'order_form']); ?>
 