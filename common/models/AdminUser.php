<?php
namespace app\common\models;

use Yii;

/**
 * This is the model class for table "rent_admin_user".
 *
 * @property integer $id
 * @property string $admin_name
 * @property string $password
 * @property string $salt
 * @property string $tel
 * @property string $action
 * @property string $real_name
 * @property string $wx_name
 * @property string $wx_id
 * @property string $add_time
 * @property string $last_login
 * @property string $last_ip
 * @property integer $is_active
 * @property integer $depart_id
 * @property string $priv_arr
 * @property integer $type
 */
class AdminUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_name', 'password', 'salt', 'tel', 'action', 'real_name', 'wx_name', 'wx_id', 'add_time', 'last_login', 'last_ip', 'depart_id', 'priv_arr'], 'required'],
            [['action', 'priv_arr'], 'string'],
            [['is_active', 'depart_id', 'type'], 'integer'],
            [['admin_name', 'password', 'salt', 'tel', 'real_name', 'wx_name'], 'string', 'max' => 255],
            [['wx_id'], 'string', 'max' => 233],
            [['add_time', 'last_login', 'last_ip'], 'string', 'max' => 222]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'admin_name' => '用户名',
            'password' => '密码',
            'salt' => '盐',
            'tel' => '电话',
            'action' => '行为',
            'real_name' => '姓名',
            'wx_name' => '微信昵称',
            'wx_id' => '微信OPENID',
            'add_time' => '添加时间',
            'last_login' => '最后登录时间',
            'last_ip' => '最后登录IP',
            'is_active' => '有效',
            'depart_id' => '部门ID',
            'priv_arr' => '权限组',
            'type' => '类型',
        ];
    }
}
