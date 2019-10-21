<?php

namespace app\common\models;

use Yii;
use app\common\models\SellOrder;
use app\common\models\Purchase;
use app\common\models\FileInfo;
use app\common\models\BaseModel;

class Contract extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contract';
    }

    public static function getSimpleCode()
    {
        return 'CONT';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_sn', 'order_id', 'add_time', 'add_user_id', 'add_user_name', 'depart_id', 'depart_name', 'remark', 'admit_user_id', 'admit_user_name', 'admit_time', 'custom_id', 'custom_name'], 'required'],
            [['add_user_id', 'depart_id', 'admit_user_id', 'contract_status', 'type', 'custom_id','template_id','keeper_user_id'], 'integer'],
            [['content'], 'string'],
            [['order_sn', 'order_id', 'add_time', 'add_user_name', 'depart_name', 'remark', 'admit_user_name','keeper_user_name','keep_time', 'admit_time', 'custom_name', 'contract_name', 'relate_order_sn'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_sn' => '合同编号',
            'template_id' => '合同模板',
            'template_name' => '模板名称',            
            'order_id' => 'Order ID',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'remark' => 'Remark',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'admit_time' => 'Admit Time',
            'contract_status' => '合同状态',
            'type' => 'Type',
            'custom_id' => 'custom_id',
            'sellOrder&order_sn' => '销售单编号',
            'purchase&order_sn' => '采购单编号',
            'custom_name' => 'custom_name',
            'contract_name' => '合同名称',
            'content' => '合同内容',
            'relate_order_sn' => '相关单号',
            'supplier_id' => ' ',
            'supplier_name' => ' ',
            'keeper_user_id' => '',
            'keeper_user_name' => '回收保存人',
            'keep_time' => '回收时间',
        ];
    }

    public function getSellOrder()
    {
        return $this->hasone(SellOrder::classname(),['id'=>'order_id']);
    }  

    public function getPurchase()
    {
        return $this->hasone(Purchase::classname(),['id'=>'order_id']);
    }

    public function getContractFile()
    {
        return $this->hasMany(FileInfo::classname(),['belong_id'=>'id'])->andwhere(['model'=>'contract']);
    }

}
