<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;
use app\common\models\WebOrderGoods;
 
class WebOrder extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'web_order';
    }

    public static function getSimpleCode()
    {
        return 'WEBOR';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_user_id', 'depart_id', 'sell_order_status', 'admit_user_id', 'custom_id', 'invoice_status', 'shipping_status', 'return_status', 'pay_status', 'store_id'], 'integer'],
            [['order_sn', 'plat_id', 'add_time', 'add_user_name', 'depart_name', 'remark', 'admit_user_name', 'admit_time', 'custom_name', 'pay_method', 'shipping_method', 'store_name', 'consignee', 'tel', 'address'], 'string', 'max' => 255],
            [['plat_name'], 'string', 'max' => 2],
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
            'plat_id' => 'Plat ID',
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
            'custom_id' => 'Custom ID',
            'custom_name' => 'Custom Name',
            'invoice_status' => 'Invoice Status',
            'pay_method' => 'Pay Method',
            'shipping_status' => 'Shipping Status',
            'return_status' => 'Return Status',
            'shipping_method' => 'Shipping Method',
            'pay_status' => 'Pay Status',
            'plat_name' => 'Plat Name',
            'store_id' => 'Store ID',
            'store_name' => 'Store Name',
            'consignee' => 'Consignee',
            'tel' => 'Tel',
            'address' => 'Address',
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
            'plat_id' => 'Plat ID',
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
            'custom_id' => 'Custom ID',
            'custom_name' => 'Custom Name',
            'invoice_status' => 'Invoice Status',
            'pay_method' => 'Pay Method',
            'shipping_status' => 'Shipping Status',
            'return_status' => 'Return Status',
            'shipping_method' => 'Shipping Method',
            'pay_status' => 'Pay Status',
            'plat_name' => 'Plat Name',
            'store_id' => 'Store ID',
            'store_name' => 'Store Name',
            'consignee' => 'Consignee',
            'tel' => 'Tel',
            'address' => 'Address',
        ];
    }

    public function getGoodsNumber(){
        $goods_number_total = $this->hasMany(WebOrderGoods::classname(),['order_id'=>'id'])->sum('number');

        if ($goods_number_total) {
            return $goods_number_total;
        }
        return 0;
    }
 

    public function getSaleTotal(){
        $sale_total = $this->hasMany(WebOrderGoods::classname(),['order_id'=>'id'])->sum('sale_price * number');
        if ($sale_total) {
            return $sale_total;
        }
        return 0;
    }    
}
