<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "consignee".
 *
 * @property integer $id
 * @property integer $belong_id
 * @property string $model
 * @property string $consignee
 * @property string $address
 * @property string $tel
 * @property string $remark
 */
class Consignee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'consignee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['belong_id'], 'integer'],
            [['model', 'consignee', 'address', 'tel', 'remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'belong_id' => 'Belong ID',
            'model' => 'Model',
            'consignee' => 'Consignee',
            'address' => 'Address',
            'tel' => 'Tel',
            'remark' => 'Remark',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'belong_id' => 'Belong ID',
            'model' => 'Model',
            'consignee' => 'Consignee',
            'address' => 'Address',
            'tel' => 'Tel',
            'remark' => 'Remark',
        ];
    }
}
