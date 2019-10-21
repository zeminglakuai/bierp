<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\WebUser;

use yii\helpers\Url;
use app\includes\Message;

use app\controllers\BaseController;
use app\includes\Common_fun;

class WebUserController extends BaseController
{
    public $page_title = '全网会员';
    public function beforeAction($action)
    {
      parent::beforeAction($action);

      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','admit','insert-goods','delete-goods','update-goods-label'];
      $admit_allow_arr = ['edit'];

      $scope_model = 'app\common\models\ExportOrder';
      $status_label = 'export_order_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }
 
    //删除
    public function actionDelete($id){
      $WebUser = WebUser::findone($id);
      if ($WebUser) {
        $WebUser->delete();
        message::result_json(1,'删除成功');
      }else{
        message::result_json(1,'数据错误');
      }
    }

    //添加
    public function actionInsert(){
        $ExportOrder = new ExportOrder();
        $ExportOrder->load(Yii::$app->request->post());

        if (!$ExportOrder->custom_id) {
          Message::result_json(2,'客户不能为空');
        }

        $custom = Custom::findone($ExportOrder->custom_id);
        if (!$custom) {
          Message::result_json(2,'客户不存在，请重新选择');
        }else{
          $ExportOrder->custom_name = $custom->custom_name;
        }

        $ExportOrder->order_sn = Common_fun::create_sn('app\common\models\ExportOrder',5);
        
        $ExportOrder->save(false);
        Message::result_json(1,'添加成功');
    }

    public function actionView($id){
        $ExportOrder = ExportOrder::findone($id);
        return $this->render('view', ['export_order'=>$ExportOrder,'id'=>$id]);
    }

    public function actionEdit($id){
        $this->layout = 'empty';
        $ExportOrder = ExportOrder::findone($id);
        return $this->render('edit', ['export_order'=>$ExportOrder,'id'=>$id]);
    }

    public function actionUpdate($id){
        $ExportOrder = ExportOrder::findone($id);
        $ExportOrder->load(Yii::$app->request->post());
        unset($ExportOrder->order_sn);
        $ExportOrder->save(false);
        Message::result_json(1,'编辑成功');
    }
}
