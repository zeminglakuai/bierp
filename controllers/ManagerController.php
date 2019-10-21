<?php
namespace app\controllers;

use Yii;
use app\common\config\sys_config;
use app\common\models\Admin;
use app\common\models\Depart;
use app\common\models\Role;
use app\common\models\Custom;
use app\common\models\Supplier;
use app\includes\Message;

use app\common\models\QywechatConfig;
use app\includes\QYwechat\QYwechat;
use app\includes\QYwechat\module\Txl;

use yii\web\Controller;
use yii\db\Query;
use yii\data\Pagination;
use yii\helpers\Url;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use app\controllers\BaseController;

class ManagerController extends BaseController
{	
	public $enableCsrfValidation = false;
    
    public function actionIndex() //管理员列表
    {	
    	$admin_name = trim(Yii::$app->request->get('admin_name'));
        $depart_id = trim(Yii::$app->request->get('depart_id'));

 
		$user_list = Admin::find();

		if($admin_name){
		   $user_list = $user_list->andwhere(['like', 'admin_name', $admin_name]);
		}

        if($depart_id){
           $user_list = $user_list->andwhere(['depart_id'=>$depart_id]);
        }

		$countQuery = clone $user_list;
		$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>10,'pageSizeLimit'=>1]);

		$user_list = $user_list->offset($pages->offset)
		->orderby(['id'=>SORT_DESC])
		->limit($pages->limit)
		->all();

		return $this->render('index', [
			'admin_name' => $admin_name,
            'depart_id' => $depart_id,
			'user_list' => $user_list,
			'pages' => $pages,
		]);
    }


    public function actionCreate()
    {   
        $this->layout = 'empty';
        return $this->render('create', []);
    }

    public function actionAddAdmin()
    {   
        $admin = new Admin();
        if ($admin && $admin->load(Yii::$app->request->post())) {
            //检查用户名是否重复
            $result = Admin::find()->where(['admin_name'=>$admin->admin_name])->one();
            if (isset($result->id) && $result->id > 0) {
                 Message::result_json(3,'管理员用户名重复');
            }

            if (!$admin->depart_id) {
               Message::result_json(4,'请选择用户部门');
            }else{
                $depart = Depart::findone($admin->depart_id);
                if ($depart) {
                    //检查部门类型
                    if($depart->type == 5){ //如果是客户类型 就检查 是不是有提交 客户ID
                        if (!$admin->custom_id) {
                           Message::result_json(4,'请选择用户所属客户');
                        }
                        $custom = Custom::findone($admin->custom_id);
                        $admin->custom_name = $custom->custom_name;
                    }

                    if($depart->type == 6){ //如果是供货商类型 就检查 是不是有提交 供货商ID
                        if (!$admin->supplier_id) {
                           Message::result_json(4,'请选择用户所属供货商');
                        }
                        $custom = Supplier::findone($admin->supplier_id);
                        $admin->supplier_name = $custom->supplier_name;
                    }
                }else{
                    Message::result_json(4,'部门不存在');
                }
            }

            //检查角色id是不是存在于depart——id 下
            if (!$admin->role_id) {
               Message::result_json(5,'请选择用户角色');
            }

            $role = Role::findone($admin->role_id);
            if ($role->depart_id <> $admin->depart_id) {
               Message::result_json(5,'角色不属于该部门');
            }
            
            if (strlen($admin->password) < 6) {
                Message::result_json(5,'密码最少需要6位');
            }

            $admin->salt  = mt_rand(1000,9999);
            $admin->password = md5(md5($admin->password).$admin->salt);
            $admin->is_active  = 1;
            $admin->add_time  = time();
            $admin->save(false);

            Message::result_json(1,'添加成功');
        }else{
            Message::result_json(2,'参数错误');
        }
    }

    public function actionView()
    {   
        $id = is_numeric(Yii::$app->request->get('id'))?Yii::$app->request->get('id'):0;
        $admin = Admin::findone($id);
        if ($admin) {
            $this->layout = 'empty';
            return $this->render('create', ['admin'=>$admin]);
        }else{
            message::show_message('缺少参数',[],'error');
        }
    }

    public function actionEditAdmin()
    {   
        $id = is_numeric(Yii::$app->request->post('id'))?Yii::$app->request->post('id'):0;
        $admin = Admin::findone($id);
 
        if ($admin && $admin->load(Yii::$app->request->post())) {
            //检查用户名是否重复
            $result = Admin::find()->where(['admin_name'=>$admin->admin_name])->andwhere(['<>','id',$id])->one();
            if (isset($result->id) && $result->id > 0) {
                 Message::result_json(3,'管理员用户名重复');
            }

            if (!$admin->depart_id) {
               Message::result_json(4,'请选择用户部门');
            }
            //检查角色id是不是存在于depart——id 下
            if (!$admin->role_id) {
               Message::result_json(5,'请选择用户角色');
            }

            $role = Role::findone($admin->role_id);
            if ($role->depart_id <> $admin->depart_id) {
               Message::result_json(5,'角色不属于该部门');
            }
            if ($admin->password) {
                $admin->salt  = mt_rand(1000,9999);
                $admin->password = md5(md5($admin->password).$admin->salt);
            }else{
                unset($admin->password);
            }
            $admin->save(false);

            //提交微信



            Message::result_json(1,'修改成功');
        }else{
            Message::result_json(2,'参数错误');
        }
    }

	public function actionUpdateAdmin()
    {	
        $allow_arr = ['real_name','is_active','admin_name'];
    	$id = is_numeric(Yii::$app->request->post('id'))?Yii::$app->request->post('id'):0;
        $type = Yii::$app->request->post('type') && in_array(trim(Yii::$app->request->post('type')), $allow_arr)?trim(Yii::$app->request->post('type')):0;
        $value = Yii::$app->request->post('value')?trim(Yii::$app->request->post('value')):0;

        if ($id && $type) {
            $admin = Admin::findone($id);
            if ($admin) {
                $admin->$type = $value;
                $admin->save(false);

                Message::result_json(1,'编辑成功',$value);
            } else {
                Message::result_json(2,'缺少参数');
            }
        }else{
            Message::result_json(3,'缺少参数');
        }
    }

    //得到部门下 角色列表
    public function actionGetRoleList($depart_id){
        $role_list = Role::find()->where(['depart_id'=>$depart_id])->asarray()->all();
        if($role_list){
            $depart = Depart::findone($depart_id);
            $result_content['depart_type'] = $depart->type;

            $role_list_select = Html::dropDownList('Admin[role_id]', 0, ArrayHelper::map($role_list,'id','role_name'),['class' => 'form-control','id'=>'role_id']);
            $result_content['role_list_select'] = $role_list_select;

            message::result_json(1,'',$result_content);
        }else{
            message::result_json(2,'没有角色');
        }
    }

    //修改密码
    public function actionChangePass(){

        $admin_id = Yii::$app->request->get('id');

        if (!is_numeric($admin_id)) {
           Message::show_message('参数错误',[],'error');
        }

        //得到该用户
        $admin_user = Admin::findone($admin_id);

        return $this->render('pass', ['admin_user'=>$admin_user]);

    }

    //修改密码
    public function actionUpdatePass($id){
        $password = Yii::$app->request->post('password');
        $c_password = Yii::$app->request->post('c_password');
        if ($password <> $c_password) {
            Message::show_message('两次密码不一致',[],'error');
        }

        if (strlen($password) < 7 || strlen($password) > 15) {
           Message::show_message('密码应该在7-15位之间',[],'error'); 
        }

        $admin_user = Admin::findone($id);
        $salt = rand(1000,9999);
        $jiami = md5(md5($password).$salt);
        $admin_user->password = $jiami;
        $admin_user->salt = $salt;
        $admin_user->save(false);

        Message::show_message('密码修改成功');
    }

    //查看管理员日志
    public function actionAdminLog($id){
        // 检查管理员是不是当前商户内
        $admin_user = Admin::find($id);
        if ($admin_user <> NULL) {

            $query = new \yii\db\Query();
     
            $log_list = $query->select('al.*,au.admin_name')
                            ->from('admin_log as al')
                            ->leftjoin('admin as au','au.id = al.admin_id')
                            ->where(['admin_id'=>$id]);

            $countQuery = clone $query;
            $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>10,'pageSizeLimit'=>1]);

            $log_list = $query->offset($pages->offset)
            ->orderby(['id'=>SORT_DESC])
            ->limit($pages->limit)
            ->all();
            
            foreach ($log_list as $key => $value) {
                 $log_list[$key]['param'] = unserialize($value['param']);
            }

            return $this->render('admin-log', [
                'log_list' => $log_list,
                'pages' => $pages,
            ]);
        }else{
            Message::show_message('管理员不存在',[],'error');
        }
         
    }




}
