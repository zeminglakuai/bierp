<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "help".
 *
 * @property integer $id
 * @property string $model
 * @property string $content
 */
class Help extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'help';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['model','module'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model' => 'Model',
            'content' => 'Content',
            'module' => 'module',            
        ];
    }
}
