<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;

class ValGoods extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'val_goods';
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
            [['val_goods_id', 'belong_id', 'goods_id', 'add_user_id', 'add_user_name', 'depart_id', 'depart_name', 'val',  'admit_user_id', 'admit_user_name', 'admit_time'], 'required'],
            [['val_goods_id', 'belong_id', 'goods_id',], 'integer'],
            [['val'], 'string'],
            [['val','add_time', 'add_user_name', 'val_name', 'admit_user_name', 'admit_time'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'belong_id' => '订单id',
            'goods_id' => '商品id',      
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'admit_time' => 'Admit Time',
            'val' => '结束时间',
           
        ];
    }
   
}
