<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Category;
use app\common\models\Goods;
use app\includes\Message;
use app\controllers\BaseController;

class CategoryController extends BaseController
{   
    public $enableCsrfValidation = false;
    public function actionIndex()
    {	

      //得到商品分类
  		$cat_list = Category::cat_list(0,0,false);
      $cat_select_list = Category::cat_list(0,0,true);
  		return $this->render('index', [
                                			'cat_list' => $cat_list,
                                      'cat_select_list' => $cat_select_list,
  		]);
    }

    public function actionCreate(){

      $this->layout = 'empty';
      $cat_select_list = Category::cat_list(0,0,true);

      return $this->render('create', [
            'cat_select_list' => $cat_select_list,
      ]);
    }

    public function actionAdd(){

        $cat_name  = trim(Yii::$app->request->post('cat_name'));
        $cat_id  = is_numeric(Yii::$app->request->post('cat_id'))?Yii::$app->request->post('cat_id'):0;


        if(strlen($cat_name) > 2){
          $parent_cat = Category::findone($cat_id);
          $ima_grade = 0;

          if (isset($parent_cat->cat_id)) {
              $ima_grade = $parent_cat->grade + 1;
          }

          $cat = new Category();
          $cat->cat_name  = $cat_name;
          $cat->parent_id = $cat_id;
          $cat->grade     = $ima_grade;
            $cat->style ='';
            $cat->pic ='';

          $cat->save(false);

          Message::result_json(1,'添加成功');

        }else{
          Message::result_json(2,'分类信息不全');
        }
    }

    public function actionUpdateCatename(){

        $cat_name  = trim(Yii::$app->request->get('cate_name'));
        $id  = Yii::$app->request->get('id');
        if (empty($cat_name)) {
          Message::result_json(2,'分类名称不允许为空');
        }
        $result = array('error'=>0,'message'=>'','content'=>'');

        $cate = Category::findone($id);
        if($cate){
            $cate->cat_name = $cat_name;
            $cate->save(false);
            $result = array('error'=>1,'message'=>'修改成功','content'=>$cat_name);
            die(json_encode($result));
        }else{
           $result = array('error'=>2,'message'=>'没有此记录','content'=>'');
           die(json_encode($result));
        }
    }

    public function actionUpdateSort(){

        $sort_order  = trim(Yii::$app->request->get('sort_order'));
        $id  = Yii::$app->request->get('id');
 
        $result = array('error'=>0,'message'=>'','content'=>'');

        $cate = Category::findone($id);
        if($cate){
            $cate->sort_order = $sort_order;
            $cate->save(false);
            $result = array('error'=>1,'message'=>'修改成功','content'=>$sort_order);
            die(json_encode($result));
        }else{
           $result = array('error'=>2,'message'=>'没有此记录','content'=>'');
           die(json_encode($result));
        }
    }


    public function actionDeleteCate(){

        $id  = Yii::$app->request->get('id');
        //检查商品 是不是有使用该品牌

        $goods = Goods::find()->where(['cat_id'=>$id])->one();
        if (isset($goods->goods_id)) {
            Message::show_message('该分类已经有商品使用，不允许删除',[],'error');
        }

        //jiancha 是不是 有 子分类
        $cate = Category::find()->where(['parent_id'=>$id])->one();
        if (isset($cate->cat_id)) {
            Message::show_message('该分类存在子分类，请先删除子分类',[],'error');
        }

        $cate = Category::findone($id);
        if($cate){
          $cate->delete(false);
          $result = array('error'=>1,'message'=>'修改成功','content'=>$press);
          Message::show_message('删除成功');
        }else{
          Message::show_message('该分类不存在',[],'error');
        }
    }

    public function actionEdit($id){
      $this->layout = 'empty';
      $cate = Category::findone($id);

      $cat_select_list = Category::cat_list(0,$cate->parent_id,true);
      

      return $this->render('create', [
            'cat_select_list' => $cat_select_list,
            'cate' => $cate,
      ]);
    }

    public function actionUpdate($id){
        $cat_name  = trim(Yii::$app->request->post('cat_name'));
        $cat_id  = is_numeric(Yii::$app->request->post('cat_id'))?Yii::$app->request->post('cat_id'):0;

        $cate = Category::findone($id);

        $children_str = Category::cat_children_str($id);

        if (in_array($cat_id, $children_str)) {
          Message::result_json(2,'自身不能是自身的上级');
        }

        if(strlen($cat_name) > 2){
          $parent_cat = Category::findone($cat_id);

          $cate->cat_name  = $cat_name;
          $cate->parent_id = $cat_id;

          $cate->save(false);

          Message::result_json(1,'编辑成功');

        }else{
          Message::result_json(2,'分类信息不全');
        }
    }
}
