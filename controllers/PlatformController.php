<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Platform;
use app\common\models\Custom;
use app\common\models\WebOrder;
use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class PlatformController extends BaseController
{   
    public $page_title = '平台管理';
    public $title_model = 'app\common\models\Platform';
    public $status_label = 'plat_status';

    public function beforeAction($action)
    {
      parent::beforeAction($action);
      //检查当前单据用户是不是有操作权限
      $admit_allow_arr = ['edit','update',];

      $scope_model = 'app\common\models\Platform';
      $status_label = 'plat_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }

    //删除
    public function actionDelete($id){
      $Platform = Platform::findone($id);
      if ($Platform) {
        //检查平台是不是已经使用中
        $web_order = WebOrder::find()->where(['plat_id'=>$id])->one();
        if ($web_order) {
          message::result_json(2,'平台使用中，不允许删除');
        }
        $Platform->delete();
        message::result_json(1,'删除成功');
      }else{
        message::result_json(2,'数据不存在');
      }
    }

 
    public function actionInsert(){
        $Platform = new Platform();
        $Platform->load(Yii::$app->request->post());
        
        if (!$Platform->plat_name) {
          Message::result_json(2,'平台名称不能为空');
        }

        if (!$Platform->custom_id) {
          Message::result_json(2,'客户名称不能为空');
        }else{
          $custom = Custom::findone($Platform->custom_id);
          $Platform->custom_name = $custom->custom_name;
        }

        $Platform->plat_status = 0;

        $Platform->save(false);
        Message::result_json(1,'添加成功');
    }

    public function actionEdit($id){
      $this->layout = 'empty';
        $Platform = Platform::findone($id);
        return $this->render('create', ['platform'=>$Platform,'id'=>$id]);
    } 
 
    public function actionUpdate($id){
        $Platform = Platform::findone($id);
        $Platform->load(Yii::$app->request->post());
        
        if (!$Platform->plat_name) {
          Message::result_json(2,'平台名称不能为空');
        }

        if (!$Platform->custom_id) {
          Message::result_json(2,'客户名称不能为空');
        }else{
          $custom = Custom::findone($Platform->custom_id);
          $Platform->custom_name = $custom->custom_name;
        }

        $Platform->plat_status = 1;

        $Platform->save(false);
        Message::result_json(1,'编辑成功');
    }
}
