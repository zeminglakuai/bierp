<?php

namespace app\common\models;

use Yii;
use app\common\models\Store;
use app\common\models\Stock;

class SellOrderGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $add_goods_error;
    public static function tableName()
    {
        return 'sell_order_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'number', 'supplier_id', 'is_self_sell','store_id','send_number'], 'integer'],
            [['market_price', 'sale_price', 'supplier_price'], 'number'],
            [['order_id', 'goods_name', 'goods_sn', 'isbn', 'supplier_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'goods_id' => 'Goods ID',
            'goods_name' => 'Goods Name',
            'goods_sn' => 'Goods Sn',
            'market_price' => 'Market Price',
            'sale_price' => 'Sale Price',
            'number' => 'Number',
            'isbn' => 'Isbn',
            'supplier_price' => 'Supplier Price',
            'supplier_id' => 'Supplier ID',
            'supplier_name' => 'Supplier Name',
            'is_self_sell' => 'Is Self Sell',
            'store_id' => 'store_id',
            'xiaoji' => '小计',
            'send_number' => '已发数量', 
            'goods_store_info'=>'库存信息',
            'return_number'=>'退货数量',
            'is_daifa'=>'代发',
            'batch_info'=>'批次信息',
            'final_cost'=>'批次价格',
        ];
    }

    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'order_id' => 'Order ID',
            'goods_id' => 'Goods ID',
            'goods_name' => 'Goods Name',
            'goods_sn' => 'Goods Sn',
            'market_price' => 'Market Price',
            'sale_price' => 'Sale Price',
            'number' => 'Number',
            'isbn' => 'Isbn',
            'supplier_price' => 'Supplier Price',
            'xiaoji' => '小计',
            'send_number' => '已发数量', 
            'goods_store_info'=>'库存信息',
            'return_number'=>'退货数量',
            'is_daifa'=>'代发',
        ];
    }





    public function AddGoods($order_id,$goods){
        //检查商品是不是已经存在
        $if_exitd = $this::find()->where(['goods_id'=>$goods->goods_id,'order_id'=>$order_id])->one();
        if ($if_exitd) {
            $this->add_goods_error = $goods->goods_name.'已经存在';
            return false;
        }

        if ($goods) {
            $this->order_id = $order_id;
            $this->goods_id = $goods->goods_id;
            $this->goods_name = $goods->goods_name;
            $this->goods_sn = $goods->goods_sn;
            $this->market_price = $goods->market_price;
            $this->isbn = $goods->isbn;

            $this->sale_price = isset($goods->sale_price)?$goods->sale_price:0;
            $this->supplier_id = isset($goods->supplier_id)?$goods->supplier_id:0;
            $this->supplier_price = isset($goods->supplier_price)?$goods->supplier_price:0;
            $this->supplier_name = isset($goods->supplier_name)?$goods->supplier_name:'';             
            $this->number = isset($goods->number)?$goods->number:1;
            $this->is_self_sell = $goods->is_self_sell?$goods->is_self_sell:0;
            $this->save(false);
            
            return true;
        }else{
            $this->add_goods_error = '缺少参数已经存在';
            return false;
        }
    }
    
    public function getXiaoji(){
        return round($this->sale_price * $this->number,2);
    }
}
