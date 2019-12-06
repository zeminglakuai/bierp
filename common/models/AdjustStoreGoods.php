<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "adjust_store_goods".
 *
 * @property integer $id
 * @property string $order_id
 * @property integer $goods_id
 * @property string $goods_name
 * @property string $isbn
 * @property string $goods_sn
 * @property string $market_price
 * @property integer $number
 * @property integer $origin_store_code
 * @property string $target_store_code
 */
class AdjustStoreGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'adjust_store_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'number', 'origin_store_code'], 'integer'],
            [['market_price'], 'number'],
            [['order_id', 'goods_name', 'isbn', 'goods_sn', 'target_store_code'], 'string', 'max' => 255],
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
            'isbn' => 'Isbn',
            'goods_sn' => 'Goods Sn',
            'market_price' => 'Market Price',
            'number' => 'Number',
            'origin_store_code' => 'Origin Store Code',
            'target_store_code' => 'Target Store Code',
        ];
    }
}
