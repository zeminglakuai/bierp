<?php

namespace app\common\models;

use Yii;
use app\common\models\Admin;
/**
 * This is the model class for table "admin_auth".
 *
 * @property integer $id
 * @property integer $from_id
 * @property integer $to_id
 * @property string $expire
 */
class AdminAuth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_auth';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from_id', 'to_id'], 'integer'],
            [['expire','add_time'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from_id' => 'From ID',
            'to_id' => 'To ID',
            'expire' => 'Expire',
            'add_time' => 'add_time',
        ];
    }

    public function getFromUser(){
        return $this->hasone(Admin::className(), ['id' => 'from_id']);
    }

    public function getToUser(){
        return $this->hasone(Admin::className(), ['id' => 'to_id']);
    }


}
