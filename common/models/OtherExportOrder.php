<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;

class OtherExportOrder extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'other_export_order';
    }

    public static function getSimpleCode()
    {
        return 'OTHEREX';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_user_id', 'admit_user_id', 'depart_id', 'other_export_order_status', 'store_id', 'other_export_order_type'], 'integer'],
            [['add_time', 'admit_time', 'order_sn', 'add_user_name', 'admit_user_name', 'remark', 'depart_name', 'shipping_method', 'store_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'add_time' => 'Add Time',
            'admit_time' => 'Admit Time',
            'add_user_id' => 'Add User ID',
            'admit_user_id' => 'Admit User ID',
            'depart_id' => 'Depart ID',
            'order_sn' => 'Order Sn',
            'add_user_name' => 'Add User Name',
            'admit_user_name' => 'Admit User Name',
            'remark' => 'Remark',
            'depart_name' => 'Depart Name',
            'other_export_order_status' => 'Other Export Order Status',
            'shipping_method' => 'Shipping Method',
            'store_name' => '仓库',
            'store_id' => 'Store ID',
            'other_export_order_type' => '其他出库类型',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'add_time' => 'Add Time',
            'admit_time' => 'Admit Time',
            'add_user_id' => 'Add User ID',
            'admit_user_id' => 'Admit User ID',
            'depart_id' => 'Depart ID',
            'order_sn' => 'Order Sn',
            'add_user_name' => 'Add User Name',
            'admit_user_name' => 'Admit User Name',
            'remark' => 'Remark',
            'depart_name' => 'Depart Name',
            'other_export_order_status' => 'Other Export Order Status',
            'shipping_method' => 'Shipping Method',
            'store_name' => '仓库',
            'store_id' => 'Store ID',
            'other_export_order_type' => '其他出库类型',
        ];
    }
}
