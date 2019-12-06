<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;
use app\common\models\CustomOrder;

class AskPriceOrder extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ask_price_order';
    }
    public static function getSimpleCode()
    {
        return 'ASKOR';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_user_id', 'depart_id', 'ask_price_order_status','supplier_id'], 'integer'],
            [['order_sn','order_name', 'custom_order_id','area', 'add_time','supplier_name', 'add_user_name', 'depart_name','access_secrect'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_sn' => '单据号',
            'order_name' => '',
            'custom_order_id' => 'Custom Order ID',
            'add_time' => '添加时间',
            'add_user_id' => 'Add User ID',
            'add_user_name' => '添加人',
            'depart_id' => 'Depart ID',
            'depart_name' => '部门名称',
            'ask_price_order_status' => '报备单状态',
            'supplier_id' => 'supplier_id',
            'supplier_name' => '供货商名称',
            'customOrder&order_name' => '客户方案名称',
            'customOrder&order_sn' => '客户方案单号',
            'admit_user_id' => '复核用户ID',
            'admit_user_name' => '复核用户名',
            'admit_time' => '复核时间',
            'access_secrect' => '访问密码',
            'goodsNumber' => '商品数量',
            'area' => '区域',            
        ];
    }

    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'order_sn' => '单据号',
            'add_time' => '添加时间',
            'add_user_name' => '添加人',
            'depart_name' => '部门名称',
            'ask_price_order_status' => '报备单状态',
            'supplier_name' => '供货商名称',
            'customOrder&order_name' => '客户方案名称',
            'customOrder&order_sn' => '客户方案单号',
            'admit_user_name' => '复核用户名',
            'admit_time' => '复核时间',
            'access_secrect' => '访问密码',
            'goodsNumber' => '商品数量',
        ];
    }


    public function getCustomOrder(){
        return $this->hasone(CustomOrder::classname(),['id'=>'custom_order_id']);
    }

    public function getGoodsNumber(){
        return $this->hasMany(AskPriceOrderGoods::classname(),['order_id'=>'id'])->sum('number');
    }

}
