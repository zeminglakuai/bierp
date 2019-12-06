<?php
namespace app\controllers;

use yii\web\Controller;
use Yii;
use app\includes\Message;
use app\common\config\sys_config;
use app\common\models\Admin;
use app\common\models\AdminAuth;
use app\common\models\Depart;
use app\controllers\BaseController;

use app\common\queue\DoSth;


class DefaultController extends BaseController
{
    public $enableCsrfValidation = false;
    public function actionIndex()
    {	
    	$this->layout = 'main';

    // Yii::$app->queue->push(new DoSth([
    //     'url' => 'http://www.icloudy.top',
    //     'file' => 'D:/xampp/htdocs/kw_erp/web/234234.txt',
    // ]));

    // Yii::$app->queue->delay(60)->push(new DoSth([
    //     'url' => 'http://www.icloudy.top',
    //     'file' => 'D:/xampp/htdocs/kw_erp/web/123123123.txt',
    // ]));


    
		  return $this->render('index');
    }
 	
    public function actionUserIndex()
    {	
      $this->layout = 'main_base';
    	$is_edit = Yii::$app->request->get('is_edit',0);
    	$user_widget = [];
    	if ($is_edit) {
    		//得到用户可用widget
    		$user_widget = [];
    	}
        return $this->render('index',['is_edit'=>$is_edit,'user_widget'=>$user_widget]);
    }


	// 登录
	public function actionLogin()
    {	
		  //更改显示的基本模板
		  $this->layout = 'main_login';
      if (isset(Yii::$app->session['manage_user'])) {
          $url = Yii::$app->urlManager->createUrl(['/default/index']);
          return Yii::$app->getResponse()->redirect($url)->send();
      } else {
  		 	$user_name = isset($_REQUEST['user_name'])?htmlspecialchars(trim($_REQUEST['user_name'])):0;
  			$password = isset($_REQUEST['password'])?$_REQUEST['password']:0;

  			if ($user_name && $password) {
  				
  			  //检查是不是正确
  				$user = Admin::find()
  								->where(['admin_name' => $user_name])
  								->one();
  				if (!$user) {
  					//$links = [['link_name'=>'返回缴费页面','link_url'=>'index.php?r=admin/user/renew&id='.$user]];
            Message::show_message('用户不存在');
  				}
  				//检查不通过 就返回密码不对的用户提示.
  				$jiami2 = md5(md5($password).$user->salt);
  				if($user->password == $jiami2){

  				   // 更新用户最后登录时间  和 ip
  				   $user->last_ip = Yii::$app->request->UserIP;
  				   $user->last_login_time = time();
  				   $user->save(false);
  				   
            //记录登陆日志
   

  					//建立session
  				   $manage_session = ['id'=>$user->id,
  									   'admin_name'=>$user->admin_name,
  									   'type'=>$user->type,
  									   'depart_id'=>$user->depart_id,
  				   ];

  				   //得到部门信息
  				   if ($user->depart) {
  						$manage_session['depart_name'] = $user->depart->depart_name;
              $manage_session['store_id'] = $user->depart->store_id;
              $manage_session['depart_type'] = $user->depart->type;
  				   }else{
  				   		if ($user->type <> 1) {
  				   			Message::show_message('用户未分配部门，为无效用户',[],'error'); 
  				   		}
  				   }
             if ($user->role) {
                 $manage_session['role'] = $user->role;
                 if (@$manage_session['action'] = unserialize($user->role->action)) {
                 }else{
                      $manage_session['action'] =  $user->role->action;
                 }

                 if (@$manage_session['privi_arr'] = unserialize($user->role->priv_arr)) {
                 }else{
                      $manage_session['privi_arr'] =  $user->role->priv_arr;
                 }
             }else{
                  if ($user->type <> 1) {
                      Message::show_message('用户未分配角色,为无效用户',[],'error'); 
                  }
             }

  				   Yii::$app->session['manage_user'] = $manage_session;
                     //检查用户是不是有被授权
                     $user_auth = AdminAuth::find()->where(['to_id'=>$user->id])->andwhere(['>','expire',time()])->all();
                     if ($user_auth) {
                         $url = Yii::$app->urlManager->createUrl(['/default/auth-list']);
                         return Yii::$app->getResponse()->redirect($url)->send();
                     }

  				   $url = Yii::$app->urlManager->createUrl(['/default/index']);
              	   return Yii::$app->getResponse()->redirect($url)->send();
  				}else{
  					Message::show_message('用户不存在'); 
  				}
  			}else{
  			   return $this->render('login');
  			}
      }
    }
	
    public function actionAuthList()
    {   
        $this->layout = 'main_login';
        $auth_list = AdminAuth::find()->where(['to_id'=>Yii::$app->session['manage_user']['id']])->andwhere(['>','expire',time()])->all();
        return $this->render('auth-list', ['auth_list'=>$auth_list]);
    }

    public function actionSelectAuth()
    {   
        $id = Yii::$app->request->get('id');
        if ($id) {
           $admin_auth = AdminAuth::findone($id);
           if ($admin_auth) {
               if ($admin_auth->to_id > 0 && $admin_auth->to_id == Yii::$app->session['manage_user']['id']) {
                    $user = Admin::findone($admin_auth->from_id);
                    if ($user->id) {
                        $manage_session = ['id'=>$user->id,
                                           'auth_id'=>Yii::$app->session['manage_user']['id'],
                                           'admin_name'=>$user->admin_name,
                                           'type'=>$user->type,
                                           'depart_id'=>$user->depart_id
                       ];

                       //得到部门信息
                       if ($user->depart) {
                            $manage_session['depart_name'] = $user->depart->depart_name;
                            $manage_session['store_id'] = $user->depart->store_id;
                       }else{
                            if ($user->type <> 1) {
                                Message::show_message('用户未分配部门，为无效用户',[],'error'); 
                            }
                       }

                       if ($user->role) {
                           $manage_session['role'] =  $user->role;
                           if (@$manage_session['action'] = unserialize($user->role->action)) {
                           }else{
                              $manage_session['action'] =  $user->role->action;
                           }
                       }else{
                            if ($user->type <> 1) {
                                Message::show_message('用户未分配角色,为无效用户',[],'error'); 
                            }else{
                              
                            }
                       }

                       Yii::$app->session['manage_user'] = $manage_session;
                    }

               }
           }
        }
       $url = Yii::$app->urlManager->createUrl(['/default/index']);
       return Yii::$app->getResponse()->redirect($url)->send();
    }

	  //退出登录
	  public function actionLogOut()
    {	
        Yii::$app->session->destroy();
		$url = Yii::$app->urlManager->createUrl(['/']);
        return Yii::$app->getResponse()->redirect($url)->send();
    }

    //修改密码
    public function actionChangePass(){
    	$this->layout = 'empty';
        $admin_id = Yii::$app->session['manage_user']['id'];

        if (!is_numeric($admin_id)) {
           Message::show_message('参数错误',[],'error');
        }

        //得到该用户
        $admin_user = Admin::findone($admin_id);

        return $this->render('pass', ['admin_user'=>$admin_user]);

    }

    //修改密码
    public function actionUpdatePass(){

        //被授权用户不能修改密码
        if (Yii::$app->session['manage_user']['auth_id'] > 0) {
           Message::result_json(3,'被授权用户不能进行该操作');
        }

        $password = Yii::$app->request->post('password');
        $c_password = Yii::$app->request->post('c_password');
        if ($password <> $c_password) {
            Message::result_json(2,'两次密码不一致');
        }

        if (strlen($password) < 7 || strlen($password) > 15) {
        	Message::result_json(2,'密码应该在7-15位之间');
        }

        $admin_user = Admin::findone(Yii::$app->session['manage_user']['id']);
        $salt = rand(1000,9999);
        $jiami = md5(md5($password).$salt);
        $admin_user->password = $jiami;
        $admin_user->salt = $salt;
        $admin_user->save(false);
        Message::result_json(1,'密码修改成功');
    }


    //用户授权
    public function actionUserAuth(){
    	$this->layout = 'empty';
        $auth_log = AdminAuth::find()->where(['from_id'=>Yii::$app->session['manage_user']['id']])->limit(5)->orderby(['id'=>3])->all();

        return $this->render('user-auth',['auth_log'=>$auth_log]);
    }

    //用户授权
    public function actionActUserAuth(){
        //被授权用户不能修改密码
        if (Yii::$app->session['manage_user']['auth_id'] > 0) {
           Message::result_json(3,'被授权用户不能进行该操作');
        }

    	$user_name = Yii::$app->request->post('user_name');
        $end_time = strtotime(Yii::$app->request->post('end_time'));

        //检查用户是不是存在
        $to_admin = Admin::find()->where(['admin_name'=>$user_name])->one();
        if (!isset($to_admin->id)) {
            Message::result_json(2,'用户不存在');
        }

        if ($to_admin->id == Yii::$app->session['manage_user']['id']) {
            Message::result_json(2,'不能授权给自己');
        }

        //检查时间是不是大于当前时间
        if ($end_time < time()) {
            Message::result_json(2,'授权结束时间应该大于当前时间');
        }

        $admin_auth = new AdminAuth();
        $admin_auth->to_id = $to_admin->id;
        $admin_auth->from_id = Yii::$app->session['manage_user']['id'];
        $admin_auth->expire = $end_time;
        $admin_auth->add_time = time();
        $admin_auth->save(false);
        Message::result_json(1,'授权成功！');
    }

    //用户资料
    public function actionUserProfile(){
        $this->layout = 'empty';
        $admin = Admin::findone(Yii::$app->session['manage_user']['id']);
        return $this->render('user-profile',['admin'=>$admin]);
    }



}
