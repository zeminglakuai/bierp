<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Dictionary;
use app\common\models\DictionaryValue;
use app\includes\Message;
use app\controllers\BaseController;

class DictionaryController extends BaseController
{   
    public $enableCsrfValidation = false;
    public $page_title = '数据字典';    
    public function actionIndex()
    {	
      
      $dictionary_list = Dictionary::find()->all();
  		return $this->render('index', [
                                			'dictionary_list' => $dictionary_list,
  		]);
    }

    public function actionView($id){
      $dictionary = Dictionary::findone($id);
      return $this->render('view', [
                                    'dictionary' => $dictionary,
                                    'id' => $id,
      ]);
    }

    public function actionCreateValue($id){
      $this->layout = 'empty';
      //$dictionary_list = Dictionary::findone($id);
      return $this->render('create',['id'=>$id]);
    }

    public function actionInsertValue($id){
      $dictionary = Dictionary::findone($id);
      if ($dictionary->edit_able < 1) {
          Message::result_json(2,'该字典不允许添加编辑');
      }
      $dictionary_value = new DictionaryValue();
      $dictionary_value->load(Yii::$app->request->post());
      $dictionary_value->save(false);
      Message::result_json(1,'添加成功');
    }

    public function actionDeleteLabel($id,$data_id){
      $Dictionary = Dictionary::find()->where(['id'=>$id])->one();
      $DictionaryValue = DictionaryValue::find()->where(['dictionary_id'=>$id,'id'=>$data_id])->one();

      if ($Dictionary && $DictionaryValue) {
        //检查是不是允许编辑
        if ($Dictionary->edit_able == 1) {

          $DictionaryValue->delete();
          Message::result_json(1,'删除成功');
        }else{
          Message::result_json(2,'该属性不允许编辑');
        }
      }else{
        Message::result_json(2,'参数错误');
      }

    }

    public function actionViewValue($id){
      $this->layout = 'empty';
      //$dictionary_list = Dictionary::findone($id);
      return $this->render('create',['id'=>$id]);
    }

    public function actionUpdateDictionaryValue($id,$data_id){
        $data_type  = trim(Yii::$app->request->get('data_type'));
        $value  = trim(Yii::$app->request->get('value'));
        $id  = Yii::$app->request->get('id');
        $data_id  = Yii::$app->request->get('data_id');
        $allow_arr = ['reutrn_ask_price','return_number'];

        $DictionaryValue = DictionaryValue::find()->where(['dictionary_id'=>$id,'id'=>$data_id])->one();
        if($DictionaryValue){
          $DictionaryValue->$data_type = $value;
          $DictionaryValue->save(false);
          message::result_json(1,'chengg',$value);
        }else{
          message::result_json(2,'没有此记录');
        }
    }
}
