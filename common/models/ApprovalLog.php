<?php

namespace app\common\models;

use Yii;

 
class ApprovalLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'approval_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'admit_user_id', 'depart_id', 'status', 'depart_type'], 'integer'],
            [['controller_label', 'admit_time', 'admit_user_name', 'depart_name', 'stauts_name', 'depart_type_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'controller_label' => 'Controller Label',
            'admit_time' => 'Admit Time',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'status' => 'Status',
            'status_name' => 'Stauts Name',
            'depart_type' => 'Depart Type',
            'depart_type_name' => 'Depart Type Name',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'controller_label' => 'Controller Label',
            'admit_time' => 'Admit Time',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'status' => 'Status',
            'stauts_name' => 'Stauts Name',
            'depart_type' => 'Depart Type',
            'depart_type_name' => 'Depart Type Name',
        ];
    }
}
