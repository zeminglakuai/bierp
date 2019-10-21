<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\helpers\Url;
use app\common\config\sys_config;
use app\common\models\Config;
use app\common\models\AdminLog;
use app\common\models\ApprovalLog;
use app\common\models\ApprovalProcess;
use app\common\models\UploadForm;
use app\includes\message;
use app\includes\Common_fun;
use yii\web\UploadedFile;


class BaseController extends Controller
{
    public $enableCsrfValidation = false;
    public $config = [];
    public $curr_config = [];
    public $ApprovalProcess;

    public function beforeAction($action)
    {	
        //初始化系统配置
        $this->layout = 'main_base';
        $temp_config = Config::find()->asarray()->where(['parent_id'=>1])->all();
        foreach ($temp_config as $key => $value) {
            $basic_config[$value['name']] = $value['value'];
        }
        $this->config = $basic_config;

        //获取审核流程 关乎到如果审核还是不是允许 修改 单据信息
        $this->ApprovalProcess = ApprovalProcess::find()->where(['label_name'=>$this->id])->one();

        //获取当前控制器配置
        $temp_config = Config::find()->asarray()->where(['key'=>$this->id])->all();
        foreach ($temp_config as $key => $value) {
            $this->curr_config[$value['name']] = $value['value'];
        }

        $no_login_allow_arr = ['pay-response','pay-notify-url','pay-return-url','login','log-out'];
        if (isset(Yii::$app->session['manage_user'])) {
            // /判断用户权限
            $user_action = Yii::$app->session['manage_user']['action'];

            //通用action
            $common_action = $this->actions();
            if ($user_action == 'all') {
            }elseif(array_key_exists($action->id, $common_action)){
            }else{
                //根据用户的privi_arr 组合clude_action数组
                $clude_action = [];
                $privi_arr = Yii::$app->session['manage_user']['privi_arr'];
                foreach ($privi_arr[$this->id] as $key => $value) {
                  if (isset(sys_config::$privilege_desc[$this->id][$value]['clude_action'])) {
                    $clude_action = array_merge($clude_action, sys_config::$privilege_desc[$this->id][$value]['clude_action']);
                  }
                }
                if (!in_array($action->id, $clude_action) && $this->id <> 'default') {
                    $this->response_wrong();
                }
            }

          //记录管理员日志
        	if (!in_array($action->id, sys_config::$admin_log_arr) && count($_REQUEST) > 0) {        		
	        	$admin_log = new AdminLog;
	        	$admin_log->model = $this->id;
	        	$admin_log->action = $action->id;
	        	$oprate_id = Yii::$app->request->get('id')?Yii::$app->request->get('id'):(Yii::$app->request->post('id')?Yii::$app->request->post('id'):0);
	        	$admin_log->ano_id = $oprate_id;
				    $admin_id = Yii::$app->session['manage_user']?Yii::$app->session['manage_user']['id']:0;
	        	$admin_log->admin_id = $admin_id;
	        	$admin_log->param = serialize($_REQUEST);
	        	$admin_log->add_time = time();
	        	$admin_log->save(false);
        	}
          return true;
        } else {
            if(in_array($action->id, $no_login_allow_arr)){
                return true;
            }else{
                $url = Yii::$app->urlManager->createUrl(['/default/login']);
                Yii::$app->response->redirect($url, 302)->send();
            }
        }
    }
    
    // public function afterAction($action)
    // { 
    //   //记录管理员日志
    //   if (!in_array($action->id, sys_config::$admin_log_arr) && count($_REQUEST) > 0) {           
    //     $admin_log = new AdminLog;
    //     $admin_log->model = $this->id;
    //     $admin_log->action = $action->id;
    //     $oprate_id = Yii::$app->request->get('id')?Yii::$app->request->get('id'):(Yii::$app->request->post('id')?Yii::$app->request->post('id'):0);
    //     $admin_log->ano_id = $oprate_id;
    //     $admin_log->admin_id = Yii::$app->session['manage_user']?Yii::$app->session['manage_user']['id']:0;
    //     $admin_log->param = serialize($_REQUEST);
    //     $admin_log->add_time = time();
    //     $admin_log->save(false);
    //   }
    //   return true;
    // }


    //添加
    public function actionCreate(){
        $this->layout = 'empty';
        return $this->render('create', []);
    }

    public function actionIndex()
    {
      return $this->render('index', []);
    }
 

    public function actions(){
      return [
        'create-goods' => [
            'class' => 'app\common\actions\SearchGoodsAction',
        ],

          'create-goods-store' => [
              'class' => 'app\common\actions\GoodStoreAction',
          ],
        'create-materiel' => [
            'class' => 'app\common\actions\SearchMaterielAction',
        ],
        'search-supplier' => [
            'class' => 'app\common\actions\SearchSupplierAction',
        ],
        'token-custom-search' => [
            'class' => 'app\common\actions\SearchCustomAction',
        ],
        'token-custom-order' => [
            'class' => 'app\common\actions\SearchCustomOrderAction',
        ],
        'export'=>[
            'class'=>'app\common\actions\ExportAction',
        ],
          'exportg'=>[
              'class'=>'app\common\actions\ExportgAction',
          ],
          'exports'=>[
              'class'=>'app\common\actions\ExportsAction',
          ],
        'export-ppt'=>[
            'class'=>'app\common\actions\ExportPptAction',
        ],
        'token-sell-order'=>[
            'class'=>'app\common\actions\SearchSellOrderAction',
        ],
        'token-purchase'=>[
            'class'=>'app\common\actions\SearchPurchaseAction',
        ],        
        'contract-search'=>[
            'class'=>'app\common\actions\SearchContractAction',
        ],
        'update-file-desc'=>[
            'class'=>'app\common\actions\UpdateFileDescAction',
        ],
        'delete-file'=>[
            'class'=>'app\common\actions\DeleteFileAction',
        ],
        'token-purchase-search'=>[
            'class'=>'app\common\actions\SearchPurchaseAction',
        ],
        'token-user-search'=>[
            'class'=>'app\common\actions\SearchAdminAction',
        ],
        'get-approval-order'=>[
            'class'=>'app\common\actions\GetApprovalOrderAction',
        ],
        'search-goods'=>[
            'class'=>'app\common\actions\SearchGoodsTokenAction',
        ],
        'to-admit'=>[
            'class'=>'app\common\actions\ToAdmitAction',
        ],        
      ];
    }

    public function response_wrong($message = '没有权限执行该操作'){
        if (Yii::$app->request->isAjax) {
            message::result_json('99',$message);
        }else{
           Message::show_message($message,[],'error');
        }
    }


    //用户对单据进行操作  防止 用户自己传参数 修改其他用户单据

    public function user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label){
      /*
        审核只能是部门主管 或 总经理
      */
      if ($action->id == 'admit') {
        if (Yii::$app->session['manage_user']['role']->role_type > 2 && Yii::$app->session['manage_user']['role']->role_type <> 99 ) {
          $this->response_wrong();
          return false;
        }
      }

      //取消自定义need_privi_arr 因为只有存在ID参数的 才需要做验证 所以用ID是不是存在取代need_privi_arr
      //之后发现 view 操作是需要id的 
      //故还是使用need_privi_arr
      //if (in_array($action->id, $need_privi_arr)) {  
      $id = Yii::$app->request->get('id',0);
      $order_id = Yii::$app->request->get('order_id',0);
      $id = $id?$id:$order_id;

      if ($id) {
        $scope_model_ob = $scope_model::findone($id);
        //如果订单已经复核 将不能执行 $admit_allow_arr 外的操作

        //检查审批流程 是不是允许 $admit_allow_arr 中的操作
        $process_data_arr = $this->get_approval_data($this->id);
        
        //通用action
        $common_action = $this->actions();

        if ($scope_model_ob->$status_label >= count($process_data_arr)-1 && !in_array($action->id, $admit_allow_arr) && !array_key_exists($action->id, $common_action)) {
          $this->response_wrong('已复合单据不允许此操作');
        }

        //如果是删除单据  只要审核过一道 则不允许删除
        if ($action->id == 'delete' && $scope_model_ob->$status_label >= 1) {
          $this->response_wrong('已进入复核流程，不允许删除');
        }

        //防止 用户自己传参数 修改其他用户单据
        if (@$privi = unserialize(Yii::$app->session['manage_user']['role']->priv_arr)) {
           if (isset($privi[$model_name]['scope']) && $privi[$model_name]['scope'] == 1) { //全部数据
           }elseif (isset($privi[$model_name]['scope']) && $privi[$model_name]['scope'] == 2) { //当前部门数据
             if ($scope_model_ob->depart_id !== Yii::$app->session['manage_user']['depart_id']) {
               $this->response_wrong();
               return false;
             }
           }elseif (isset($privi[$model_name]['scope']) && $privi[$model_name]['scope'] == 3) {//只查看自己添加的数据
             if ($scope_model_ob->add_user_id !== Yii::$app->session['manage_user']['id']) {
              $this->response_wrong();
              return false;
             }
           }
        }
      }
      // }else{

      // }
      return true;
    }

    //$ar_ob,$status_label
    /*
      返回值：
      1：审批成功 流程结束
      2：审批成功 但流程未结束
      9：失败
    */
    public function base_admit($ar_ob,$status_label,$status){
      //如果status为0 则返回错误
      if ($status == 0) {
        return ['error'=>9,'message'=>'订单状态不合法'];
      }
      //检查审核流程
      $process_data_arr = $this->get_approval_data($this->id);

      $origin_status = $ar_ob->$status_label;
      if ($status - $origin_status == 1) {
        //如果是总部总经理 则可以执行一切操作
        if (Yii::$app->session['manage_user']['depart_id'] == '1' ) {
        }else{
          //检查权限 当前status审批需求部门类型 是不是 当前用户的部门类型
          if ($process_data_arr[$status]['scope_depart_type'] == '0') {
            if ($ar_ob->depart_id <> Yii::$app->session['manage_user']['depart_id']) {
              return ['error'=>9,'message'=>'部门类型不匹配,需要'.sys_config::$approval_depart_type[$process_data_arr[$status]['scope_depart_type']]];
            }
          }else{
            if ($process_data_arr[$status]['scope_depart_type'] <> Yii::$app->session['manage_user']['depart_type']) {
              return ['error'=>9,'message'=>'部门类型不匹配,需要'.sys_config::$approval_depart_type[$process_data_arr[$status]['scope_depart_type']]];
            }
          }
        }

        //如果是不通过 则记录驳回意见 并返回 复核成功
        $unadmit = Yii::$app->request->get('unadmit');
        $remark = Yii::$app->request->get('remark');

        $admit_result = 1;

        if ($unadmit <> null) {
          $admit_result = 0;
        }else{
          //修改单据为新的状态
          $ar_ob->$status_label = ($ar_ob->$status_label)+1;
          $ar_ob->status_name = $process_data_arr[$status]['processed_name'];
          $ar_ob->save(false);
        }

        //记录审批日志ApprovalLog
        $this->add_process_log($ar_ob->id,$status,$process_data_arr[$status]['processed_name'],$remark,$admit_result);

        //发送站内信 和 可能的微信通知




        //检查是不是流程结束
        if ($status == count($process_data_arr)-1) {
          //修改单据审批流程结束
          $ar_ob->status_done = 1;
          $ar_ob->save(false);
          return ['error'=>1,'message'=>'审批成功','content'=>$process_data_arr[$status]];
        }

        return ['error'=>2,'message'=>'审批成功','content'=>$process_data_arr[$status]];
      }else{
        return ['error'=>9,'message'=>'审批流程不存在'];
      }

    }

    //记录审批日志
    public function add_process_log($order_id,$status,$status_name,$remark,$admit_result){
      $ApprovalLog = new ApprovalLog();
      $ApprovalLog->controller_label = $this->id;
      $ApprovalLog->admit_time = time();
      $ApprovalLog->admit_user_id = Yii::$app->session['manage_user']['id'];
      $ApprovalLog->admit_user_name = Yii::$app->session['manage_user']['admin_name'];
      $ApprovalLog->depart_id = Yii::$app->session['manage_user']['depart_id'];
      $ApprovalLog->depart_name = Yii::$app->session['manage_user']['depart_name'];
      $ApprovalLog->status = $status;
      $ApprovalLog->order_id = $order_id;
      $ApprovalLog->status_name = $status_name;
      $ApprovalLog->depart_type = Yii::$app->session['manage_user']['depart_type'];
      $ApprovalLog->depart_type_name = sys_config::$approval_depart_type[Yii::$app->session['manage_user']['depart_type']];
      $ApprovalLog->remark = urldecode($remark);
      $ApprovalLog->admit_result = $admit_result;
      $ApprovalLog->save(false);
      return true;
    }

    public function get_approval_data($label_name){
      $ApprovalProcess = ApprovalProcess::find()->where(['label_name'=>$label_name])->one();
      $process_data_arr = @unserialize($ApprovalProcess->process_data);
      return $process_data_arr;
    }

    //检查是不是已经走完审批流程
    public function if_approval_done($ar_ob,$status_label){
      $process_data_arr = $this->get_approval_data($this->id);
      if ($ar_ob->$status_label >= count($process_data_arr)-1) {
        return true;
      }
      return false;
    }

 

}


































































