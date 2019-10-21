<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "qywehcat_user".
 *
 * @property integer $id
 * @property string $open_id
 * @property string $union_id
 * @property string $nick_name
 * @property string $sex
 * @property string $avatar
 * @property string $depart_id
 * @property string $user_id
 * @property string $add_time
 * @property integer $admin_id
 * @property integer $is_subcrib
 * @property string $subcrib_time
 * @property string $english_name
 * @property string $mobile
 * @property string $email
 * @property string $enable
 * @property string $avatar_mediaid
 * @property string $telephone
 */
class QywehcatUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qywehcat_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_id', 'is_subcrib'], 'integer'],
            [['open_id', 'union_id', 'nick_name', 'sex', 'avatar', 'depart_id', 'user_id', 'add_time', 'subcrib_time', 'english_name', 'mobile', 'email', 'enable', 'avatar_mediaid', 'telephone'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'open_id' => 'Open ID',
            'union_id' => 'Union ID',
            'nick_name' => '姓名',
            'sex' => 'Sex',
            'avatar' => 'Avatar',
            'depart_id' => 'Depart ID',
            'user_id' => 'User ID',
            'add_time' => 'Add Time',
            'admin_id' => 'Admin ID',
            'is_subcrib' => '关注',
            'subcrib_time' => '关注事件',
            'english_name' => 'English Name',
            'mobile' => '手机号',
            'email' => 'Email',
            'enable' => 'Enable',
            'avatar_mediaid' => 'Avatar Mediaid',
            'telephone' => '手机号',
            'adminUser&admin_name' => '关联管理员',
        ];
    }

    public function getAdminUser(){
        return $this->hasMany(Admin::classname(),['id'=>'admin_id']);
    }
}
