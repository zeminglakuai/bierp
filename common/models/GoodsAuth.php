<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;
use app\common\models\FileInfo;

/**
 * This is the model class for table "goods_auth".
 *
 * @property integer $id
 * @property integer $auth_supplier_id
 * @property string $auth_suppier_name
 * @property string $auth_file
 * @property string $auth_img
 * @property string $expire_time
 * @property string $add_time
 * @property integer $add_user_id
 * @property string $add_user_name
 * @property integer $depart_id
 * @property string $depart_name
 */
class GoodsAuth extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_auth';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auth_supplier_id', 'add_user_id', 'depart_id'], 'integer'],
            [['auth_suppier_name', 'auth_file', 'auth_img', 'remark', 'expire_time', 'add_time', 'add_user_name', 'depart_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'auth_supplier_id' => 'Auth Supplier ID',
            'auth_supplier_name' => 'Auth Suppier Name',
            'auth_file' => 'Auth File',
            'auth_img' => 'Auth Img',
            'expire_time' => '授权期限',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'authFile' => 'authFile',
            'remark' => 'remark',            
        ];
    }

    public function getAuthFile()
    {
        return $this->hasMany(FileInfo::classname(),['belong_id'=>'id'])->where(['model'=>'goods_auth']);
    }
}
