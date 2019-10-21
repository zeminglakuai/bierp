<?php

namespace app\common\models;

use Yii;
use app\common\models\Supplier;
/**
 * This is the model class for table "kw_supplier_goods".
 *
 * @property integer $id
 * @property integer $supplier_id
 * @property integer $goods_id
 * @property string $purchase_price
 */
class SupplierGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'supplier_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['supplier_id', 'goods_id', 'purchase_price'], 'required'],
            [['supplier_id', 'goods_id'], 'integer'],
            [['purchase_price'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplier_id' => 'Supplier ID',
            'goods_id' => 'Goods ID',
            'purchase_price' => 'Purchase Price',
        ];
    }

    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier_id']);
    }
}
