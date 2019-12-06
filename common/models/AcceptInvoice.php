<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;

class AcceptInvoice extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accept_invoice';
    }

    public static function getSimpleCode()
    {
        return 'ACCIN';
    }

     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fee', 'total', 'invoice_fee'], 'number'],
            [['order_id', 'add_user_id', 'depart_id', 'type','admit_user_id', 'accept_invoice_status', 'invoice_rate','custom_id','accept_user_id','supplier_id'], 'integer'],
            [['title', 'order_sn', 'tax_code', 'add_user_name','admit_time','supplier_name','admit_user_name','accept_user_name','invioce_content','invoice_time', 'add_time', 'depart_name', 'remain_time','csutom_name','relate_order_sn', 'remark', 'invoice_type', 'invoice_code', 'invoice_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '抬头',
            'fee' => '开票金额',
            'order_sn' => 'Order Sn',
            'order_id' => 'Order_id',
            'tax_code' => '客户税号',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'add_time' => 'Add Time',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'relate_order_sn' => 'Relate Order Sn',
            'type' => 'Type',
            'remark' => 'Remark',
            'total' => 'Total',
            'accept_invoice_status' => 'Invoice Status',
            'invoice_type' => 'Invoice Type',
            'invoice_rate' => 'Invoice Rate',
            'invoice_code' => 'Invoice Code',
            'invoice_number' => 'Invoice Number',
            'invoice_fee' => 'Invoice Fee',
            'remain_time' => '提醒时间',
            'custom_id' => 'custom_id',
            'custom_name' => 'csutom_name',
            'supplier_id' => 'supplier_id',
            'supplier_name' => 'supplier_name',            
            'receive_user_id' => 'csutom_name',
            'receive_user_name' => '领取人',
            'invioce_content' => '发票内容',
            'invoice_time' => '开票时间',
            'invoice_rate_fee' => '发票税额',
            'order_total' => '销售单金额',
            'admit_user_id' => '',
            'admit_user_name' => '',
            'admit_time' => '',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'fee' => 'Fee',
            'relate_order_id' => 'Relate Order ID',
            'order_sn' => 'Order Sn',
            'tax_code' => '客户税号',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'add_time' => 'Add Time',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'relate_order_sn' => 'Relate Order Sn',
            'type' => 'Type',
            'remark' => 'Remark',
            'total' => 'Total',
            'invoice_status' => 'Invoice Status',
            'invoice_type' => 'Invoice Type',
            'invoice_rate' => 'Invoice Rate',
            'invoice_code' => 'Invoice Code',
            'invoice_number' => 'Invoice Number',
            'invoice_fee' => 'Invoice Fee',
        ];
    }
}
