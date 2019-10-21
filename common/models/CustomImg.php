<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "custom_img".
 *
 * @property integer $id
 * @property integer $custom_id
 * @property string $desc
 * @property string $img
 * @property string $add_time
 */
class CustomImg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'custom_img';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'custom_id'], 'integer'],
            [['desc', 'img', 'add_time'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'custom_id' => 'Custom ID',
            'desc' => 'Desc',
            'img' => 'Img',
            'add_time' => 'Add Time',
        ];
    }
}
