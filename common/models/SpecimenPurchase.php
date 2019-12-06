<?php

namespace app\common\models;

use Yii;

use app\common\models\BaseModel;
use app\common\models\SpecimenPurchaseGoods;
class SpecimenPurchase extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'specimen_purchase';
    }

    public static function getSimpleCode()
    {
        return 'SPECPUR';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['supplier_id', 'specimen_purchase_status', 'admit_user_id', 'add_user_id', 'depart_id', 'return_status'], 'integer'],
            [['add_time', 'admit_time', 'order_sn', 'supplier_name','if_return','return_time','remind_return_time','status_name','pay_method','shipping_method', 'remark', 'admit_user_name', 'add_user_name', 'depart_name'], 'string', 'max' => 255],
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
            'specimen_purchase_status' => '样品采购单状态',
            'remark' => 'Remark',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'return_status' => '退回状态',
            'goodsNumber' => '商品数量',
            'pay_method' => '支付方式', 
            'shipping_method' => '发货方式',
            'status_name' => '状态名称',
            'status_done' => '审批结束',
            'if_return' => '是否需要退货',
            'remind_return_time' => '提醒退货时间',
            'return_time' => '退货时间',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'add_time' => 'Add Time',
            'admit_time' => 'Admit Time',
            'order_sn' => 'Order Sn',
            'supplier_id' => 'Supplier ID',
            'supplier_name' => 'Supplier Name',
            'specimen_purchase_status' => 'Specimen Purchase Status',
            'remark' => 'Remark',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'return_status' => 'Return Status',
        ];
    }

    public function getGoodsNumber(){
        $goods_number_total = $this->hasMany(SpecimenPurchaseGoods::classname(),['order_id'=>'id'])->sum('number');
        if ($goods_number_total) {
            return $goods_number_total;
        }
        return 0;
    }    
}
