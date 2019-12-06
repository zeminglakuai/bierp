<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;
class RequestAdjustOrder extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'request_adjust_order';
    }

    public static function getSimpleCode()
    {
        return 'REQUAD';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_user_id', 'depart_id', 'sell_order_status', 'admit_user_id', 'shipping_status', 'from_store_id', 'to_store_id'], 'integer'],
            [['order_sn', 'add_time', 'add_user_name', 'depart_name', 'remark', 'admit_user_name', 'admit_time', 'pay_method', 'shipping_method', 'from_store_name', 'consignee', 'tel', 'address', 'to_store_name'], 'string', 'max' => 255],
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
            'sell_order_status' => 'Sell Order Status',
            'remark' => 'Remark',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'admit_time' => 'Admit Time',
            'pay_method' => 'Pay Method',
            'shipping_status' => 'Shipping Status',
            'shipping_method' => 'Shipping Method',
            'from_store_id' => 'From Store ID',
            'from_store_name' => '申请仓库',
            'consignee' => 'Consignee',
            'tel' => 'Tel',
            'address' => 'Address',
            'to_store_id' => 'To Store ID',
            'to_store_name' => '接受仓库',
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
            'sell_order_status' => 'Sell Order Status',
            'remark' => 'Remark',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'admit_time' => 'Admit Time',
            'pay_method' => 'Pay Method',
            'shipping_status' => 'Shipping Status',
            'shipping_method' => 'Shipping Method',
            'from_store_id' => 'From Store ID',
            'from_store_name' => 'From Store Name',
            'consignee' => 'Consignee',
            'tel' => 'Tel',
            'address' => 'Address',
            'to_store_id' => 'To Store ID',
            'to_store_name' => 'To Store Name',
        ];
    }


    public function getGoodsNumber(){
        $goods_number = $this->hasMany(RequestAdjustOrderGoods::classname(),['order_id'=>'id'])->sum('number');
        return $goods_number?$goods_number:0;
    }
 
}
