<?php
use yii\helpers\Url;

?>

 

<?= app\common\widgets\DataList::widget([
                      'model'=>'app\common\models\CustomOrderGoods',
                      'model_name'=>$this->context->id,
                      'init_condition'=>[['goods_id'=>$goods_id]],
                      'title_arr'=>['id'=>1,'customOrder&order_name'=>0,'goods_name'=>0,'sale_price'=>0,'supplier_price'=>0,'supplier_name'=>0,'customOrder&add_user_name'=>0,'customOrder&depart_name'=>0,'customOrder&add_time'=>0,'customOrder&status_name'=>0,],
                      ])
?>

