<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "web_order_goods".
 *
 * @property integer $id
 * @property string $order_id
 * @property integer $goods_id
 * @property string $goods_name
 * @property string $goods_sn
 * @property string $market_price
 * @property string $sale_price
 * @property integer $number
 * @property string $isbn
 * @property string $supplier_price
 * @property integer $supplier_id
 * @property string $supplier_name
 * @property integer $is_self_sell
 * @property integer $store_id
 * @property integer $send_number
 * @property integer $return_number
 * @property integer $is_daifa
 */
class WebOrderGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'web_order_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'number', 'supplier_id', 'is_self_sell', 'store_id', 'send_number', 'return_number', 'is_daifa'], 'integer'],
            [['market_price', 'sale_price', 'supplier_price'], 'number'],
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
            'is_daifa' => '设置如果是代发',
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
            'supplier_price' => 'Supplier Price',
            'supplier_id' => 'Supplier ID',
            'supplier_name' => 'Supplier Name',
            'is_self_sell' => 'Is Self Sell',
            'store_id' => 'Store ID',
            'send_number' => 'Send Number',
            'return_number' => 'Return Number',
            'is_daifa' => '设置如果是代发',
        ];
    }
}
