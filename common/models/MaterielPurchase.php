<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;

class MaterielPurchase extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'materiel_purchase';
    }
    public static function getSimpleCode()
    {
        return 'MAPU';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['supplier_id', 'materiel_purchase_status', 'admit_user_id', 'add_user_id', 'depart_id', 'invoice_status', 'pay_status', 'store_id'], 'integer'],
            [['add_time', 'admit_time', 'order_sn', 'supplier_name', 'remark', 'admit_user_name', 'add_user_name', 'depart_name', 'pay_method', 'shipping_method', 'store_name'], 'string', 'max' => 255],
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
            'order_sn' => 'Order Sn',
            'supplier_id' => 'Supplier ID',
            'supplier_name' => 'Supplier Name',
            'materiel_purchase_status' => 'Materiel Purchase Status',
            'remark' => 'Remark',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'invoice_status' => 'Invoice Status',
            'pay_method' => 'Pay Method',
            'pay_status' => 'Pay Status',
            'shipping_method' => 'Shipping Method',
            'store_id' => 'Store ID',
            'store_name' => 'Store Name',
        ];
    }

    public function exportLabels()
    {
        return [
            'add_time' => 'Add Time',
            'admit_time' => 'Admit Time',
            'order_sn' => 'Order Sn',
            'supplier_id' => 'Supplier ID',
            'supplier_name' => 'Supplier Name',
            'materiel_purchase_status' => 'Materiel Purchase Status',
            'remark' => 'Remark',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'invoice_status' => 'Invoice Status',
            'pay_method' => 'Pay Method',
            'pay_status' => 'Pay Status',
            'shipping_method' => 'Shipping Method',
            'store_id' => 'Store ID',
            'store_name' => 'Store Name',
        ];
    }


}
