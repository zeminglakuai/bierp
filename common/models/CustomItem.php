<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "custom_item".
 *
 * @property integer $id
 * @property string $item_name
 * @property integer $custom_id
 * @property string $custom_name
 * @property string $remark
 * @property integer $add_user_id
 * @property string $add_user_name
 * @property string $add_time
 * @property integer $depart_id
 * @property string $depart_name
 * @property string $stauts_name
 * @property integer $status_done
 */
class CustomItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'custom_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'custom_id', 'add_user_id', 'depart_id', 'status_done'], 'integer'],
            [['item_name', 'custom_name', 'remark', 'add_user_name', 'add_time', 'depart_name', 'stauts_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_name' => 'Item Name',
            'custom_id' => 'Custom ID',
            'custom_name' => 'Custom Name',
            'remark' => 'Remark',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'add_time' => 'Add Time',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'stauts_name' => 'Stauts Name',
            'status_done' => 'Status Done',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'item_name' => 'Item Name',
            'custom_id' => 'Custom ID',
            'custom_name' => 'Custom Name',
            'remark' => 'Remark',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'add_time' => 'Add Time',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'stauts_name' => 'Stauts Name',
            'status_done' => 'Status Done',
        ];
    }
}
