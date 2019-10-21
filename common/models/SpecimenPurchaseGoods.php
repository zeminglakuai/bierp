<?php

namespace app\common\models;

use Yii;

 
class SpecimenPurchaseGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $add_goods_error;
    public static function tableName()
    {
        return 'specimen_purchase_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'number', 'is_self_sell'], 'integer'],
            [['market_price', 'sale_price', 'purchase_price'], 'number'],
            [['order_id', 'goods_name', 'goods_sn', 'isbn'], 'string', 'max' => 255],
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
            'purchase_price' => 'Purchase Price',
            'is_self_sell' => 'Is Self Sell',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
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
            'purchase_price' => 'Purchase Price',
            'is_self_sell' => 'Is Self Sell',
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
            
            $this->purchase_price = isset($goods->sale_price)?$goods->sale_price:0;
            //$this->supplier_id = isset($goods->supplier_id)?$goods->supplier_id:0;
            //$this->supplier_price = isset($goods->supplier_price)?$goods->supplier_price:0;
            //$this->supplier_name = isset($goods->supplier_name)?$goods->supplier_name:'';             
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
        return round($this->purchase_price * $this->number,2);
    }
}
