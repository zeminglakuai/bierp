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
        return 'RECEI';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'add_user_id', 'depart_id', 'supplier_id', 'custom_id', 'admit_user_id', 'admit_depart_id','receipt_status'], 'integer'],
            [['fee'], 'number'],
            [['model', 'add_time', 'add_user_name', 'depart_name', 'supplier_name', 'custom_name', 'paymethod', 'admit_time', 'admit_user_name', 'admit_depart_name', 'remark', 'remain_time', 'order_sn', 'relate_order_sn'], 'string', 'max' => 255],
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
            'supplier_name' => 'Supplier Name',
            'custom_id' => 'Custom ID',
            'custom_name' => 'Custom Name',
            'paymethod' => 'Paymethod',
            'admit_time' => 'Admit Time',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'admit_depart_id' => 'Admit Depart ID',
            'admit_depart_name' => 'Admit Depart Name',
            'remark' => 'Remark',
            'remain_time' => '收款时间',
            'order_sn' => 'Order Sn',
            'relate_order_sn' => 'Relate Order Sn',
            'receipt_status' => 'Relate Order Sn',

        ];
    }








}
