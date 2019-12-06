<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "dictionary_value".
 *
 * @property integer $id
 * @property integer $dictionary_id
 * @property string $dictionary_value
 */
class DictionaryValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dictionary_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dictionary_id'], 'integer'],
            [['dictionary_value'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dictionary_id' => 'Dictionary ID',
            'dictionary_value' => '属性值',
            'sort' => '排序',
        ];
    }

    public static function getValueList($id)
    {
        return static::find()->where(['dictionary_id'=>$id])->all();
    }

}
