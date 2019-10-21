<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "batch".
 *
 * @property integer $id
 * @property string $batch_code
 * @property integer $goods_id
 * @property string $price
 * @property integer $number
 * @property string $addtime
 * @property integer $store_id
 */
class Batch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'batch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'number','store_id'], 'integer'],
            [['price'], 'number'],
            [['batch_code', 'addtime'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'batch_code' => 'Batch Code',
            'goods_id' => 'Goods ID',
            'price' => 'Price',
            'number' => 'Number',
            'addtime' => 'Addtime',
            'store_id' => 'store_id',
        ];
    }
}
