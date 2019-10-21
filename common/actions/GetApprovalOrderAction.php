<?php
namespace app\common\actions;

use Yii;
use yii\base\Action;
use app\common\config\sys_config;
use app\includes\Message;

class GetApprovalOrderAction extends Action {
 
	public function run() {
      $this->controller->layout = false;
      //得到当前控制器下对应审批流程
      $process_data_arr = $this->controller->get_approval_data($this->controller->id);
      $model = $this->controller->title_model;
      $data_list = $model::find();

      if (@$privi = unserialize(Yii::$app->session['manage_user']['role']->priv_arr)) {
         if (isset($privi[$model_name]['scope']) && $privi[$model_name]['scope'] == 1) { //全部数据
         }elseif (isset($privi[$model_name]['scope']) && $privi[$model_name]['scope'] == 2) { //当前部门数据
             $data_list = $data_list->andwhere(['depart_id'=>Yii::$app->session['manage_user']['depart_id']]);
         }elseif (isset($privi[$model_name]['scope']) && $privi[$model_name]['scope'] == 3) {//只查看自己添加的数据
              $data_list = $data_list->andwhere(['add_user_id'=>Yii::$app->session['manage_user']['id']]);
         }
      } 

      //分别获取各种状态的订单的数量
      $order_status = [];
      foreach ($process_data_arr as $key => $value) {
        $order_status[$key]['process_name'] = $value['process_name'];
        $order_status[$key]['count'] = $data_list->andwhere([$this->controller->status_label=>$key])->count();
      }

      $return_content = $this->controller->render('/default/approval_order', ['order_status'=>$order_status,'nav_name'=>$this->controller->page_title]);
      Message::result_json(1,'',$return_content);
 	}
}