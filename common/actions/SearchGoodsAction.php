<?php

namespace app\common\actions;

use Yii;
use yii\base\Action;
use app\common\models\Goods;
use app\common\models\SupplierGoods;
use app\common\models\Supplier;
use app\common\models\Category;
use app\common\models\Brand;

use yii\data\Pagination;


class SearchGoodsAction extends Action
{
    public $order_id = 0;

    public function run()
    {
        $insert_url   = 'insert-goods';
        $url          = 'create-goods';
        $supplier_url = 'search-supplier';
        $search_data = Yii::$app->request->get();


        $search_data['sortby'] = !empty(trim(Yii::$app->request->get('sortby'))) ? trim(Yii::$app->request->get('sortby')) : 'g.goods_id';
        $search_data['order'] = trim(Yii::$app->request->get('order')) == 'SORT_ASC' ? SORT_ASC : SORT_DESC;

        //得到商品库存
        //	$goods_list = Goods::find()->where(['goods_status'=>1]);
        $query = new \yii\db\Query();
        $goods_list = $query->select('g.goods_id,g.cat_id,g.goods_sn,g.goods_name,g.isbn,g.goods_img,gs.supplier_price,gs.supplier_id,b.brand_name')
            ->from('goods as g')
            ->leftjoin('goods_supplier as gs', 'gs.goods_id=g.goods_id')
            ->leftjoin('brand as b', 'g.brand_id=b.id');

        if ($search_data['goods_id']) {
            $goods_list = $goods_list->andwhere(['g.goods_id' => $search_data['goods_id']]);
        }
        if ($search_data['type']) {
            $goods_list = $goods_list->andwhere(['g.type' => $search_data['type']]);
        }

        if ($search_data['category']) {
            //得到分类的子分类
            $children_cate_id = Category::cat_children_str($search_data['category']);
            $goods_list = $goods_list->andwhere(['IN', 'g.cat_id', $children_cate_id]);
        }

        if ($search_data['goods_name']) {
            $goods_list = $goods_list->andwhere(['like', 'g.goods_name', $search_data['goods_name']]);
        }

        if ($search_data['brand_id']) {
            $goods_list = $goods_list->andwhere(['g.brand_id' => $search_data['brand_id']]);
            $brand = Brand::findone($search_data['brand_id']);
        }

        // if($search_data['supplier_name']){
        // 	$goods_list = $goods_list->andwhere(['like','g.supplier_name',$search_data['supplier_name']]);
        // }

        if ($search_data['supplier_id']) {
            $goods_list = $goods_list->andwhere(['gs.supplier_id' => $search_data['supplier_id']]);
        }
        if ($search_data['supplier']) {
            $goods_list = $goods_list->andwhere(['gs.supplier_id' => $search_data['supplier']]);
            $supplier_info = Supplier::findone($search_data['supplier']);
        }
        if (!$search_data['OrderType']) {
            $search_data['OrderType'] = 1;
        }
        if ($search_data['purchase_start']) {
            $goods_list = $goods_list->andwhere(['>', 'gs.supplier_price', $search_data['purchase_start']]);
        }

        if ($search_data['purchase_end']) {
            $goods_list = $goods_list->andwhere(['<', 'gs.supplier_price', $search_data['purchase_end']]);
        }

        if ($search_data['market_start'] > 0) {
            $goods_list = $goods_list->andwhere(['>', 'g.market_price', $search_data['market_start']]);
        }

        if ($search_data['market_end'] > 0) {
            $goods_list = $goods_list->andwhere(['<', 'g.market_price', $search_data['market_end']]);
        }

        if ($search_data['sale_start'] > 0) {
            $goods_list = $goods_list->andwhere(['>', 'g.shop_price', $search_data['sale_start']]);
        }


        if ($search_data['sale_end'] > 0) {
            $goods_list = $goods_list->andwhere(['<', 'g.shop_price', $search_data['sale_end']]);
        }

        $countQuery = clone $goods_list;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 15, 'pageSizeLimit' => 1]);


        $goods_list = $goods_list->offset($pages->offset)
            ->orderby([$search_data['sortby'] => $search_data['order']])
            ->groupBy(' g.goods_id')
            ->limit($pages->limit)
            ->all();

        //得到商品分类 并分级
        $cat_list = Category::cat_list(0, $search_data['category']);
        // /得到品牌列表
        $brand_list = Brand::get_select_list();
        $this->controller->layout = 'empty';
        return $this->controller->render('/goods/search-goods', [
            'goods_list' => $goods_list,
            'cat_list' => $cat_list,
            'brand_list' => $brand_list,
            'pages' => $pages,
            'brand' => $brand,
            'search_data' => $search_data,
            'insert_url' => $insert_url,
            'url' => $url,
            'order_id' => $this->order_id,
            'supplier_info' => $supplier_info,
            'supplier_url' => $supplier_url,
            'OrderType' => $search_data['OrderType'],


        ]);
    }
}
