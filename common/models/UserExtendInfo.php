<?php

namespace app\common\models;

use Yii;
use app\common\models\DictionaryValue;

/**
 * This is the model class for table "contact_extend_info".
 *
 * @property integer $id
 * @property integer $filed_id
 * @property string $filed_value
 * @property integer $contact_id
 */
class UserExtendInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_extend_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filed_id', 'contact_id'], 'integer'],
            [['filed_value'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filed_id' => 'Filed ID',
            'filed_value' => 'Filed Value',
            'contact_id' => 'Contact ID',
        ];
    }

    public function getFiledName(){
        return   $this->hasOne(DictionaryValue::className(), ['id' => 'filed_id']);
    }
}
