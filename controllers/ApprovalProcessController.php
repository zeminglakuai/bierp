<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\Url;
use app\common\models\ApprovalProcess;
use app\includes\Message;
use app\includes\Common_fun;
use app\common\config\export_config;
use app\common\config\sys_config;
use app\controllers\BaseController;


class ApprovalProcessController extends BaseController
{   
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
    	$module_list = [];
    	foreach (sys_config::$nav_list as $key => $value) {
    		if (isset($value['sub_list'])) {
    			foreach ($value['sub_list'] as $kk => $vv) {
                    if (isset($vv['is_approval'])) {
                        if (!isset($module_list[$key])) {
                            $module_list[$key] = ['id'=>$key,'text'=>$value['name']];
                        }
                        $module_list[$key]['children'][] = ['id'=>$kk,'text'=>$vv['name']];
                    }
    			}
    		}
    	}
 
		$module_list = Common_fun::module_arr2jstr($module_list);
        return $this->render('index', [
                                       'module_list' => $module_list,
        ]);
    }


    public function actionProcessList()
    {
    	$this->layout = false;
    	$module = Yii::$app->request->get('module');
        $module_name = false;

        foreach (sys_config::$nav_list as $key => $value) {
            if (array_key_exists($module, $value['sub_list'])) {
                $module_name = $value['sub_list'][$module]['name'];
                break;
            }
        }
        if (!$module_name) {
            return false;
        }


        $ApprovalProcess = ApprovalProcess::find()->where(['label_name'=>$module])->one();
        if (!$ApprovalProcess) {
            $ApprovalProcess = new ApprovalProcess();
            $ApprovalProcess->label_name = $module;
            $ApprovalProcess->process_data = serialize(
                                             [0=>['process_name'=>'未复核','scope_depart_type'=>0,'processed_name'=>'未复核','tips'=>'','allow_amend_order'=>1],
                                              1=>['process_name'=>'复核','scope_depart_type'=>0,'processed_name'=>'已复核','tips'=>'由部门经理复核','allow_amend_order'=>0]
                                              ]);
            $ApprovalProcess->save(false);
        }

        if (!$process_data_arr = @unserialize($ApprovalProcess->process_data)) {
            $process_data_arr = [0=>['process_name'=>'未复核','scope_depart_type'=>0,'processed_name'=>'未复核','tips'=>'','allow_amend_order'=>1],
                                              1=>['process_name'=>'复核','scope_depart_type'=>0,'processed_name'=>'已复核','tips'=>'由部门经理复核','allow_amend_order'=>0]
                                              ];
        }

        $content =  $this->render('process-list', [
        								'approval-process' => $ApprovalProcess,
                                        'module' => $module,
                                        'module_name' => $module_name,
                                        'process_data_arr' => $process_data_arr,
        ]);

        Message::result_json(1,'',$content);
    }

    public function actionCreate()
    {
    	$this->layout = 'empty';
    	$module = Yii::$app->request->get('module',0);

    	if ($module) {
    		return $this->render('edit', [
    						       'module' => $module,
       		]);
    	}else{
    		message::show_message('asdasdas');
    	}
 		
    }

    public function actionInsert()
    {
        $module = Yii::$app->request->post('module',0);
        $process_name = Yii::$app->request->post('process_name',0);
        $scope_depart_type = Yii::$app->request->post('scope_depart_type',null);
        $processed_name = Yii::$app->request->post('processed_name',0);
        $allow_amend_order = Yii::$app->request->post('allow_amend_order',0);
        $tips = Yii::$app->request->post('tips',0);

        
        if (!$process_name || !is_numeric($scope_depart_type) || !$processed_name) {
           message::result_json(2,'请填写必填项');
        }

        //得到当前配置
        $ApprovalProcess = ApprovalProcess::find()->where(['label_name'=>$module])->one();
        if (!$ApprovalProcess) {
           message::result_json(2,'参数错误');
        }

        $process_data_arr = unserialize($ApprovalProcess->process_data);

        //添加当前数据到数组中
        $new_process_date = ['process_name'=>$process_name,'scope_depart_type'=>$scope_depart_type,'processed_name'=>$processed_name,'tips'=>$tips,'allow_amend_order'=>$allow_amend_order];
        array_push($process_data_arr,$new_process_date);
        $ApprovalProcess->process_data = serialize($process_data_arr);
        $ApprovalProcess->save(false);

        message::result_json(1,'添加成功');
    }

    public function actionEdit($id)
    {
        $this->layout = 'empty';
        $module = Yii::$app->request->get('module',0);

        if ($id == 0) {
            message::show_message('默认状态不允许编辑',[],'error');
        }

        //得到当前配置
        $ApprovalProcess = ApprovalProcess::find()->where(['label_name'=>$module])->one();
        if (!$ApprovalProcess) {
           message::show_message('参数错误',[],'error');
        }
        
        $process_data_arr = unserialize($ApprovalProcess->process_data);
        // print_r($process_data_arr);
        // print_r($process_data_arr[$id]);
        return $this->render('edit', [
                                    'module' => $module,
                                    'process_data_arr' => $process_data_arr[$id],
                                    'id' => $id,
        ]);
    }

    public function actionUpdate($id)
    {
        $module = Yii::$app->request->post('module',0);
        $process_name = Yii::$app->request->post('process_name',0);
        $scope_depart_type = Yii::$app->request->post('scope_depart_type',null);
        $processed_name = Yii::$app->request->post('processed_name',0);
        $allow_amend_order = Yii::$app->request->post('allow_amend_order',0);
        $tips = Yii::$app->request->post('tips',0);

        
        if (!$process_name || !is_numeric($scope_depart_type) || !$processed_name) {
           message::result_json(2,'请填写必填项');
        }

        //得到当前配置
        $ApprovalProcess = ApprovalProcess::find()->where(['label_name'=>$module])->one();
        if (!$ApprovalProcess) {
           message::result_json(2,'参数错误');
        }

        $process_data_arr = unserialize($ApprovalProcess->process_data);

        //添加当前数据到数组中
        $new_process_date = ['process_name'=>$process_name,'scope_depart_type'=>$scope_depart_type,'processed_name'=>$processed_name,'tips'=>$tips,'allow_amend_order'=>$allow_amend_order];
        $process_data_arr[$id] = $new_process_date;
        $ApprovalProcess->process_data = serialize($process_data_arr);
        $ApprovalProcess->save(false);

        message::result_json(1,'编辑成功');
    }

    public function actionDelete($id)
    {
        $module = Yii::$app->request->get('module',0);
        
        //得到当前配置
        $ApprovalProcess = ApprovalProcess::find()->where(['label_name'=>$module])->one();
        if (!$ApprovalProcess) {
           message::result_json(2,'参数错误');
        }

        if ($id == 0) {
            message::show_message('默认状态不允许删除',[],'error');
        }

        $process_data_arr = unserialize($ApprovalProcess->process_data);

        //删除当前数据到数组中
        $process_data_arr[$id] = false;
        unset($process_data_arr[$id]);
        $process_data_arr = array_values($process_data_arr);

        $ApprovalProcess->process_data = serialize($process_data_arr);
        $ApprovalProcess->save(false);

        message::result_json(1,'删除成功');
    }
}
