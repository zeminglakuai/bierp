<?php
namespace app\common\actions;

use Yii;
use yii\base\Action;
use app\common\models\Materiel;
use yii\data\Pagination;

class SearchMaterielAction extends Action {
	public $order_id = 0;

    public function run() {

    	$insert_url   = 'insert-materiel';
    	$url          = 'create-materiel';
    	$supplier_url = 'search-supplier';

    	$search_data = Yii::$app->request->get();

		$search_data['sortby'] = !empty(trim(Yii::$app->request->get('sortby')))?trim(Yii::$app->request->get('sortby')):'id';
		$search_data['order'] = trim(Yii::$app->request->get('order')) == 'SORT_ASC'?SORT_ASC:SORT_DESC;

        //得到商品库存
		$goods_list = Materiel::find();

		if($search_data['materiel_name']){
			$goods_list = $goods_list->andwhere(['like','materiel_name',$search_data['materiel_name']]);
		}

		$countQuery = clone $goods_list;
		$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>15,'pageSizeLimit'=>1]);


		$goods_list = $goods_list->offset($pages->offset)
								->orderby([$search_data['sortby']=>$search_data['order']])
								->limit($pages->limit)
								->all();


 
		$this->controller->layout = 'empty';
		return $this->controller->render('/materiel/search-materiel', [
										'goods_list' => $goods_list,
										'pages' => $pages,
										'search_data' => $search_data,
										'insert_url' => $insert_url,
										'url' => $url,
										'order_id' => $this->order_id,

		]);
    }
}