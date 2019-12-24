<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "contact".
 *
 * @property integer $id
 * @property string $name
 * @property string $tel
 * @property string $position
 * @property string $remark
 * @property integer $belong_id
 * @property string $belong_name
 * @property string $model
 * @property string $add_time
 * @property integer $is_active
 * @property string $service_open_id
 * @property string $qy_open_id
 */
class Contact extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['belong_id', 'is_active'], 'integer'],
            [['name', 'tel', 'position', 'remark', 'belong_name', 'model', 'add_time', 'service_open_id', 'qy_open_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'tel' => 'Tel',
            'position' => 'Position',
            'remark' => 'Remark',
            'belong_id' => 'Belong ID',
            'belong_name' => 'Belong Name',
            'model' => 'Model',
            'add_time' => 'Add Time',
            'is_active' => 'Is Active',
            'service_open_id' => 'Service Open ID',
            'qy_open_id' => 'Qy Open ID',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'tel' => 'Tel',
            'position' => 'Position',
            'remark' => 'Remark',
            'belong_id' => 'Belong ID',
            'belong_name' => 'Belong Name',
            'model' => 'Model',
            'add_time' => 'Add Time',
            'is_active' => 'Is Active',
            'service_open_id' => 'Service Open ID',
            'qy_open_id' => 'Qy Open ID',
        ];
    }

    public function getExtendInfo()
    {
        return   $this->hasMany(UserExtendInfo::className(), ['contact_id' => 'id']);
    }
    public function getInfo($id)
    {
        // $data = Yii::$app->db->createCommand("select * from contact where id=" . $id)->queryOne();

        // return $data['belong_name'] . '：' . $data['name'];
    }
}
