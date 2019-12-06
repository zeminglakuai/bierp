<?php

namespace app\common\models;

use Yii;
use app\common\models\PurchaseReturnGoods;
use app\common\models\Supplier;
use app\common\models\Purchase;
use app\common\models\BaseModel;

class PurchaseReturn extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purchase_return';
    }

    public static function getSimpleCode()
    {
        return 'PURE';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['supplier_id', 'purchase_status', 'admit_user_id', 'add_user_id', 'depart_id', 'invoice_status', 'pay_status', 'return_status', 'period', 'store_id'], 'integer'],
            [['order_name', 'add_time', 'admit_time', 'order_sn', 'supplier_name', 'remark', 'admit_user_name', 'add_user_name', 'depart_name', 'pay_method', 'shipping_method', 'store_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_name' => 'Order Name',
            'add_time' => 'Add Time',
            'admit_time' => 'Admit Time',
            'order_sn' => 'Order Sn',
            'supplier_id' => 'Supplier ID',
            'supplier_name' => 'Supplier Name',
            'purchase_status' => 'Purchase Status',
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
            'return_status' => 'Return Status',
            'period' => 'Period',
            'store_id' => 'Store ID',
            'store_name' => 'Store Name',
            'purchase_id' => 'Store Name',
            'purchase_sn' => '采购单号',
            'returnNumber' => '退货总数',
        ];
    }


    public function exportLabels()
    {
        return [
            'add_time' => 'Add Time',
            'admit_time' => 'Admit Time',
            'order_sn' => 'Order Sn',
            'supplier_name' => 'Supplier Name',
            'remark' => 'Remark',
            'add_user_name' => 'Add User Name',
            'depart_name' => 'Depart Name',
            'store_name' => 'Store Name',
            'purchase_id' => 'Store Name',
            'purchase_sn' => '采购单号',
            'status_name' => 'Store Name',
        ];
    }

    public function getReturnNumber(){
        $goods_number_total = $this->hasMany(PurchaseReturnGoods::classname(),['order_id'=>'id'])->sum('return_number');
        return $goods_number_total?$goods_number_total:0;
    }

    public function getSupplier(){
        return $this->hasone(Supplier::classname(), ['id' => 'supplier_id']);
    }

    public function getPurchase(){
        return $this->hasMany(Purchase::classname(), ['order_id' => 'purchase_id']);
    }

}
