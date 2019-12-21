<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;

class Theme extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'theme';
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
            [['theme_id','belong_id'], 'integer'],
            [['theme_name','start_time','end_time', 'remark'], 'string'],
            [['theme_name','add_time', 'add_user_name', 'remark', 'admit_user_name', 'admit_time', 'custom_name', 'contract_name','end_time', 'start_time'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'theme_id' => 'ID',
            'theme_name' => '主题名称',      
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'remark' => 'Remark',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'admit_time' => 'Admit Time',
            'end_time' => '结束时间',
            'start_time' => '开始时间',
        ];
    }
    public function getThemetFile()
    {
        return $this->hasMany(FileInfo::classname(),['belong_id'=>'theme_id'])->where(['model'=>'theme']);
    }
    public function getPlat_name(){

        return $this->hasOne(Platform::className(), ['id' => 'belong_id']);

    }
}
