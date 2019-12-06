<?php
namespace app\teamwork\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\helpers\Url;
use app\common\config\sys_config;
use app\common\models\Config;
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
    public $layout = 'main';
    
    public function beforeAction($action)
    {	
        $temp_config = Config::find()->asarray()->where(['parent_id'=>1])->all();
        foreach ($temp_config as $key => $value) {
            $basic_config[$value['name']] = $value['value'];
        }
        $this->config = $basic_config;

 
        //获取当前控制器配置
        $temp_config = Config::find()->asarray()->where(['key'=>$this->id])->all();
        foreach ($temp_config as $key => $value) {
            $this->curr_config[$value['name']] = $value['value'];
        }

        $no_login_allow_arr = ['pay-response','pay-notify-url','pay-return-url','login','log-out'];
        if (isset(Yii::$app->session['manage_user'])) {
            // /判断用户权限
            $user_action = Yii::$app->session['manage_user']['action'];
          return true;
        } else {
            $url = Yii::$app->urlManager->createUrl(['/default/login']);
            Yii::$app->response->redirect($url, 302)->send();
        }
    }
    

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
      ];
    }

    public function response_wrong($message = '没有权限执行该操作'){
        if (Yii::$app->request->isAjax) {
            message::result_json('99',$message);
        }else{
           Message::show_message($message,[],'error');
        }
    }
}


