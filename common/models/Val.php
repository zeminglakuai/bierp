<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;

class Val extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'val';
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
            [['id', 'order_id', 'val_name', 'add_user_id', 'add_user_name', 'depart_id', 'depart_name', 'val', 'val_name_en', 'admit_user_id', 'admit_user_name', 'admit_time'], 'required'],
            [['id', 'order_id'], 'integer'],
            [['val_name', 'val', 'val_name_en'], 'string'],
            [['val_name','add_time', 'add_user_name', 'val_name', 'admit_user_name', 'admit_time', 'val'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => '订单id',      
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'val_name' => 'Remark',
            'val_name_en' => 'val_name_en',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'admit_time' => 'Admit Time',
            'val' => '结束时间',
           
        ];
    }
   
}
