<?php

namespace app\common\models;

use Yii;

use app\common\models\ContactExtendInfo;
use app\common\models\Depart;
use app\common\models\Role;
/**
 * This is the model class for table "kw_admin".
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
 */
class Admin extends \yii\db\ActiveRecord
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
            [['is_active', 'depart_id', 'role_id','custom_id','supplier_id'], 'integer'],
            [['admin_name', 'password', 'salt', 'tel', 'action', 'real_name', 'wx_name', 'priv_arr','custom_name','supplier_name','english_name'], 'string', 'max' => 255],
            [['wx_id'], 'string', 'max' => 233],
            [['add_time', 'last_login_time', 'last_ip','desc'], 'string', 'max' => 222]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'admin_name' => 'Admin Name',
            'password' => 'Password',
            'salt' => 'Salt',
            'tel' => 'Tel',
            'action' => 'Action',
            'real_name' => 'Real Name',
            'wx_name' => 'Wx Name',
            'wx_id' => 'Wx ID',
            'add_time' => 'Add Time',
            'last_login_time' => 'Last Login',
            'last_ip' => 'Last Ip',
            'is_active' => 'Is Active',
            'depart_id' => 'Depart ID',
            'priv_arr' => 'Priv Arr',
            'role_id' => 'Priv Arr',
            'desc' => 'desc',
            'type' => '类型',
            'custom_id' => 'custom_id',
            'custom_name' => '客户名称',
            'supplier_id' => 'supplier_id',
            'supplier_name' => '供货商名称',
            'english_name' => '英文名',
            'role&role_name' => '角色', 
            'depart&depart_name' => '部门',
        ];
    }

    public function getDepart(){
        return   $this->hasOne(Depart::className(), ['id' => 'depart_id']);
    }

    public function getRole(){
        return   $this->hasOne(Role::className(), ['id' => 'role_id']);
    }

    public function getExtendInfo(){
        return   $this->hasMany(UserExtendInfo::className(), ['contact_id' => 'id']);
    }
}
