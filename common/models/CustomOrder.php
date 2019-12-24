<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;
use app\common\models\CustomOrderGoods;

class CustomOrder extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'custom_order';
    }

    public static function getSimpleCode()
    {
        return 'CUSOR';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_user_id', 'admit_user_id', 'depart_id', 'custom_id', 'platform_id', 'contact_id', 'custom_order_status', 'is_create_ask_price'], 'integer'],
            [['order_name', 'add_time', 'admit_time', 'order_sn', 'item_expire', 'add_user_name', 'admit_user_name', 'custom_name', 'remark', 'depart_name'], 'string', 'max' => 255]
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
            'custom&custom_name' => '客户',
            'custom_order_status' => '状态',
            'saleTotal' => '销售额',
            'remark' => '备注',
            'depart_name' => 'Depart Name',
            'goodsNumber' => '商品数',
            'is_create_ask_price' => '成报备单',
            'item_expire' => '报名截止时间',
            'shipping_method' => '配送方式',
            'contact_name' => '联系人',
            'platform_name' => '平台',

        ];
    }

    public function exportLabels()
    {
        return [
            'order_name' => '订单名称',
            'add_time' => '添加时间',
            'admit_time' => '审核时间',
            'order_sn' => '单据号',
            'add_user_name' => '添加人',
            'admit_user_name' => '审核人',
            'custom_name' => '客户',
            'custom_order_status' => '状态',
            'remark' => '备注',
            'depart_name' => '部门名称',
            'item_expire' => '报名截止时间',
        ];
    }

    public function getGoodsNumber()
    {
        $goods_number_total = $this->hasMany(CustomOrderGoods::classname(), ['order_id' => 'id'])->sum('number');

        if ($goods_number_total) {
            return $goods_number_total;
        }
        return 0;
    }

    public function getCustom()
    {
        return $this->hasone(Custom::classname(), ['id' => 'custom_id']);
    }

    public function getSaleTotal()
    {
        $sale_total = $this->hasMany(CustomOrderGoods::classname(), ['order_id' => 'id'])->sum('sale_price * number');
        if ($sale_total) {
            return $sale_total;
        }
        return 0;
    }
}
