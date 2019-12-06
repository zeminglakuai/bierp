<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "export_ppt_table".
 *
 * @property integer $id
 * @property string $template_name
 * @property string $module_name
 * @property string $module
 * @property string $data
 */
class ExportPptTable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'export_ppt_table';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data'], 'string'],
            [['template_name', 'module_name', 'module','page_title','page_face','page_back','title_module'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'template_name' => 'Template Name',
            'module_name' => 'Module Name',
            'module' => 'Module',
            'data' => 'Data',
            'page_title' => 'page_title',
            'page_face' => 'page_face',
            'page_back' => 'page_back',
            'title_module' => 'title_module',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'template_name' => 'Template Name',
            'module_name' => 'Module Name',
            'module' => 'Module',
            'data' => 'Data',
        ];
    }
}
