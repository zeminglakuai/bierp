<?php

namespace app\common\models;

use Yii;

class Teamwork extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $attach;
    
    public static function tableName()
    {
        return 'teamwork';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['title', 'cate', 'add_time', 'add_user_id', 'add_user_name','main_nav','sub_nav','is_archive','attach'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'cate' => 'Cate',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'main_nav' => 'main_nav',
            'sub_nav' => 'sub_nav',
            'is_archive' => 'archive',
            'attach' => '附件',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'cate' => 'Cate',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
        ];
    }
}
