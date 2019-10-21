<?php
namespace app\common\actions;

use Yii;
use yii\base\Action;
use app\common\models\Purchase;

class SearchPurchaseAction extends Action {
    public function run() {
    	$purchase_list = [];
    	$q = Yii::$app->request->get('q');

    	$purchases = Purchase::find()->all();
        if($q){
            $purchases = $purchases->andwhere(['like','order_sn',$q]);
            if ($purchases) {
                foreach ($purchases as $key => $value) {
                    $purchase_list[] = ['id'=>$value->id,'name'=>$value->order_sn,'supplier_id'=>$value->supplier_id,'supplier_name'=>$value->supplier_name,'total'=>$value->total];
                }
            }
            die(json_encode($purchase_list));
        }else{
            return $this->controller->render('/goods/search-goods', [
                'purchase_list' => $purchase_list,


            ]);
        }



	}
}