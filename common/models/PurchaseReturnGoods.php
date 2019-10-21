<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "purchase_return_goods".
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
 * @property string $purchase_price
 * @property integer $is_self_sell
 * @property integer $return_number
 */
class PurchaseReturnGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purchase_return_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'number', 'is_self_sell', 'return_number'], 'integer'],
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
            'return_number' => '退货数',
        ];
    }

    /**
     * @inheritdoc
     */
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
            'purchase_price' => '',
            'return_number' => '退货数',
        ];
    }


    public function getXiaoji(){
        $xiaoji = round($this->purchase_price*$this->return_number,2);
        if ($xiaoji) {
            return $xiaoji;
        }
        return 0;
    }    
}
