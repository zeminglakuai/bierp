<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Depart;
use app\common\models\Role;
use app\common\models\Admin;
use app\common\models\Region;
use app\includes\Message;
use app\includes\Common_fun;
use app\common\config\sys_config;

use app\includes\QYwechat\module\Txl;

use app\controllers\BaseController;
class DepartController extends BaseController
{   
    public $enableCsrfValidation = false;
 

    public function actionIndex()
    {	
        $depart_list = Depart::get_depart_tree();

        //$depart_list = json_encode($depart_list[1],false);
        $depart_list = Common_fun::depart_arr2jstr($depart_list);
        return $this->render('index', [
                                        'depart_list' => $depart_list,
        ]);
    }

    public function actionGetDepartJson()
    {   
        $depart_list = Depart::get_depart_tree();
        $depart_list = $depart_list[1];
        die(json_encode($depart_list,false));
     }   

    public function actionCreate()
    {
        //得到当前省列表
        $this->layout = 'empty';
        return $this->render('create', []);
    }

    public function actionAddDepart()
    { 
        $depart = new Depart();
        if ($depart->load(Yii::$app->request->post())) {
            if (!isset($depart->depart_name) || $depart->depart_name == '') {
                 Message::result_json(2,'部门名称必须填写');
            }

            $depart->add_time = time();
            $depart->save(false);
                
            //同步部门
            // $txl = new Txl();
            // $data = '{"name": "'.$depart->depart_name.'",
            //            "parentid": '.$depart->parent_id.',
            //            "order": 1,
            //            "id": '.$depart->id.'}';

            // $create_result = $txl->createDepartment($data);
            // print_r($create_result);
            
            Message::result_json(1,'添加成功');

        } else {
            Message::result_json(2,'缺少参数');
        }
    }

    public function actionRoleList($id)
    { 
        $id = is_numeric(Yii::$app->request->get('id'))?Yii::$app->request->get('id'):0;
        if ($id) {
            $depart =  Depart::findone($id);            
            $this->layout = 'empty';
            $this->layout = false;
            $role_list = Role::find()->where(['depart_id'=>$id])->all();
            $content = $this->render('role-list', ['depart'=>$depart,'role_list'=>$role_list]);
            Message::result_json(1,'',$content);
        }else{
            Message::result_json(2,'参数错误','部门下还没有角色');
        }
    }


    //更新部门信息
    public function actionUpdateDepart()
    {   
        $id = is_numeric(Yii::$app->request->post('id'))?Yii::$app->request->post('id'):0;
        $depart = Depart::findone($id);
        if ($depart && $depart->load(Yii::$app->request->post())) {
            if (!isset($depart->depart_name) || $depart->depart_name == '') {
                 Message::result_json(2,'部门名称必须填写');
            }

            //如果是仓库部门 就必须选择 仓库
            if ($depart->type == 3) {
               if ($depart->store_id > 0) {
               }else{
                Message::result_json(2,'请选择部门仓库');
               }
            }else{
                $depart->store_id = 0;
            }

            $depart->save(false);
 
            Message::result_json(1,'编辑成功');

        } else {
            Message::result_json(2,'缺少参数');
        }
    }

    //删除部门
    public function actionDeleteDepart(){
        $id = is_numeric(Yii::$app->request->get('id'))?Yii::$app->request->get('id'):0;
        $depart = Depart::findone($id);
        if ($depart) {
            //检查部门下 有无用户
            $admin_user = Admin::find()->where(['depart_id'=>$id])->count();
            if($admin_user){
               Message::result_json(3,'部门存在用户，不允许删除!'); 
            }
            $depart->delete();
            role::deleteAll(['depart_id'=>$id]);
            Message::result_json(1,'删除成功'); 
        }else{
            Message::result_json(2,'部门不存在');
        }
    }

    //添加职位
    public function actionCreateRole($id){
        $this->layout = 'empty';
        return $this->render('role-create', ['depart_id'=>$id]);
    }

    //添加职位
    public function actionInsertRole(){
        $role = new Role();
        $role->load(Yii::$app->request->post());
        if(!empty($role->role_name)){
            $role->save(false);
            Message::result_json(1,'添加成功'); 
        }else{
           Message::result_json(2,'请填写角色名称'); 
        }
    } 

    //编辑
    public function actionEditRole($id){
        $this->layout = 'empty';
        $role = Role::findone($id);
        if ($role) {
          return $this->render('role-create', ['role'=>$role]);
        }else{

        }
    }

    //编辑
    public function actionUpdateRole($id){
        $role = Role::findone($id);
        $role->load(Yii::$app->request->post());
        if(!empty($role->role_name)){
            $role->save(false);
            Message::result_json(1,'编辑成功'); 
        }else{
           Message::result_json(2,'请填写角色名称'); 
        }
    }

    //删除职位
    public function actionDeleteRole(){
        $id = is_numeric(Yii::$app->request->get('id'))?Yii::$app->request->get('id'):0;
        $role = Role::findone($id);
        if($role){
            //检查角色下 有无用户
            $admin_user = Admin::find()->where(['role_id'=>$id])->count();
            if($admin_user){
               Message::result_json(3,'角色存在用户，不允许删除!'); 
            }
            $role->delete();

            Message::result_json(1,'删除成功'); 
        }else{
            Message::result_json(2,'数据不存在'); 
        }
    }

    //分派权限
    public function actionEditPrivi($id){
        if (!is_numeric($id) || $id == 1) {
           Message::result_json(2,'参数错误');
        }

        //得到该用户权限
        $role = Role::findone($id);
        if ($role) {
            $priv_arr = $role->action;
            $role->priv_arr = unserialize($role->priv_arr);

            $this->layout = 'empty';
            return $this->render('edit-privi', [
                                'role'=>$role,
                                'priv_arr' => $priv_arr,
            ]);
        }else{
             Message::show_message('用户错误',[],'error');
        }
    }


    //修改用户权限
    public function actionActPrivilege()
    {   

        $id = !empty($_POST['id'])?$_POST['id']:0;

        if (!is_numeric($id) || $id == 1) {
           Message::show_message('参数错误',[],'error');
        }
 
        $action   = [];
        $priv_arr = [];

        foreach (sys_config::$privilege_desc as $key => $value) {
             foreach ($value as $kk => $vv) {
                  if (isset($_POST[$key][$kk])) {
                        if ($kk == 'scope') {
                           $priv_arr[$key]['scope'] = isset($_POST[$key]['scope'])?$_POST[$key]['scope']:3;
                        }else{
                           $priv_arr[$key][] = $kk;
                           if(!isset($action[$key]['clude_action'])){
                              $action[$key]['clude_action'] = [];
                           }
                           $action[$key]['clude_action']   = array_merge($action[$key]['clude_action'],$vv['clude_action']);
                           
                           if(!isset($action[$key]['widget'])){
                              $action[$key]['widget'] = [];
                           }
                           if (isset($vv['widget'])) {
                              $action[$key]['widget']   = array_merge($action[$key]['widget'],$vv['widget']);
                           }
                        }
                  }
             }
        }
		/*print_r($action);
		print_r($priv_arr);
		exit();*/
        //得到该用户权限
        $role = Role::findone($id);
 
        if (isset($role->id)) {

            $role->action = serialize($action);
            $role->priv_arr = serialize($priv_arr);
            $role->save(false);

            Message::show_message('修改成功');

        }else{
             Message::show_message('用户错误',[],'error');
        }
    }
}
