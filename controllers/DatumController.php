<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\Url;
use app\common\models\Datum;
use app\common\models\DatumCate;
use app\common\models\DatumFile;
use app\common\models\FileInfo;
use app\common\models\Depart;
use app\includes\Message;
use app\includes\Pinyin;
use yii\web\UploadedFile;
use app\common\models\UploadForm;
use app\controllers\BaseController;

class DatumController extends BaseController
{   
    public $enableCsrfValidation = false;
    public $page_title = '基础合同';
    
    public function actionIndex()
    {
      $init_condition = [['is_delete'=>0],['like','scope',','.Yii::$app->session['manage_user']['depart_id'].','],['is_private'=>0]];

      $init_condition = [['is_delete'=>0],['or',['like','scope',','.Yii::$app->session['manage_user']['depart_id'].','],['like','scope',',0,']],['is_private'=>0]];

      if (Yii::$app->session['manage_user']['role']->role_type == 99) {
        $init_condition = [['is_delete'=>0],['is_private'=>0]];
      }

		  return $this->render('index', ['init_condition'=>$init_condition]);
    }

    //查看/编辑
    public function actionView($id){
        $this->layout = 'empty';
        //分类列表
        $Datum = Datum::findone($id);

        return $this->render('preview', [
                            'datum' => $Datum,
                            
        ]);
    }

    //查看/编辑
    public function actionEdit($id){
        $this->layout = 'empty';
        $Datum = Datum::findone($id);

        //分类列表
        $cat_list = DatumCate::datum_cat_list(0,$Datum->cat_id);
        return $this->render('view', [
                            'datum' => $Datum,
                            'cat_list' => $cat_list,
                            
        ]);
    }

    //编辑
    public function actionUpdate($id){
        $Datum = Datum::findone($id);
        $Datum->load(Yii::$app->request->post());

        $scope = Yii::$app->request->post('scope');

        // if (count($scope) == 0) {
        //     Message::result_json(2,'请选择文件查看权限');
        // }else{
        //     $scope_str = ',';
        //     foreach ($scope as $key => $value) {
        //        $scope_str .= $value.',';
        //     }
        //     $Datum->scope = $scope_str;
        // }

        // if (!$Datum->cat_id) {
        //   Message::result_json(2,'请选择文件分类');
        // }

        if (strlen($Datum->datum_name) < 2) {
          Message::result_json(2,'文件名称不能为空');
        }

        $Datum->save(false);

        $upload_result = UploadForm::upload_files();

        if ($upload_result && is_array($upload_result)) {
          if (count($upload_result['file']) > 0) {
            foreach ($upload_result['file'] as $key => $value) {
              $FileInfo = new FileInfo();
              $FileInfo->belong_id = $Datum->id;
              $FileInfo->file_path = $value['file_name'];
              $FileInfo->file_desc = $value['file_desc'];
              $FileInfo->model = 'datum';
              $FileInfo->add_time = time();
              $FileInfo->save(false);
            }
          }
        }

        if (isset($upload_result['error']) && count($upload_result['error']) > 0) {
          $upload_info = '';
          foreach ($upload_result['error'] as $key => $value) {
            $upload_info .=  $value.'<br>';
          }
        }

        Message::result_json(1,'编辑成功'.$upload_info);
    }

    //添加 
    public function actionCreate(){
        $this->layout = 'empty';
      	//分类列表
		    $cat_list = DatumCate::datum_cat_list(0);

      	return $this->render('view', ['cat_list'=>$cat_list]);
    }

    //添加
    public function actionInsert(){
        $Datum = new Datum();
        $Datum->load(Yii::$app->request->post());

        $scope = Yii::$app->request->post('scope');

        // if (count($scope) == 0) {
        //     Message::result_json(2,'请选择文件查看权限');
        // }else{
        //     $scope_str = ',';
        //     foreach ($scope as $key => $value) {
        //        $scope_str .= $value.',';
        //     }
        //     $Datum->scope = $scope_str;
        // }

        // if (!$Datum->cat_id) {
        //   Message::result_json(2,'请选择文件分类');
        // }

        if (strlen($Datum->datum_name) < 2) {
          Message::result_json(2,'文件名称不能为空');
        }

        $Datum->save(false);

        $upload_result = UploadForm::upload_files();
        if ($upload_result && is_array($upload_result)) {
          if (count($upload_result['file']) > 0) {
            foreach ($upload_result['file'] as $key => $value) {
              $FileInfo = new FileInfo();
              $FileInfo->belong_id = $Datum->id;
              $FileInfo->file_path = $value['file_name'];
              $FileInfo->file_desc = $value['file_desc'];
              $FileInfo->model = 'datum';
              $FileInfo->add_time = time();
              $FileInfo->save(false);
            }
          }
        }

        if (isset($upload_result['error']) && count($upload_result['error']) > 0) {
          $upload_info = '';
          foreach ($upload_result['error'] as $key => $value) {
            $upload_info .=  $value.'<br>';
          }
        }

        Message::result_json(2,'添加成功'.$upload_info);   
    }

    public function actionDelete($id){
        $datum = Datum::findone($id);

        $datum->is_delete = 1;
        if ($datum->save(false)) {
          Message::result_json(1,'删除成功！');
        }else{
          print_r($datum->errors);
          Message::result_json(2,'删除失败！');
        }
        
    }
  
}
