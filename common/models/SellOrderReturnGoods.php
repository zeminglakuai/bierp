<?php

namespace app\common\models;

use Yii;

class SellOrderReturnGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sell_order_return_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'number', 'supplier_id', 'is_self_sell','return_type'], 'integer'],
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
            'return_number' => '退换数量',
            'return_price' => '退还金额',
            'return_type' => '处理方式', 
            'note' => '备注',
        ];
    }

    public function exportLabels()
    {
        return [
            'goods_id' => 'Goods ID',
            'goods_name' => 'Goods Name',
            'goods_sn' => 'Goods Sn',
            'market_price' => 'Market Price',
            'sale_price' => 'Sale Price',
            'number' => 'Number',
            'isbn' => 'Isbn',
            'supplier_price' => 'Supplier Price',
            'supplier_name' => 'Supplier Name',
            'return_number' => '退换数量',
            'return_price' => '退还金额',
            'return_type' => '处理方式', 
            'note' => '备注',
        ];
    }

    public function getXiaoji(){
        return round($this->sale_price * $this->number,2);
    }

}
