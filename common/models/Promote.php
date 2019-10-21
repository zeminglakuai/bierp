<?php

namespace app\common\models;

use Yii;

use app\common\models\BaseModel;

class Promote extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promote';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'b2c_order_id', 'add_user_id', 'depart_id', 'custom_id', 'promote_status'], 'integer'],
            [['b2c_order_sn', 'add_user_name', 'add_time', 'depart_name', 'order_sn', 'custom_name', 'remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'b2c_order_id' => 'B2c Order ID',
            'b2c_order_sn' => 'B2c单据编号',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'add_time' => 'Add Time',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'order_sn' => 'Order Sn',
            'custom_id' => 'Custom ID',
            'custom_name' => 'Custom Name',
            'remark' => 'Remark',
            'promote_status' => '单据状态',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'b2c_order_id' => 'B2c Order ID',
            'b2c_order_sn' => 'B2c Order Sn',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'add_time' => 'Add Time',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'order_sn' => 'Order Sn',
            'custom_id' => 'Custom ID',
            'custom_name' => 'Custom Name',
            'remark' => 'Remark',
            'promote_status' => 'Promote Status',
        ];
    }
}
