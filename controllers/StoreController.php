<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Store;
use app\common\models\Depart;
use app\includes\Message;
use app\controllers\BaseController;

class StoreController extends BaseController
{   
    public $enableCsrfValidation = false;
    public function actionIndex()
    {	
     
      //得到仓库列表
   		$query = new \yii\db\Query();
  		$store_list = $query->select('*')->from('store')->all();
 
          
  		return $this->render('index', [
                                			'store_list' => $store_list,
  		]);
    }

    //新建仓库
    public function actionCreate(){
        $this->layout = 'empty';
        return $this->render('create', []);
    }

    //添加仓库
    public function actionAddStore(){
      $store = new Store();
      $store->load(Yii::$app->request->post());
      if(!empty($store->store_name)){
        $store->save();
        message::result_json(1,'添加成功');
      }else{
        message::result_json(2,'请填写仓库名');
      }
    }
    
    //编辑仓库
    public function actionView(){
        $id = is_numeric(Yii::$app->request->get('id'))?Yii::$app->request->get('id'):0;
        $Store = Store::findone($id);
        if ($Store) {
            $this->layout = 'empty';
            return $this->render('create', ['store'=>$Store]);
        }else{
            message::show_message('缺少参数',[],'error');
        }
    }

    //仓库
    public function actionDelete($id){
        $Store = Store::findone($id);
        if ($Store) {
            //检查仓库是不是已经有使用
            
            message::show_message('缺少参数',[],'error');
        }else{
            message::show_message('缺少参数',[],'error');
        }
    }
    //编辑仓库
    public function actionEditStore(){
        $id = is_numeric(Yii::$app->request->post('id'))?Yii::$app->request->post('id'):0;
        $store = Store::findone($id);
        if ($store && $store->load(Yii::$app->request->post())) {
            $store->save(false);
            Message::result_json(1,'修改成功');
        }else{
            Message::result_json(2,'参数错误');
        }
    }    
    //删除仓库
    public function actionDeleteStore(){
       $id = is_numeric(Yii::$app->request->get('id'))?Yii::$app->request->get('id'):0;
        $Store = Store::findone($id);
        if ($Store) {
            //检查仓库是不是有使用
            $depart = Depart::find()->where(['store_id'=>$id])->count();
            if($depart){
               Message::result_json(3,'仓库使用中，不允许删除!'); 
            }

            $Store->delete();
            role::deleteAll(['depart_id'=>$id]);
            Message::result_json(1,'删除成功'); 
        }else{
            Message::result_json(2,'仓库不存在');
        }
    }    
 
    //更新仓库信息
    public function actionUpdateStoreInfo(){
        $id = is_numeric(Yii::$app->request->get('id'))?Yii::$app->request->get('id'):0;
        $value = Yii::$app->request->get('value')?trim(Yii::$app->request->get('value')):'';
        $type = Yii::$app->request->get('type')?trim(Yii::$app->request->get('type')):'';

        $allow_edit_arr = ['store_name','store_desc','address','contact','tel'];
        if(!in_array($type, $allow_edit_arr)){
          message::result_json(3,'参数错误',$value);
        }
        $Store = Store::findone($id);
        if (isset($Store->id) || strlen($value) > 0) {

            $Store->$type = $value;
            $Store->save(false);

            message::result_json(1,'修改成功',$value);
        }else{
          message::result_json(2,'缺少参数');
        }
    }
    



}
