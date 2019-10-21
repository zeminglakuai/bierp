<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;

class DaifaOrder extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'daifa_order';
    }

    public static function getSimpleCode()
    {
        return 'DAIFA';
    }

    public function rules()
    {
        return [
            [['add_user_id', 'admit_user_id', 'depart_id', 'sell_order_id', 'daifa_order_status', 'materiel_id', 'supplier_id', 'status_done'], 'integer'],
            [['materiel_fee', 'shipping_fee'], 'number'],
            [['add_time', 'admit_time', 'order_sn', 'add_user_name', 'admit_user_name', 'remark', 'depart_name', 'consignee', 'tel', 'address', 'sell_order_sn', 'custom_id', 'custom_name', 'shipping_method', 'shipping_code', 'supplier_name', 'status_name'], 'string', 'max' => 255],
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
            'consignee' => 'Consignee',
            'tel' => 'Tel',
            'address' => 'Address',
            'sell_order_id' => 'Sell Order ID',
            'sell_order_sn' => 'Sell Order Sn',
            'custom_id' => 'Custom ID',
            'custom_name' => 'Custom Name',
            'daifa_order_status' => 'Daifa Order Status',
            'shipping_method' => 'Shipping Method',
            'materiel_id' => 'Materiel ID',
            'materiel_fee' => 'Materiel Fee',
            'shipping_fee' => 'Shipping Fee',
            'shipping_code' => '发货单号',
            'supplier_id' => 'Supplier ID',
            'supplier_name' => 'Supplier Name',
            'status_name' => 'Status Name',
            'status_done' => '审核流程结束',
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
            'consignee' => 'Consignee',
            'tel' => 'Tel',
            'address' => 'Address',
            'sell_order_id' => 'Sell Order ID',
            'sell_order_sn' => 'Sell Order Sn',
            'custom_id' => 'Custom ID',
            'custom_name' => 'Custom Name',
            'daifa_order_status' => 'Daifa Order Status',
            'shipping_method' => 'Shipping Method',
            'materiel_id' => 'Materiel ID',
            'materiel_fee' => 'Materiel Fee',
            'shipping_fee' => 'Shipping Fee',
            'shipping_code' => '发货单号',
            'supplier_id' => 'Supplier ID',
            'supplier_name' => 'Supplier Name',
            'status_name' => 'Status Name',
            'status_done' => '审核流程结束',
        ];
    }

    public function getSellOrder(){
        return $this->hasone(SellOrder::classname(), ['id' => 'sell_order_id']);
    }

    public function getGoodsNumber(){
        $number = $this->hasone(DaifaOrderGoods::classname(), ['order_id' => 'id'])->sum('number');
        return $number?$number:'0';
    }

    public function getSendNumber(){
        return $this->hasone(DaifaOrderGoods::classname(), ['order_id' => 'id'])->sum('send_number');
    }



}
