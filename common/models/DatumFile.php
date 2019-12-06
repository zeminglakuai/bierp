<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "datum_file".
 *
 * @property integer $id
 * @property integer $datum_id
 * @property string $desc
 * @property string $file
 * @property string $add_time
 */
class DatumFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'datum_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['datum_id'], 'integer'],
            [['desc', 'file', 'add_time'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'datum_id' => 'Datum ID',
            'desc' => 'Desc',
            'file' => 'File',
            'add_time' => 'Add Time',
        ];
    }
}
