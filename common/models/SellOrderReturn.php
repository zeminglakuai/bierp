<?php

namespace app\common\models;

use Yii;

use app\common\models\SellOrder;
use app\common\models\BaseModel;
use app\common\models\Custom;

class SellOrderReturn extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sell_order_return';
    }
    public static function getSimpleCode()
    {
        return 'SELLRE';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_user_id', 'depart_id', 'sell_order_status', 'admit_user_id', 'custom_id','store_id'], 'integer'],
            [['order_sn', 'custom_order_id', 'add_time', 'add_user_name','return_reason','consignee','tel','address','store_name', 'depart_name', 'remark', 'admit_user_name', 'admit_time', 'custom_name'], 'string', 'max' => 255]
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
            'sell_order_id' => 'Custom Order ID',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'sell_order_return_status' => '退货单状态',
            'remark' => 'Remark',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'admit_time' => 'Admit Time',
            'custom_id' => 'Custom ID',
            'custom_name' => 'Custom Name',
            'sellOrder&order_sn' => '销售单号', 
            'store_id' => '仓库', 
            'store_name' => '收货仓库', 
            'tel' => '收货人手机号',
            'consignee' => '收货人姓名',
            'address' => '收货地址',
            'return_reason' => '退货原因',            
        ];
    }

    public function exportLabels()
    {
        return [
            'order_sn' => 'Order Sn',
            'sell_order_id' => 'Custom Order ID',
            'add_time' => 'Add Time',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'sell_order_return_status' => '退货单状态',
            'remark' => 'Remark',
            'admit_time' => 'Admit Time',
            'custom_name' => 'Custom Name',
            'sellOrder&order_sn' => '销售单号', 
            'store_id' => '销售单号', 
            'store_name' => '收货仓库', 
            'tel' => '收货人手机号',
            'consignee' => '收货人姓名',
            'address' => '收货地址',
            'return_reason' => '退货原因',
        ];
    }

    public function getSellOrder(){
        return $this->hasone(SellOrder::classname(), ['id' => 'sell_order_id']);
    }

    public function getCustom(){
        return $this->hasone(Custom::classname(), ['id' => 'custom_id']);
    }

}
