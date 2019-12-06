<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;

class FileInfo extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'file_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['belong_id', 'add_user_id', 'depart_id'], 'integer'],
            [['file_path', 'file_desc', 'add_time', 'add_user_name', 'depart_name','model','type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'belong_id' => 'Belong ID',
            'file_path' => 'File Path',
            'file_desc' => 'File Desc',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'model' => 'model',
            'type' => 'type',
        ];
    }
}
