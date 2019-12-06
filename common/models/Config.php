<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "rent_config".
 *
 * @property integer $id
 * @property string $name
 * @property string $value
 * @property string $tips
 * @property string $desc
 * @property integer $visiable
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'value', 'tips', 'desc', 'visiable'], 'required'],
            [['visiable'], 'integer'],
            [['name', 'value', 'tips', 'desc'], 'string', 'max' => 233]
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
            'tips' => 'Tips',
            'desc' => 'Desc',
            'visiable' => 'Visiable',
        ];
    }
    public function getName($id){
        $id=86;
//        $customers = Yii::$app->db->createCommand("SELECT * FROM config where id=".$id)->queryAll();
        $query = new \yii\db\Query();

        $s=$query->select('value')->from('config')->where(['id'=>$id])->one();
        return $s['value'];
    }

}
