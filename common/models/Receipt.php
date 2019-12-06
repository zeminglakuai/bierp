<?php

namespace app\common\models;

use Yii;

use app\common\models\BaseModel;

class Receipt extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'receipt';
    }

    public static function getSimpleCode()
    {
        return 'RECEIPT';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'add_user_id', 'depart_id', 'supplier_id', 'custom_id', 'admit_user_id', 'admit_depart_id', 'receipt_status'], 'integer'],
            [['fee'], 'number'],
            [['model', 'add_time', 'add_user_name','pay_ohter', 'depart_name','pay_bank_name', 'supplier_name', 'custom_name', 'pay_method', 'admit_time', 'admit_user_name', 'admit_depart_name', 'remark', 'remain_time', 'order_sn', 'relate_order_sn', 'pay_bank_code', 'receipt_method', 'receipt_bank_name', 'receipt_bank_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'model' => 'Model',
            'fee' => '金额',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'supplier_id' => 'Supplier ID',
            'supplier_name' => 'Supplier Name',
            'custom_id' => 'Custom ID',
            'custom_name' => 'Custom Name',
            'pay_method' => 'Pay Method',
            'admit_time' => '复核时间',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'admit_depart_id' => 'Admit Depart ID',
            'admit_depart_name' => 'Admit Depart Name',
            'remark' => 'Remark',
            'remain_time' => '提醒收款时间',
            'order_sn' => 'Order Sn',
            'relate_order_sn' => 'Relate Order Sn',
            'receipt_status' => '收款单状态',
            'pay_bank_account' => '付款银行开户名', 
            'pay_bank_name' => '付款银行',
            'pay_bank_code' => '银行账号',
            'pay_ohter' => '其他付款信息',
            'receipt_bank_account' => '收款银行开户名', 
            'receipt_method' => '收款方式',
            'receipt_bank_name' => '收款银行',
            'receipt_bank_code' => '收款银行账号',
            'receipt_time' => '收款时间', 
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'model' => 'Model',
            'fee' => 'Fee',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'supplier_id' => 'Supplier ID',
            'supplier_name' => 'Supplier Name',
            'custom_id' => 'Custom ID',
            'custom_name' => 'Custom Name',
            'pay_method' => 'Pay Method',
            'admit_time' => 'Admit Time',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'admit_depart_id' => 'Admit Depart ID',
            'admit_depart_name' => 'Admit Depart Name',
            'remark' => 'Remark',
            'remain_time' => 'Remain Time',
            'order_sn' => 'Order Sn',
            'relate_order_sn' => 'Relate Order Sn',
            'receipt_status' => 'Receipt Status',
            'bank_name' => 'Bank Name',
            'bank_code' => 'Bank Code',
            'receipt_method' => 'Receipt Method',
            'receipt_bank_name' => 'Receipt Bank Name',
            'receipt_bank_code' => 'Receipt Bank Code',
        ];
    }
}
