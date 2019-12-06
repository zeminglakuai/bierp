<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "web_user".
 *
 * @property integer $id
 * @property string $consignee
 * @property string $tel
 * @property string $address
 * @property integer $province
 * @property integer $city
 * @property integer $area
 * @property string $add_time
 */
class WebUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'web_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['province', 'city', 'area'], 'integer'],
            [['consignee', 'tel', 'address', 'add_time'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'consignee' => '用户姓名',
            'tel' => 'Tel',
            'address' => 'Address',
            'province' => 'Province',
            'city' => 'City',
            'area' => 'Area',
            'add_time' => 'Add Time',
            'plat_id' => 'Add Time',
            'plat_name' => '平台名称',            
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'consignee' => 'Consignee',
            'tel' => 'Tel',
            'address' => 'Address',
            'province' => 'Province',
            'city' => 'City',
            'area' => 'Area',
            'add_time' => 'Add Time',
        ];
    }
}
