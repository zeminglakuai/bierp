<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Brand;
use app\common\models\Goods;
use app\common\models\Supplier;
use app\common\models\GoodsImplode;
use app\includes\Message;
use app\controllers\BaseController;

class GoodsImplodeController extends BaseController
{   
    public $enableCsrfValidation = false;
    public $page_title = '商品整合';

    public $title_model = 'app\common\models\GoodsImplode';
    public $detail_model = 'app\common\models\GoodsImplodeGoods';
    public $status_label = 'goods_implode_status';

    public function beforeAction($action)
    {
      parent::beforeAction($action);
      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','admit','update','insert-goods','delete-goods','update-goods-label'];
      $admit_allow_arr = ['edit','view','create-materiel'];

      $scope_model = 'app\common\models\GoodsImplode';
      $status_label = 'goods_implode_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }



    public function actionInsert(){
 
        $Brand = new Brand();
        $Brand->load(Yii::$app->request->post());
        
        if (strlen($Brand->brand_name) > 2) {
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
          Message::result_json(2,'客户名称不能为空','','error');
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
