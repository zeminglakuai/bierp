<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;
use app\common\models\CustomOrderGoods;

class B2cOrder extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'b2c_order';
    }

    public static function getSimpleCode()
    {
        return 'B2COR';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_user_id', 'admit_user_id', 'depart_id', 'custom_id', 'custom_order_status', 'ask_order_done_user_id', 'is_create_ask_price'], 'integer'],
            [['order_name', 'add_time', 'admit_time','item_expire', 'order_sn', 'add_user_name', 'admit_user_name', 'custom_name', 'remark', 'depart_name', 'ask_order_done_user_name', 'ask_order_done_time'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_name' => '项目名称',
            'add_time' => '添加时间',
            'admit_time' => '审核时间',
            'add_user_id' => '添加人ID',
            'admit_user_id' => '审核人ID',
            'depart_id' => '部门ID',
            'order_sn' => '单据号',
            'add_user_name' => '添加人',
            'admit_user_name' => '审核人',
            'custom_id' => '客户ID',
            'custom_name' => '客户',
            'custom&custom_name' => '客户名称',
            'custom_order_status' => '状态',
            'saleTotal' => '销售额',
            'remark' => '备注',
            'depart_name' => 'Depart Name',
            'goodsNumber' => '商品数',
            'is_create_ask_price'=>'成报备单',
            'item_expire'=>'报名截止时间',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'order_name' => 'Order Name',
            'add_time' => 'Add Time',
            'admit_time' => 'Admit Time',
            'add_user_id' => 'Add User ID',
            'admit_user_id' => 'Admit User ID',
            'depart_id' => 'Depart ID',
            'order_sn' => 'Order Sn',
            'add_user_name' => 'Add User Name',
            'admit_user_name' => 'Admit User Name',
            'custom_id' => 'Custom ID',
            'custom_name' => 'Custom Name',
            'custom_order_status' => 'Custom Order Status',
            'remark' => 'Remark',
            'depart_name' => 'Depart Name',
            'ask_order_done_user_id' => 'Ask Order Done User ID',
            'ask_order_done_user_name' => 'Ask Order Done User Name',
            'ask_order_done_time' => '询价结束时间',
            'is_create_ask_price' => 'Is Create Ask Price',
        ];
    }

    public function getGoodsNumber(){
        $goods_number_total = $this->hasMany(B2cOrderGoods::classname(),['order_id'=>'id'])->sum('number');

        if ($goods_number_total) {
            return $goods_number_total;
        }
        return 0;
    }

    public function getCustom(){
        return $this->hasone(Custom::classname(), ['id' => 'custom_id']);
    }

    public function getSaleTotal(){
        $sale_total = $this->hasMany(B2cOrderGoods::classname(),['order_id'=>'id'])->sum('sale_price * number');
        if ($sale_total) {
            return $sale_total;
        }
        return 0;
    }
}
