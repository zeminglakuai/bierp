<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Materiel;
use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class MaterielController extends BaseController
{
    public $page_title = '物料资料';
    public $status_label = 'leave_status';
    
    public function beforeAction($action)
    {
      parent::beforeAction($action);
      //检查当前单据用户是不是有操作权限

      $need_privi_arr = ['delete','edit','admit',];
      $admit_allow_arr = ['edit','update'];
      $scope_model = 'app\common\models\Contract';
      $status_label = 'contract_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }
 
    //删除
    public function actionDelete($id){
      $Materiel = Materiel::findone($id);
      if ($Materiel) {
        $Materiel->is_delete = 1;
        $Materiel->save(false);
        message::result_json(1,'删除成功');
      }else{
        message::result_json(2,'数据不存在');
      }
    }

    //添加
    public function actionInsert(){
        $Materiel = new Materiel();
        $Materiel->load(Yii::$app->request->post());

        if (isset($Materiel->materiel_name) && strlen($Materiel->materiel_name) > 5) {
        }else{
          Message::result_json(2,'请填写物料名称');
        }

        if (isset($Materiel->use_to) && strlen($Materiel->use_to) > 5) {
        }else{
          Message::result_json(2,'请填写物料用途');
        }

        $Materiel->is_delete = 0;
        $Materiel->save(false);
        Message::result_json(1,'添加成功');
    }

    public function actionEdit($id){
        $this->layout = 'empty';
        $materiel = Materiel::findone($id);
        return $this->render('create', ['materiel'=>$materiel,'id'=>$id]);
    }

    public function actionUpdate($id){
        $Materiel = Materiel::findone($id);
        $Materiel->load(Yii::$app->request->post());

        if (isset($Materiel->materiel_name) && strlen($Materiel->materiel_name) > 5) {
        }else{
          Message::result_json(2,'请填写物料名称');
        }

        if (isset($Materiel->use_to) && strlen($Materiel->use_to) > 5) {
        }else{
          Message::result_json(2,'请填写物料用途');
        }
        $Materiel->save(false);
        Message::result_json(1,'编辑成功');
    }
}
