<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "leave".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $real_name
 * @property string $user_depart_id
 * @property string $user_depart_name
 * @property integer $add_user_id
 * @property string $add_time
 * @property string $add_user_name
 * @property integer $depart_id
 * @property string $depart_name
 * @property string $leave_reason
 * @property string $start_time
 * @property string $end_time
 * @property string $remark
 */
class Leave extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'leave';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'add_user_id', 'depart_id'], 'integer'],
            [['real_name', 'user_depart_id', 'user_depart_name', 'add_time', 'add_user_name', 'depart_name', 'leave_reason', 'start_time', 'end_time', 'remark'], 'string', 'max' => 255],
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
            'user_depart_id' => 'User Depart ID',
            'user_depart_name' => 'User Depart Name',
            'add_user_id' => 'Add User ID',
            'add_time' => 'Add Time',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'leave_reason' => 'Leave Reason',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'remark' => 'Remark',
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
            'user_depart_id' => 'User Depart ID',
            'user_depart_name' => 'User Depart Name',
            'add_user_id' => 'Add User ID',
            'add_time' => 'Add Time',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'leave_reason' => 'Leave Reason',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'remark' => 'Remark',
        ];
    }
}
