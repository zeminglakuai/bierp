<?php
namespace app\common\actions;

use Yii;
use yii\base\Action;

class SearchCustomOrderAction extends Action {
    public function run() {
    	$q = Yii::$app->request->get('q');
    	$query = new \yii\db\Query();
    	$query->select('id,order_name,order_sn as name')
				->from('custom_order')
				->where(['like','order_name',$q])
				->orwhere(['like','order_sn',$q]);

      if (@$privi = unserialize(Yii::$app->session['manage_user']['role']->priv_arr)) {
         if (isset($privi[$model_name]['scope']) && $privi[$model_name]['scope'] == 1) { //全部数据
         }elseif (isset($privi[$model_name]['scope']) && $privi[$model_name]['scope'] == 2) { //当前部门数据
             $data_list = $data_list->andwhere(['depart_id'=>Yii::$app->session['manage_user']['depart_id']]);
         }elseif (isset($privi[$model_name]['scope']) && $privi[$model_name]['scope'] == 3) {//只查看自己添加的数据
              $data_list = $data_list->andwhere(['add_user_id'=>Yii::$app->session['manage_user']['id']]);
         }
      }
		  $supp_list = $query->all();
		  die(json_encode($supp_list));
	}
}