<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;

class Payment extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment';
    }

    public static function getSimpleCode()
    {
        return 'PAYMENT';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'add_user_id', 'depart_id', 'supplier_id', 'custom_id', 'admit_user_id', 'admit_depart_id', 'payment_status'], 'integer'],
            [['fee'], 'number'],
            [['model', 'add_time', 'add_user_name', 'depart_name', 'supplier_name', 'custom_name', 'pay_method', 'admit_time', 'admit_user_name', 'admit_depart_name', 'remark', 'remain_time', 'order_sn', 'relate_order_sn', 'pay_bank_name', 'pay_bank_code', 'receipt_method', 'receipt_bank_name', 'receipt_bank_code', 'pay_other', 'receipt_bank_account', 'pay_bank_account', 'pay_time'], 'string', 'max' => 255],
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
            'fee' => 'Fee',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'supplier_id' => 'Supplier ID',
            'supplier_name' => '供货商名称',
            'custom_id' => 'Custom ID',
            'custom_name' => 'Custom Name',
            'pay_method' => '付款方式',
            'admit_time' => '复核时间',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'admit_depart_id' => 'Admit Depart ID',
            'admit_depart_name' => 'Admit Depart Name',
            'remark' => 'Remark',
            'remain_time' => '提醒付款时间',
            'order_sn' => '单号',
            'relate_order_sn' => 'Relate Order Sn',
            'payment_status' => '付款单状态',
            'pay_bank_name' => '付款银行',
            'pay_bank_code' => '付款银行账号',
            'receipt_bank_name' => '收款银行',
            'receipt_bank_code' => '收款银行账号',
            'pay_other' => '其他付款信息',
            'receipt_bank_account' => '收款银行开户名',
            'pay_bank_account' => '付款银行开户名',
            'pay_time' => '付款时间',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'order_id' => 'Order ID',
            'model' => 'Model',
            'fee' => 'Fee',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'supplier_id' => 'Supplier ID',
            'supplier_name' => '供货商名称',
            'custom_id' => 'Custom ID',
            'custom_name' => 'Custom Name',
            'pay_method' => '付款方式',
            'admit_time' => '复核时间',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'admit_depart_id' => 'Admit Depart ID',
            'admit_depart_name' => 'Admit Depart Name',
            'remark' => 'Remark',
            'remain_time' => '提醒付款时间',
            'order_sn' => '单号',
            'relate_order_sn' => 'Relate Order Sn',
            'payment_status' => '付款状态',
            'pay_bank_name' => '付款银行',
            'pay_bank_code' => '付款银行账号',
            'receipt_bank_name' => '收款银行',
            'receipt_bank_code' => '收款银行账号',
            'pay_other' => '其他付款信息',
            'receipt_bank_account' => '收款银行开户名',
            'pay_bank_account' => '付款银行开户名',
            'pay_time' => '付款时间',
        ];
    }
}
