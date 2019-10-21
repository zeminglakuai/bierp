<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "export_table".
 *
 * @property integer $id
 * @property string $module_name
 * @property string $type
 * @property string $data
 */
class ExportTable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'export_table';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data'], 'string'],
            [['module_name', 'type','template_name','title_module','detail_module'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'module_name' => 'Module Name',
            'type' => 'Type',
            'data' => 'Data',
            'template_name' => 'template_name', 
            'title_module' => 'title_module',
            'detail_module' => 'detail_module',
        ];
    }
}
