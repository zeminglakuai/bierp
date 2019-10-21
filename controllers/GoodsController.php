<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\Url;
use app\common\models\Goods;
use app\common\models\config;
use app\common\models\Category;
use app\common\models\Brand;
use app\common\models\Supplier;
use app\common\models\SupplierGoods;
use app\common\models\ActivityGoods;
use app\common\models\CustomOrderGoods;
use app\common\models\FileInfo;
use app\includes\Message;
use app\includes\Pinyin;

use moonland\phpexcel\Excel;


use yii\helpers\BaseFileHelper;
use yii\web\UploadedFile;
use app\common\models\UploadForm;
use app\common\models\Uploadfile;
use app\controllers\BaseController;

class GoodsController extends BaseController
{   
    public $enableCsrfValidation = false;
	public $page_title = '商品资料';
	public $scope = true;
	public $search_allowed = ['category'=>3];
	public $goods_supplier='';
	
    public function actionIndex()
    {
        $sdata=Yii::$app->request->get();

		$goods_name  = trim(Yii::$app->request->get('goods_name'));
        $type  = is_numeric(Yii::$app->request->get('type'))?Yii::$app->request->get('type'):1;
        $goods_id  = is_numeric(Yii::$app->request->get('goods_id'))?Yii::$app->request->get('goods_id'):0;
		$category  = is_numeric(Yii::$app->request->get('category'))?Yii::$app->request->get('category'):0;
		$brand_id  = is_numeric(Yii::$app->request->get('brand_id'))?Yii::$app->request->get('brand_id'):0;
		$supplier_name  = trim(Yii::$app->request->get('supplier_name',''));
		$purchase_start  = is_numeric(Yii::$app->request->get('purchase_start'))?Yii::$app->request->get('purchase_start'):'';
		$purchase_end  = is_numeric(Yii::$app->request->get('purchase_end'))?Yii::$app->request->get('purchase_end'):'';
		$market_start  = is_numeric(Yii::$app->request->get('market_start'))?Yii::$app->request->get('market_start'):'';
		$market_end  = is_numeric(Yii::$app->request->get('market_end'))?Yii::$app->request->get('market_end'):'';

		$sale_start  = is_numeric(Yii::$app->request->get('sale_start'))?Yii::$app->request->get('sale_start'):'';
		$sale_end  = is_numeric(Yii::$app->request->get('sale_end'))?Yii::$app->request->get('sale_end'):'';

		$sortby = !empty(trim(Yii::$app->request->get('sortby')))?trim(Yii::$app->request->get('sortby')):'goods_id';
		$order = trim(Yii::$app->request->get('order')) == 'SORT_ASC'?SORT_ASC:SORT_DESC;

		$export = is_numeric(Yii::$app->request->get('export'))?Yii::$app->request->get('export'):0;

        //得到商品库存
		$query = new \yii\db\Query();
		$goods_list = $query->select('g.*,b.*')
							->from('goods as g')
							->leftjoin('brand as b','b.id = g.brand_id')
							->where(['or','g.goods_status = 1','g.add_user_id = '.Yii::$app->session['manage_user']['id']]);
		/*获取滞销时间*/

        if($goods_id){
            $goods_list = $goods_list->andwhere(['goods_id'=>$goods_id]);
        }

        $goods_list = $query->andwhere(['g.type'=>$type]);
        $sdata['type']=$type;
		if($category){
			//得到分类的子分类
			$children_cate_id = Category::cat_children_str($category);
			$goods_list = $query->andwhere(['IN','g.cat_id',$children_cate_id]);

        }

		if($goods_name){
			$goods_list = $query->andwhere(['like','g.goods_name',$goods_name]);
		}

		if($brand_id){
			$goods_list = $query->andwhere(['g.brand_id'=>$brand_id]);
            $brand = Brand::findone($brand_id);
		}

		if($supplier_name){
			$goods_list = $query->andwhere(['like','g.supplier_name',$supplier_name]);
		}

        if($purchase_start){
            $goods_list = $query->andwhere(['>','g.supplier_price',$purchase_start]);
        }

        if($purchase_end){
            $goods_list = $query->andwhere(['<','g.supplier_price',$purchase_end]);

        }

		if ($market_start > 0) {
			$goods_list = $query->andwhere(['>','market_price',$market_start]);

        }

		if ($market_end > 0) {
			$goods_list = $query->andwhere(['<','market_price',$market_end]);

        }

		if ($sale_start > 0) {
			$goods_list = $query->andwhere(['>','shop_price',$sale_start]);

        }

		if ($sale_end > 0) {
			$goods_list = $query->andwhere(['<','shop_price',$sale_end]);
		}

		$countQuery = clone $query;
		$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>15,'pageSizeLimit'=>1]);

		if ($export == 1) {
			$goods_list = $query->orderby([$sortby=>$order])
								->all();

		}else{
			$goods_list = $query->offset($pages->offset)
							->orderby([$sortby=>$order])
							->limit($pages->limit)
							->all();
		}


		//得到商品分类 并分级
		$cat_list = Category::cat_list(0, $category);
		// /得到供货商列表
		//$su_list = Supplier::get_select_list();
        if ($goods_list){
            foreach ($goods_list as $key=>$val){
                $data = Yii::$app->db->createCommand("select s.goods_id,s.out_time,s.add_time,s.number,se.store_name from stock s left join store se on se.id=s.store_id where s.number>0 and goods_id=".$val['goods_id'])->queryAll();

                foreach ($data as $k=>$v){
                    if ($v['out_time']>0){
                        $data[$k]['time']=ceil((time() - $v['out_time']) / 86400);
                    }else{
                        $data[$k]['time']=ceil((time() - $v['add_time'])/86400);
                    }
                }
                $goods_list[$key]['data']=$data;

                $goods_list[$key]['goods_supplier']= Yii::$app->db->createCommand("select gs.goods_supplier_id,s.supplier_name,s.tel,s.contact,gs.supplier_price from goods_supplier gs left join goods g on g.supplier_id=gs.supplier_id left join supplier s on s.id=gs.supplier_id where gs.goods_id=".$val['goods_id'])->queryAll();
                $goods_list[$key]['goods_platform']=Yii::$app->db->createCommand("SELECT gp.goods_platform_id, gp.platform_id,gp.enddate,gp.startdate, p.plat_name,p.contract_start_time,p.contract_end_time,p.contract_contact, p.contract_tel FROM goods_platform as gp LEFT JOIN goods as g ON g.goods_id=gp.goods_id LEFT JOIN platform as p ON  p.id =gp.platform_id WHERE gp.goods_id =".$val['goods_id'])->queryAll();
            }
        }


		// /得到品牌列表
		$brand_list = Brand::get_select_list();
		return $this->render('index', [
										'goods_list' => $goods_list,
										'cat_list' => $cat_list,
										'brand_list' => $brand_list,
										'su_list' => $su_list,
										'pages' => $pages,
										'goods_name' => $goods_name,
                                        'brand_id' => $brand_id,
										'brand' => $brand,
										'category' => $category,
										'supplier_name' => $supplier_name,
										'supplier_info' => $supplier_info,
										'purchase_start' =>$purchase_start,
										'purchase_end' =>$purchase_end,
										'market_start' =>$market_start,
										'market_end' =>$market_end,
										'sortby' =>$sortby,
										'order' =>$order,
                                        'search_data'=>$sdata,
                                        'type'=>$type,

		]);
    }

    //查看/编辑商品
    public function actionView(){
    	$goods_id = is_numeric(Yii::$app->request->get('goods_id'))?Yii::$app->request->get('goods_id'):0;
    	if ($goods_id) {
    		$goods = Goods::findone($goods_id);
            $query = new \yii\db\Query();
            $supp_list = $query->select('tel,contact')
                ->from('supplier')
                ->where(['id'=>$goods['supplier_id']])
                ->one();
            $platform=Yii::$app->db->createCommand("SELECT * FROM platform")->queryAll();

    		//得到商品分类 并分级
			$cat_list = Category::cat_list(0, $goods->cat_id);
			$goods_list['goods_supplier']= Yii::$app->db->createCommand("select gs.goods_supplier_id,s.supplier_name,s.tel,s.contact,gs.supplier_price,gs.supplier_id from goods_supplier gs left join goods g on g.supplier_id=gs.supplier_id left join supplier s on s.id=gs.supplier_id where gs.goods_id=".$goods_id)->queryAll();
			$goods_list['goods_platform']=Yii::$app->db->createCommand("SELECT gp.goods_platform_id, gp.platform_id,gp.startdate,gp.enddate, gp.daifa , p.plat_name,p.contract_start_time,p.contract_end_time,p.contract_contact, p.contract_tel FROM goods_platform as gp LEFT JOIN goods as g ON g.goods_id=gp.goods_id LEFT JOIN platform as p ON  p.id =gp.platform_id WHERE gp.goods_id =".$goods_id)->queryAll();
	
	      	return $this->render('view', [
	        					'cat_list' => $cat_list,
					            'oparate' => 'update',
					            'goods' => $goods,
                                'supp_list' => $supp_list,
                                'platform' => $platform,
					            'supplier_price'=>$supplier_price,
                'goods_supplier'=>$goods_list['goods_supplier'],
                'goods_platform'=>$goods_list['goods_platform'],


	      	]);
    	}else{
    		message::show_message('商品不存在',[],'error');
    	}
    }

    //套餐添加商品
    public function actionViewt(){
        $goods_id = is_numeric(Yii::$app->request->get('goods_id'))?Yii::$app->request->get('goods_id'):0;
        if ($goods_id) {
            $goods = Goods::findone($goods_id);
            $query = new \yii\db\Query();
            $supp_list = $query->select('tel,contact')
                ->from('supplier')
                ->where(['id'=>$goods['supplier_id']])
                ->one();

            //得到商品分类 并分级
            $cat_list = Category::cat_list(0, $goods->cat_id);

            return $this->render('viewt', [
                'cat_list' => $cat_list,
                'goods_list' => $goods_list,
                'oparate' => 'update',
                'goods' => $goods,
                'supp_list' => $supp_list,
                'supplier_price'=>$supplier_price,
                'goods_id'=>$goods_id,

            ]);
        }else{
            message::show_message('商品不存在',[],'error');
        }
    }

    //查看/编辑商品
    public function actionEdit(){
    	$this->layout = 'empty';
    	$goods_id = is_numeric(Yii::$app->request->get('id'))?Yii::$app->request->get('id'):0;
    	if ($goods_id) {
    		$goods = Goods::findone($goods_id);

    		//得到商品分类 并分级
			$cat_list = Category::cat_list(0, $goods->cat_id);

	      	return $this->render('view', [
	        					'cat_list' => $cat_list,
					            'oparate' => 'update',
					            'goods' => $goods,
					            'supplier_price'=>$supplier_price,
	      	]);
    	}else{
    		message::show_message('商品不存在',[],'error');
    	}
    }

    //编辑商品
    public function actionUpdate(){

    	$supplier       = Yii::$app->request->post('supplier');
    	$platform= Yii::$app->request->post('platform');
    	$purchase_price = Yii::$app->request->post('purchase_price');
    	$goods_id = is_numeric(Yii::$app->request->post('goods_id'))?Yii::$app->request->post('goods_id'):0;
    	if ($goods_id) {
    		$goods = Goods::findone($goods_id);

    		$goods->load(Yii::$app->request->post());

            $ppt_file = UploadedFile::getInstance($goods, 'ppt_file');
            if ($ppt_file) {
                $filed_name = 'uploads/'.date('Ym');
                BaseFileHelper::createDirectory($filed_name);
                $new_img_name = $filed_name.'/'.Yii::$app->security->generateRandomString(20) . '.' . $ppt_file->extension;
                $ppt_file->saveAs($new_img_name);
                $goods->ppt_file = $new_img_name;
            }

	    	if (empty($goods->goods_name)) {
	    		Message::show_message('请填写商品名称',[],'error');
	    	}

	    	
	    	if (empty($goods->goods_sn)) {
	    		Message::show_message('请填写商品序号',[],'error');
	    	}else{
	    		//检查goods_sn是不是重复
	    		$check_goods = Goods::find()->where(['goods_sn'=>$goods->goods_sn])->one();
	    		if (isset($check_goods->goods_id) && $check_goods->goods_id <> $goods->goods_id) {
	    			Message::show_message('商品货号重复',[],'error');
	    		}
	    	}

            if (empty($goods->cat_id)) {
                Message::show_message('请选择商品分类',[],'error');
            }

            if (empty($goods->brand_id)) {
                Message::show_message('请选择商品品牌',[],'error');
            }
            if (empty($goods->market_price)) {
                Message::show_message('请填写市场价格',[],'error');
            }
	    	$goods->goods_py = Pinyin::encode($goods->goods_name, 'head','utf8');
	    	$goods->shop_price = is_numeric($goods->shop_price)?$goods->shop_price:0;
	    	$goods->market_price = is_numeric($goods->market_price)?$goods->market_price:0;
	    	$goods->goods_status = 0;
	    	/*if ($goods->supplier_id) {
	    		$supplier = Supplier::findone($goods->supplier_id);
	    		$goods->supplier_name = $supplier->supplier_name;
	    	}*/
	    	//如果是自营产品 则必须填写售价
	    	if ($goods->is_self_sell > 0) {
	    		if ($goods->is_self_sell <= 0) {
	    			Message::show_message('自营产品必须填写售价',[],'error');
	    		}
	    	}

            $transaction = Yii::$app->db->beginTransaction();

	    	try{
                $goods->save(false);
                foreach ($platform as $key=>$val){
                    if ($val['goods_platform_id']!=''){
                        if ($val['platform_id']!=''){
                            Yii::$app->db->createCommand("update goods_platform set goods_id=".$goods_id." , platform_id=".$val['platform_id'].", enddate='".$val['enddate']."' , startdate='".$val['startdate']."', daifa=".$val['daifa']." where goods_platform_id=".$val['goods_platform_id'])->execute();
                        }else{
                            Yii::$app->db->createCommand(" DELETE FROM goods_platform  where goods_platform_id=".$val['goods_platform_id'])->execute();
                        }
                    }else{
                        if ($val['platform_id']!=''){

//                        Yii::$app->db->createCommand("insert into goods_supplier goods_id=".$goods_id." and supplier_id=".$val['supplier_id']." and supplier_price=".$val['supplier_price'])->execute();
                            Yii::$app->db->createCommand("insert into goods_platform (goods_id,platform_id,startdate,enddate,daifa) values ($goods_id,".$val['platform_id'].",'".$val['startdate']."','".$val['enddate']."',".$val['daifa'].")")->execute();
                        }
                    }
             }
                foreach ($supplier as $k=>$v){
                    if ($v['goods_supplier_id']!=''){
                        if ($v['supplier_id']!=''&&$v['supplier_price']!=''){
                            Yii::$app->db->createCommand("update goods_supplier set goods_id=".$goods_id." , supplier_id=".$v['supplier_id']." , supplier_price=".$v['supplier_price']." where goods_supplier_id=".$v['goods_supplier_id'])->execute();
                        }else{
                            Yii::$app->db->createCommand(" DELETE FROM goods_supplier  where goods_supplier_id=".$v['goods_supplier_id'])->execute();
                        }

                    }else{
                        if ($v['supplier_id']!=''&&$v['supplier_price']!=''){

//                        Yii::$app->db->createCommand("insert into goods_supplier goods_id=".$goods_id." and supplier_id=".$val['supplier_id']." and supplier_price=".$val['supplier_price'])->execute();
                            Yii::$app->db->createCommand("insert into goods_supplier (goods_id,supplier_id,supplier_price) values ($goods_id,".$v['supplier_id'].",".$v['supplier_price'].")")->execute();
                        }
                    }
                }
                $transaction->commit();
	    	}catch (Exception $e){
                $transaction->rollBack();
            }



	    	$upload_result = UploadForm::upload_files();
	        if ($upload_result && is_array($upload_result)) {
	          if (count($upload_result['file']) > 0) {
	            foreach ($upload_result['file'] as $key => $value) {
	              $FileInfo = new FileInfo();
	              $FileInfo->belong_id = $goods->goods_id;
	              $FileInfo->file_path = $value['file_name'];
	              $FileInfo->file_desc = $value['file_desc'];
	              $FileInfo->model = 'goods';              
	              $FileInfo->save(false);
	            }
	          }
	        }

	    	message::show_message('更新成功',[]);
    	}else{
    		message::show_message('商品不存在',[],'error');
    	}
    }

    //添加商品
    public function actionCreate(){
        $type= Yii::$app->request->get('type');

        //分类列表
    	//得到商品分类 并分级
		$cat_list = Category::cat_list(0, $category);
      	//品牌列表
		$brand_list = Brand::get_select_list();
		//得到供货商列表
		//$su_list = Supplier::get_select_list();

      	return $this->render('view', [
        					'cat_list' => $cat_list,
        					'brand_list' => $brand_list,
				            'oparate' => 'insert',
            'type'=>$type,
      	]);
    }

    //添加商品
    public function actionInsert(){

    	$supplier       = Yii::$app->request->post('supplier');
    	$type       = Yii::$app->request->post('type');
    	$purchase_price = Yii::$app->request->post('purchase_price');

 		$goods = new Goods();
 		$goods->load(Yii::$app->request->post());

        $ppt_file = UploadedFile::getInstance($goods, 'ppt_file');
        if ($ppt_file) {
            $filed_name = 'uploads/'.date('Ym');
            BaseFileHelper::createDirectory($filed_name);
            $new_img_name = $filed_name.'/'.Yii::$app->security->generateRandomString(20) . '.' . $ppt_file->extension;
            $ppt_file->saveAs($new_img_name);
            $goods->ppt_file = $new_img_name;
        }
        $goods->type=$type;
    	if (empty($goods->goods_name)) {
    		Message::show_message('请填写商品名称',[],'error');
    	}

    	if (empty($goods->type)) {
            $goods->type=1;
            $links = [['link_name'=>'返回列表','link_url'=>URL::to(['/goods'])]];
    	}else{
            $links = [['link_name'=>'返回列表','link_url'=>URL::to(['/goods','type'=>$type])]];
        }


    	if (empty($goods->goods_sn)) {
    		Message::show_message('请填写商品型号',[],'error');
    	}else{
    		//检查goods_sn是不是重复
    		$check_goods = Goods::find()->where(['goods_sn'=>$goods->goods_sn])->one();
    		if (isset($check_goods->goods_id)) {
    			Message::show_message('商品货号重复',[],'error');
    		}
    	}

        if (empty($goods->cat_id)) {
            Message::show_message('请选择商品分类',[],'error');
        }

        if (empty($goods->brand_id)) {
            Message::show_message('请选择商品品牌',[],'error');
        }


        if (empty($goods->market_price)) {
            Message::show_message('请填写市场价格',[],'error');
        }
        $goods->default_supplier_id='';
        $goods->default_supplier_name='';
        $goods->goods_desc='';

    	if ($goods->supplier_id) {
    		$supplier = Supplier::findone($goods->supplier_id);
    		$goods->supplier_name = $supplier->supplier_name;
    	}

    	//如果是自营产品 则必须填写售价
    	if ($goods->is_self_sell > 0) {
    		if ($goods->is_self_sell <= 0) {
    			Message::show_message('自营产品必须填写售价',[],'error');
    		}
    	}

    	$goods->goods_py = Pinyin::encode($goods->goods_name, 'head','utf8');
    	$goods->shop_price = is_numeric($goods->shop_price)?$goods->shop_price:0;
    	$goods->market_price = is_numeric($goods->market_price)?$goods->market_price:0;
    	$goods->add_time = time();
    	$goods->add_user_id = Yii::$app->session['manage_user']['id'];
    	$goods->add_user_name = Yii::$app->session['manage_user']['admin_name'];
        $goods->goods_status=0;


    	$goods->save(false); 

	    $upload_result = UploadForm::upload_files();
        if ($upload_result && is_array($upload_result)) {
          if (count($upload_result['file']) > 0) {
            foreach ($upload_result['file'] as $key => $value) {
              $FileInfo = new FileInfo();
              $FileInfo->belong_id = $goods->goods_id;
              $FileInfo->file_path = $value['file_name'];
              $FileInfo->file_desc = $value['file_desc'];
              $FileInfo->model = 'goods';              
              $FileInfo->save(false);
            }
          }
        }
		Message::show_message('添加成功',$links);
           
    }

    public function actionDelete(){

        $id  = Yii::$app->request->get('goods_id');
        $result = array('error'=>0,'message'=>'','content'=>'');
        $goods_info = Goods::findone($id);
        if($goods_info){
           $goods_info->goods_status = 2;
           $goods_info->save(false);

           message::show_message('操作成功');
        }else{
           message::show_message('不存在该商品！','','error');
        }

    }

    public function actionSearchGoods(){
    	$this->module->layout = 'main22';
    	$goods_key = !empty(Yii::$app->request->get('goods_key'))?trim(Yii::$app->request->get('goods_key')):'';
    	$activity_id = is_numeric(Yii::$app->request->get('activity_id'))?Yii::$app->request->get('activity_id'):01;
        if (empty($goods_key)) {
        	message::result_json(3,'查询关键字为空');
        }
        if ($activity_id <= 0) {
        	message::result_json(4,'参数错误，刷新后再提交');
        }
        $goods_list = Goods::find()->where(['like','goods_name',$goods_key])->orwhere(['like','goods_sn',$goods_key])->Asarray()->limit(20)->all();

        if(count($goods_list) > 0){

           $content = $this->render('search-goods',['goods_list'=>$goods_list,'activity_id'=>$activity_id]);

           message::result_json(1,'查询成功',$content);

        }else{
           message::result_json(2,'没有查询到任何商品');
        }
    }

    public function actionSearchSupplier($q){
    	$query = new \yii\db\Query();
    	$supp_list = $query->select('id,supplier_name as name')
							->from('supplier')
							->where(['like','supplier_name',$q])
							->orwhere(['like','simple_name',$q])
							->all();
		die(json_encode($supp_list));
    }

    public function actionCheckGoodsSn(){
    	$sn = Yii::$app->request->get('sn','');
    	$goods_id = Yii::$app->request->get('goods_id',0);

    	$query = new \yii\db\Query();
    	$query->select('id')
							->from('goods')
							->where(['goods_sn'=>$sn]);
		
		$if_goods_sn = 	$query->one();
		if ($if_goods_sn) {
			message::result_json(1,'存在同货号商品');
		}else{
			message::result_json(2,'safe');
		}
    }

    //
    public function actionAdmitList(){

		$confirm_list = Goods::find()->where(['goods_status'=>0])->all();

      	return $this->render('confirm-list', [
        					'confirm_list' => $confirm_list,
      	]);
    }

    public function actionAdmit($id){
    	$goods = Goods::findone($id);
    	if ($goods) {
    		$goods->goods_status = 1;
    		$goods->save(false);
    		message::result_json(1,'操作成功');
    	}else{
    		message::result_json(2,'不存在该商品！');
    	}
    }

    //widget 待审核商品列表
    public function actionWidgetGoods(){
		$this->layout = false;
		//查询待审核商品
		//查询总的商品数量
		//
      	return $this->render('widget-goods', []);
    }

    public function actionDeleteImg($id){
      $FileInfo = FileInfo::findone($id);
      if ($FileInfo) {
        $FileInfo->delete();
        message::result_json(1,'删除成功!');
      }else{
        message::result_json(2,'找不到数据!');
      }
    }

    public function actionUploadImg(){
    	$goods = new Goods();
    	$image = UploadedFile::getInstance($goods, 'goods_img');
        if ($image) {
			$filed_name = 'uploads/'.date('Ym');
            BaseFileHelper::createDirectory($filed_name);
            $new_img_name = $filed_name.'/'.Yii::$app->security->generateRandomString(20) . '.' . $image->extension;
            $image->saveAs($new_img_name);
            $goods_img = $new_img_name;
    	}else{
    		message::result_json(2,'错误');
    	}

		message::result_json(1,'success',$goods_img);
    }


    public function actionCustomOrderGoods($goods_id){
        $this->layout = 'empty';
        $goods_list = CustomOrderGoods::find()->where(['goods_id'=>$goods_id])->all();

        return $this->render('custom_order_goods', [
                                        'goods_list' => $goods_list,
                                        'goods_id' => $goods_id,

        ]);
    }

	// 获取有效期最后一个字符
	public function getLastUnit($str){
		return mb_substr($str,-1,1,"UTF-8");
	}

    public function getUnit($can){
        $s='';
        switch ($can) {
            case 7:
                $s='秒';
                break;
            case 8:
                $s='分';
                break;
            case 9:
                $s='时';
                break;
            case 10:
                $s='天';
                break;
            case 11:
                $s='周';
                break;
            case 12:
                $s='月';
                break;
            case 13:
                $s='年';
                break;
            case '秒':
                $s=7;
                break;
            case '分':
                $s=8;
                break;
            case '时':
                $s=9;
                break;
            case '天':
                $s=10;
                break;
            case '周':
                $s=11;
                break;
            case 12:
                $s='月';
                break;
            case '年':
                $s=13;
                break;
        }
        return $s;
    }




}
