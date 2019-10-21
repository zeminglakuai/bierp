<?php

namespace app\common\models;

use Yii;

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
            [['goods_id', 'number', 'supplier_id', 'is_self_sell', 'store_id', 'send_number', 'return_number'], 'integer'],
            [['market_price', 'sale_price', 'supplier_price', 'dd_price'], 'number'],
            [['order_id', 'goods_name', 'goods_sn', 'isbn', 'supplier_name'], 'string', 'max' => 255],
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
            'store_id' => 'Store ID',
            'send_number' => 'Send Number',
            'return_number' => 'Return Number',
            'dd_price' => 'Dd Price',
        ];
    }
}
