<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;

class Mession extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mession';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mession_depart_id', 'add_user_id', 'depart_id', 'year', 'month', 'admit_user_id'], 'integer'],
            [['sale_price'], 'number'],
            [['mession_depart_name', 'add_time', 'add_user_name', 'depart_name', 'sale_amount','mession_name', 'remark', 'admit_user_name', 'admit_time','mession_data'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mession_depart_id' => 'Mession Depart ID',
            'mession_depart_name' => '部门',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'year' => '年',
            'month' => '月',
            'sale_amount' => 'Sale Amount',
            'remark' => 'Remark',
            'sale_price' => 'Sale Price',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'admit_time' => 'Admit Time',
            'mession_name' => '名称',
            'mession_data' => '数据',
            'mession_status' => '状态',
        ];
    }
}
