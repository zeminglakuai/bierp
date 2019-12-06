<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;

class Materiel extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'materiel';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_delete'], 'integer'],
            [['materiel_price'], 'number'],            
            [['materiel_name', 'remark', 'use_to', 'unit', 'add_time', 'add_user_id', 'add_user_name', 'depart_id', 'depart_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'materiel_name' => '物料名称',
            'remark' => 'Remark',
            'use_to' => '用途',
            'unit' => '单位',
            'is_delete' => 'Is Delete',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'materiel_price' => '单价',
        ];
    }
}
