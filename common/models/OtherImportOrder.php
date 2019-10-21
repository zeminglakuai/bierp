<?php

namespace app\common\models;

use Yii;

use app\common\models\BaseModel;
class OtherImportOrder extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'other_import_order';
    }

    public static function getSimpleCode()
    {
        return 'OIMPORT';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_user_id', 'admit_user_id', 'depart_id', 'relate_order_id', 'store_id', 'other_import_order_status', 'relate_order_type', 'other_import_order_type', 'sell_order_return_id'], 'integer'],
            [['add_time', 'admit_time', 'order_sn', 'add_user_name','custom_name','custom_id','store_name', 'admit_user_name', 'remark', 'depart_name', 'relate_order_sn', 'sell_order_return_sn'], 'string', 'max' => 255],
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
            'relate_order_id' => 'Relate Order ID',
            'relate_order_sn' => 'Relate Order Sn',
            'store_id' => 'Store ID',
            'other_import_order_status' => 'Other Import Order Status',
            'relate_order_type' => 'Relate Order Type',
            'other_import_order_type' => '其他入库单类型',
            'sell_order_return_id' => 'Sell Order Return ID',
            'sell_order_return_sn' => '退货单单号',
            'store_name' => '收货仓库',
            'custom_name' => '客户名称',
            'custom_id' => '收货仓库',            
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
            'relate_order_id' => 'Relate Order ID',
            'relate_order_sn' => 'Relate Order Sn',
            'store_id' => 'Store ID',
            'other_import_order_status' => 'Other Import Order Status',
            'relate_order_type' => 'Relate Order Type',
            'other_import_order_type' => '其他入库单类型',
            'sell_order_return_id' => 'Sell Order Return ID',
            'sell_order_return_sn' => 'Sell Order Return Sn',
        ];
    }
}
