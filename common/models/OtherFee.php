<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;

class OtherFee extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'other_fee';
    }

    public static function getSimpleCode()
    {
        return 'OTHERFEE';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'add_user_id', 'depart_id', 'invoice_status', 'custom_id'], 'integer'],
            [['fee'], 'number'],
            [['order_sn', 'add_time', 'add_user_name', 'depart_name', 'fee_ttype_name', 'fee_couse', 'invoice_type', 'remark', 'custom_name', 'custom_tel'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_sn' => 'Order Sn',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'fee' => '费用金额',
            'fee_ttype_name' => '费用类型',
            'fee_couse' => '费用用途',
            'invoice_type' => '发票类型',
            'invoice_status' => '发票状态',
            'remark' => 'Remark',
            'custom_id' => 'Custom ID',
            'custom_name' => 'Custom Name',
            'custom_tel' => 'Custom Tel',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'order_sn' => 'Order Sn',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'fee' => '费用金额',
            'fee_ttype_name' => '费用类型',
            'fee_couse' => '费用用途',
            'invoice_type' => '发票类型',
            'invoice_status' => '发票状态',
            'remark' => 'Remark',
            'custom_id' => 'Custom ID',
            'custom_name' => 'Custom Name',
            'custom_tel' => 'Custom Tel',
        ];
    }
}
