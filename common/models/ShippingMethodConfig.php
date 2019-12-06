<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;
use app\common\models\FileInfo;

class ShippingMethodConfig extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shipping_method_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['basic_price', 'per_kg_price'], 'number'],
            [['basic_price', 'per_kg_price', 'area_desc'], 'required'],
            [['shipping_method_status', 'add_user_id', 'depart_id', 'admit_user_id'], 'integer'],
            [['shipping_id', 'area_desc', 'shipping_name', 'add_user_name', 'depart_name', 'admit_user_name', 'admit_time', 'add_time'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shipping_id' => 'Shipping ID',
            'area_desc' => '区域描述',
            'basic_price' => '基础价格',
            'per_kg_price' => '每公斤加价',
            'shipping_name' => '快递名称',
            'shipping_method_status' => 'Shipping Method Status',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'admit_time' => 'Admit Time',
            'add_time' => 'Add Time',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'shipping_id' => 'Shipping ID',
            'area_desc' => 'Area Desc',
            'basic_price' => 'Basic Price',
            'per_kg_price' => 'Per Kg Price',
            'shipping_name' => 'Shipping Name',
            'shipping_method_status' => 'Shipping Method Status',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'admit_time' => 'Admit Time',
            'add_time' => 'Add Time',
        ];
    }



}
