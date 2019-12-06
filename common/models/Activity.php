<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "activity".
 *
 * @property integer $id
 * @property string $activity_name
 * @property string $remark
 * @property string $start_time
 * @property string $end_time
 * @property string $custom_name
 */
class Activity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_name', 'remark', 'start_time', 'end_time', 'custom_name'], 'string', 'max' => 233]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_name' => 'Activity Name',
            'remark' => 'Remark',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'custom_name' => 'Custom Name',
        ];
    }
}
