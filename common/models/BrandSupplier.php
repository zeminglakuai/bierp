<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "brand_supplier".
 *
 * @property integer $id
 * @property integer $brand_id
 * @property integer $supplier_id
 */
class BrandSupplier extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand_supplier';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id', 'supplier_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand_id' => 'Brand ID',
            'supplier_id' => 'Supplier ID',
        ];
    }
}
