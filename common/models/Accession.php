<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;
class Accession extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accession';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'add_user_id', 'depart_id', 'status_done', 'accession_status'], 'integer'],
            [['description'], 'string'],
            [['real_name', 'remark', 'add_time', 'add_user_name', 'depart_name', 'status_name', 'accession_time'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'real_name' => 'Real Name',
            'remark' => 'Remark',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'status_name' => 'Status Name',
            'status_done' => 'Status Done',
            'accession_status' => 'Accession Status',
            'accession_time' => 'Accession Time',
            'description' => 'Description',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'real_name' => 'Real Name',
            'remark' => 'Remark',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'status_name' => 'Status Name',
            'status_done' => 'Status Done',
            'accession_status' => 'Accession Status',
            'accession_time' => 'Accession Time',
            'description' => 'Description',
        ];
    }

    //合同
    public function getFiles()
    {   
        return $this->hasMany(FileInfo::className(), ['belong_id' => 'id'])->where(['model'=>$this->tableName()]);
    }

}
