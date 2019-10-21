<?php

namespace app\common\models;

use Yii;
use app\common\models\DictionaryValue;
/**
 * This is the model class for table "dictionary".
 *
 * @property integer $id
 * @property string $dictionary_name
 * @property string $dictionary_desc
 * @property string $key
 */
class Dictionary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dictionary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dictionary_name', 'dictionary_desc', 'key'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dictionary_name' => 'Dictionary Name',
            'dictionary_desc' => '字典数据名称',
            'key' => 'Key',
            'edit_able' => '可编辑',
        ];
    }

    public function getDictionaryvalue()
    {
        return $this->hasMany(DictionaryValue::className(), ['dictionary_id' => 'id']);
    }
}
