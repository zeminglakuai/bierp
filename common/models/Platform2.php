<?php

namespace app\common\models;

use Yii;

use app\common\models\BaseModel;

class Platform extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'platform';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_user_id', 'depart_id', 'plat_status', 'is_delete', 'custom_id'], 'integer'],
            [['plat_info'], 'string'],
            [['plat_name', 'add_time', 'add_user_name', 'depart_name', 'custom_name','remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'plat_name' => '平台名称',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'plat_info' => 'Plat Info',
            'plat_status' => '状态',
            'is_delete' => 'Is Delete',
            'custom_id' => 'Custom ID',
            'custom_name' => 'Custom Name',
            'remark' => 'Custom Name',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'plat_name' => 'Plat Name',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'plat_info' => 'Plat Info',
            'plat_status' => 'Plat Status',
            'is_delete' => 'Is Delete',
            'custom_id' => 'Custom ID',
            'custom_name' => 'Custom Name',
        ];
    }
}
