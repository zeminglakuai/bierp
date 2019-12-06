<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property integer $belong_id
 * @property string $consignee
 * @property string $tel
 * @property string $address
 * @property string $type
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'belong_id'], 'integer'],
            [['consignee', 'tel', 'address', 'type'], 'string', 'max' => 255],
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
            'consignee' => 'Consignee',
            'tel' => 'Tel',
            'address' => 'Address',
            'type' => 'Type',
        ];
    }
}
