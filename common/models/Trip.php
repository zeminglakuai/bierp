<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;
class Trip extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'trip';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'triper_id', 'add_user_id', 'depart_id', 'status_done'], 'integer'],
            [['triper_name', 'position', 'reason', 'trip_point', 'start_time', 'end_time', 'live_start_time','live_end_time', 'vehicle', 'hotel', 'remark', 'add_time', 'add_user_name', 'depart_name', 'status_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'triper_id' => '出差人',
            'triper_name' => '出差人',            
            'position' => '职位',
            'reason' => '原因',
            'trip_point' => '差旅地点',
            'start_time' => '差旅开始时间',
            'end_time' => '差旅结束时间',
            'live_start_time' => '住宿开始时间',
            'live_end_time' => '住宿结束时间',
            'vehicle' => '交通工具',
            'hotel' => '酒店',
            'remark' => 'Remark',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'status_name' => 'Status Name',
            'status_done' => 'Status Done',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'triper' => '出差人',
            'position' => '职位',
            'reason' => '原因',
            'trip_point' => '差旅地点',
            'trip_time' => '差旅时间',
            'live_time' => '住宿时间',
            'vehicle' => '交通工具',
            'hotel' => '酒店',
            'remark' => 'Remark',
            'add_time' => 'Add Time',
            'add_user_name' => 'Add User Name',
            'depart_name' => 'Depart Name',
        ];
    }
}
