<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "activity_goods".
 *
 * @property integer $id
 * @property integer $activity_id
 * @property integer $goods_id
 * @property string $sale_price
 * @property integer $number
 * @property integer $supplier_id
 * @property string $supplier_name
 * @property string $supplier_price
 * @property string $goods_name
 * @property string $goods_sn
 * @property string $brand_name
 * @property integer $brand_id
 * @property string $market_price
 */
class ActivityGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id', 'goods_id', 'number', 'supplier_id', 'brand_id'], 'integer'],
            [['sale_price', 'supplier_price', 'market_price'], 'number'],
            [['supplier_name', 'goods_name', 'goods_sn', 'brand_name'], 'string', 'max' => 233]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_id' => 'Activity ID',
            'goods_id' => 'Goods ID',
            'sale_price' => 'Sale Price',
            'number' => 'Number',
            'supplier_id' => 'Supplier ID',
            'supplier_name' => 'Supplier Name',
            'supplier_price' => 'Supplier Price',
            'goods_name' => 'Goods Name',
            'goods_sn' => 'Goods Sn',
            'brand_name' => 'Brand Name',
            'brand_id' => 'Brand ID',
            'market_price' => 'Market Price',
        ];
    }
}
