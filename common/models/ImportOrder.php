<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;
use app\common\models\Supplier;
use app\common\models\Store;

class ImportOrder extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'import_order';
    }

    public static function getSimpleCode()
    {
        return 'IMOR';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_user_id', 'admit_user_id', 'depart_id','store_id', 'supplier_id','sell_order_id'], 'integer'],
            [['order_name', 'add_time', 'admit_time', 'order_sn','supplier_name','store_name', 'add_user_name', 'admit_user_name', 'remark', 'depart_name', 'consinee', 'tel', 'address', 'sell_order_sn', 'custom_id', 'custom_name'], 'string', 'max' => 255],
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
            'consinee' => 'Consinee',
            'tel' => 'Tel',
            'address' => 'Address',
            'purchase_id' => 'Sell Order ID',
            'purchase_sn' => '采购单编号',
            'supplier_id' => 'supplier_id',
            'supplier_name' => 'supplier_name',
            'store_id'=>'store_id',
            'import_order_status'=>'',
            'store_name'=>'收货仓库',
        ];
    }
 
    public function getSupplier(){
        return $this->hasone(Supplier::classname(),['id'=>'custom_id']);
    }

    public function getPurchase(){
        return $this->hasone(Purchase::classname(),['id'=>'purchase_id']);
    }
    public function getContract(){
        return $this->hasone(Contract::classname(),['order_id'=>'id']);
    }

//    public function getStore(){
//        return $this->hasone(Store::classname(),['id'=>'store_id']);
//    }
}
