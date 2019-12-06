<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;
use app\common\models\Custom;
class ExportOrder extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'export_order';
    }

    public static function getSimpleCode()
    {
        return 'EXOR';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_user_id', 'admit_user_id', 'depart_id', 'sell_order_id','export_order_status','store_id'], 'integer'],
            [['materiel_fee', 'shipping_fee'], 'number'],
            [['order_name', 'add_time', 'admit_time','store_name', 'shipping_code','order_sn', 'add_user_name','shipping_method', 'admit_user_name', 'remark', 'depart_name', 'consignee', 'tel', 'address', 'sell_order_sn', 'custom_id', 'custom_name'], 'string', 'max' => 255],
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
            'add_user_id' => 'Add User ID',
            'admit_user_id' => 'Admit User ID',
            'depart_id' => 'Depart ID',
            'order_sn' => 'Order Sn',
            'add_user_name' => 'Add User Name',
            'admit_user_name' => 'Admit User Name',
            'remark' => 'Remark',
            'depart_name' => 'Depart Name',
            'consignee' => 'Consinee',
            'tel' => 'Tel',
            'address' => 'Address',
            'sell_order_id' => 'Sell Order ID',
            'sell_order_sn' => 'Sell Order Sn',
            'custom_id' => 'Custom ID',
            'custom_name' => 'Custom Name',
            'materiel_fee' => '物料费用',
            'shipping_fee' => '运输费用',
            'shipping_code' => '发货单号',
            'store_name' => '出库仓库',
            'store_id' => '',
            'export_order_status' => '',
            'shipping_method' => '',
        ];
    }

    public function getCustom(){
        return $this->hasone(Custom::classname(),['id'=>'custom_id']);
    }

    public function getContract(){
        return $this->hasone(Contract::classname(),['order_id'=>'id']);
    }

}
