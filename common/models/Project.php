<?php

namespace app\common\models;

use Yii;


use app\common\models\BaseModel;

class Project extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id'], 'integer'],
            [['project_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_id' => 'ID',
            'project_name' => 'name',
        ];
    }
    public static function getValueList()
    {
        return static::find()->all();
    }

    

   

}
