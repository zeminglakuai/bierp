<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Brand;
use app\common\models\Goods;
use app\common\models\Supplier;
use app\common\models\BrandSupplier;
use app\includes\Message;
use app\controllers\BaseController;

class BrandController extends BaseController
{   
    public $enableCsrfValidation = false;
    public function actionIndex(){
      $brand_name = Yii::$app->request->get('brand_name');
      $supplier_id = Yii::$app->request->get('supplier_id');

      $data_list = Brand::find();

      if ($brand_name) {
        $data_list = $data_list->andwhere(['like','brand.brand_name',$brand_name]);
      }

      if ($supplier_id) {
        $data_list = $data_list->joinwith('brandSupplier')->andwhere(['brand_supplier.supplier_id'=>$supplier_id]);
      }

      $countQuery = clone $data_list;
      $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>10,'pageSizeLimit'=>1]);

      $data_list = $data_list->offset($pages->offset)
                              ->orderby('id desc')
                              ->limit($pages->limit)
                              ->all();

      return $this->render('index',[
                                      'data_list'=>$data_list,
                                      'pages'=>$pages,
                                      'search_data' => $search_data,
                                      ]
          );
    }

    public function actionInsert(){
 
        $Brand = new Brand();
        $Brand->load(Yii::$app->request->post());
        
        if (strlen($Brand->brand_name) > 2) {
          //检查品牌名称是否重复
          $if_excited = Brand::find()->where(['brand_name'=>$Brand->brand_name])->one();
          if ($if_excited) {
            Message::result_json(2,'品牌名称重复');
          }
          $Brand->save(false);

          $supplier = Yii::$app->request->post('Supplier');
          if ($supplier) {
            foreach ($supplier as $key => $value) {
              $brand_supplier = new BrandSupplier();
              $brand_supplier->brand_id = $Brand->id;
              $brand_supplier->supplier_id = $value;
              $brand_supplier->save(false);
            }
          }

          Message::result_json(1,'添加成功');
        }else{
          Message::result_json(2,'品牌名称不能为空');
        }
    }

    public function actionEdit($id){
      $this->layout = 'empty';
      $brand = Brand::findone($id);
      if(isset($brand->id)){

        return $this->render('create', [
                                    'brand' => $brand,
        ]);
      }else{
        Message::show_message('信息不全',[],'error');
      }
    }

    public function actionUpdate($id){

        $brand = Brand::findone($id);
        if(isset($brand->id)){
          $brand->load(Yii::$app->request->post());

          //检查品牌名称是否重复
          $if_excited = Brand::find()->where(['brand_name'=>$brand->brand_name])->andwhere(['<>','id',$brand->id])->one();
          if ($if_excited) {
            Message::result_json(2,'品牌名称重复');
          }

          $brand->save(false);

          $supplier = Yii::$app->request->post('Supplier');
          if ($supplier) {
            BrandSupplier::deleteAll('brand_id = '.$id);
            $supplier = array_unique($supplier);
            foreach ($supplier as $key => $value) {
              if ($value > 0) {
                $brand_supplier = new BrandSupplier();
                $brand_supplier->brand_id = $brand->id;
                $brand_supplier->supplier_id = $value;
                $brand_supplier->save(false);
              }
            }
          }

          Message::result_json(1,'更新成功');
        }else{
          Message::show_message(2,'参数错误');
        }
    }


    public function actionUpdateBrandName(){

        $brand_name  = trim(Yii::$app->request->get('brand_name'));
        $id  = Yii::$app->request->get('id');
        
        $result = array('error'=>0,'message'=>'','content'=>'');

        $brand = Brand::findone($id);
        if($brand){
            $brand->brand_name = $brand_name;
            $brand->save(false);
            $result = array('error'=>1,'message'=>'修改成功','content'=>$brand_name);
            die(json_encode($result));
        }else{
           $result = array('error'=>2,'message'=>'没有此记录','content'=>'');
           die(json_encode($result));
        }
    }

    public function actionDelete($id){
        $goods = Goods::find()->where(['brand_id'=>$id])->one();
        if (isset($goods->goods_id)) {
          Message::result_json(2,'该品牌已经有商品使用，不允许删除');
        }
        $brand = Brand::findone($id);
        if($brand){
          $brand->delete(false);

          BrandSupplier::deleteAll('brand_id = '.$id);
          $result = array('error'=>1,'message'=>'修改成功','content'=>$press);
          Message::result_json(1,'删除成功');
        }else{
          Message::result_json(2,'该品牌不存在');
        }
    }
}
