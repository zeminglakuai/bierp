<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "qywechat_config".
 *
 * @property integer $id
 * @property string $name
 * @property string $value
 * @property string $expire
 * @property integer $parent_id
 * @property string $desc
 */
class QywechatConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qywechat_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id','visiable'], 'integer'],
            [['name', 'value', 'expire', 'desc'], 'string', 'max' => 255]
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
            'value' => 'Value',
            'expire' => 'Expire',
            'parent_id' => 'Parent ID',
            'desc' => 'Desc',
            'visiable' =>'visiable'
        ];
    }

    static function get_config($parent_id){
        $config = [];
        $temp_config = self::find()->asarray()->where(['parent_id'=>$parent_id])->all();
        foreach ($temp_config as $key => $value) {
            $config[$value['name']] = $value['value'];
        }
        return $config;
    }

    static function get_secrect($agent_data_id){
        $secrect = '';
        $temp_secrect = self::find()->where(['parent_id'=>$agent_data_id,'name'=>'secret'])->one();
        if ($temp_secrect) {
           return $temp_secrect->value;
        }
        return false;
    }

}
