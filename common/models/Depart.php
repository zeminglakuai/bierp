<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "depart".
 *
 * @property integer $id
 * @property string $depart_name
 * @property integer $parent_id
 * @property string $depart_desc
 */
class Depart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'depart';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['depart_name'], 'required'],
            [['parent_id','type','store_id'], 'integer'],
            [['depart_name','depart_desc'], 'string', 'max' => 244],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'depart_name' => 'Depart Name',
            'parent_id' => 'Parent ID',
            'store_id' => 'store_id',
            'depart_desc' => 'Depart Desc',
            'type' => 'type',
        ];
    }

    //得到部分列表  并处理成树状
    public static function get_depart_tree(){
        $depart = static::find()->all();
        $depart_temp = [];
        if ($depart) {
            foreach ($depart as $key => $value) {
               $depart_temp[$value->id] = ['id'=>$value->id,'text'=>$value->depart_name,'parent'=>$value->parent_id];  
            }
            $depart_list = static::genTree($depart_temp);
            return $depart_list;
        }
    }

    //得到部分列表  并处理成树状
    public static function get_depart_select($select_name,$selected_id){
        $depart = static::get_depart_tree();
        $select_str = static::recursion_depart_select($depart,$select_name,'-',$selected_id);
        return $select_str;
    }
    
    public static function recursion_depart_select($arr,$select_name,$level,$selected_id){
        if(strlen($level) == 1){
            $mul = (isset($selected_id) && is_array($selected_id))?'multiple="multiple"':'';
            $str = '<select class="form-control" name="'.$select_name.'" id="depart_select_list"'.$mul.'>';
            
            if (is_array($selected_id)) {
               $str .= '<option value="0" '.((in_array('0', $selected_id))?'selected="selected"':'').'>全部部门</option>';
            }else{
               $str .= '<option value="0" '.(($selected_id == '0')?'selected="selected"':'').'>全部部门</option>';
            }
        }
        $level = $level.$level[0];
        foreach ($arr as $key => $value) {
            $value_len = count($arr);
            if (is_array($selected_id)) {
               $str .= '<option value="'.$value['id'].'" '.((in_array($value['id'], $selected_id))?'selected="selected"':'').'>'.substr($level,0,mb_strlen($level)-2).$value['text'].'</option>'; 
            }else{
               $str .= '<option value="'.$value['id'].'" '.(($selected_id == $value['id'])?'selected="selected"':'').'>'.substr($level,0,mb_strlen($level)-2).$value['text'].'</option>'; 
            }
            if (isset($value['children'])) {
                $str .= static::recursion_depart_select($value['children'],$select_name,$level,$selected_id);
            }
        }
        if(strlen($level) == 2){
          $str .= '</select>';  
        }
        return $str;
    }

    public static function genTree($items) {
        foreach ($items as $item)
            $items[$item['parent']]['children'][$item['id']] = &$items[$item['id']];
 
        return isset($items[0]['children']) ? $items[0]['children'] : array();
    }

}
