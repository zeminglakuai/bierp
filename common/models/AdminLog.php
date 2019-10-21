<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "kw_admin_log".
 *
 * @property integer $id
 * @property integer $admin_id
 * @property integer $ano_id
 * @property string $model
 * @property string $param
 * @property string $action
 * @property string $add_time
 */
class AdminLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_id', 'ano_id', 'model', 'param', 'action', 'add_time'], 'required'],
            [['admin_id', 'ano_id'], 'integer'],
            [['param'], 'string'],
            [['model'], 'string', 'max' => 244],
            [['action', 'add_time'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'admin_id' => 'Admin ID',
            'ano_id' => 'Ano ID',
            'model' => 'MODEL',
            'param' => '操作参数',
            'action' => 'action',
            'add_time' => '操作时间',
        ];
    }
}
