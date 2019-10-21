<?php

namespace app\common\models;

use Yii;
use app\common\models\Admin;

/**
 * This is the model class for table "role".
 *
 * @property integer $id
 * @property string $role_name
 * @property string $role_desc
 * @property integer $depart_id
 * @property string $action
 * @property string $priv_arr
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['depart_id','role_type'], 'integer'],
            [['action', 'priv_arr'], 'string'],
            [['role_name'], 'string', 'max' => 222],
            [['role_desc'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_name' => 'Role Name',
            'role_desc' => 'Role Desc',
            'depart_id' => 'Depart ID',
            'action' => 'Action',
            'priv_arr' => 'Priv Arr',
            'role_type' => 'role_type',            
        ];
    }

    public function getRoleUserNumber(){
        return   $this->hasMany(Admin::className(), ['role_id' => 'id'])->count();
    }

    public static function get_role_select($select_name,$role_id){
        $Role = static::find()->where(['id'=>$role_id])->one();
        if (isset($Role->depart_id) && $Role->depart_id > 0) {
            $str = '<select class="form-control" name="'.$select_name.'">';
            $str .= '<option value="0">请选择角色</option>';
            $role_list = static::findall(['depart_id'=>$Role->depart_id]);
            foreach ($role_list as $key => $value) {
                $str .= '<option value="'.$value->id.'" '.(($role_id == $value->id)?'selected="selected"':'').'>'.$value->role_name.'</option>';;
            }
            $str .= '</select>';
            return $str;
        }else{
            return false;
        }
        
    }
 

}
