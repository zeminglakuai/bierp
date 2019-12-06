<?php

namespace app\common\models;

use Yii;
use app\common\models\DatumCate;
use app\common\models\DatumFlie;
use app\common\models\BaseModel;
use app\common\models\FileInfo;

class Datum extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'datum';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_user_id', 'depart_id', 'is_private','cat_id','is_delete'], 'integer'],
            [['datum_name', 'content', 'file_path', 'add_time', 'add_user_name', 'depart_name', 'scope'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'datum_name' => '文件名称',
            'content' => '内容',
            'file_path' => '文件路径',
            'add_time' => '添加时间',
            'add_user_id' => '添加用户',
            'add_user_name' => '添加用户名称',
            'depart_id' => '部门ID',
            'depart_name' => '部门名称',
            'scope' => '可见范围',
            'is_private' => '是否私人文件',
            'cat_id' => '分类ID',
            'datumCat&cat_name' => '分类',
            'datumFile' => '分类',
            'is_delete' => '分类',
        ];
    }

    public function getDatumFile(){
        return $this->hasMany(FileInfo::classname(),['belong_id'=>'id'])->andwhere(['model'=>'datum']);
    }

    public function getDatumCat(){
        return $this->hasone(DatumCate::classname(),['cat_id'=>'cat_id']);
    }
}
