<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "store".
 *
 * @property integer $id
 * @property string $store_name
 * @property string $address
 * @property string $desc
 * @property string $contact
 * @property string $tel
 */
class Store extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_name', 'address', 'desc', 'contact', 'tel'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_name' => 'Store Name',
            'address' => 'Address',
            'desc' => 'Desc',
            'contact' => 'Contact',
            'tel' => 'Tel',
        ];
    }

    //得到仓库列表  并处理成树状
    public static function get_store_select($select_name){
        $store_list = static::find()->all();
        $str = '<select  class="form-control" name="'.$select_name.'">';
        $str .= '<option value="0">请选择仓库</option>';
        foreach ($store_list as $key => $value) {
            $str .= '<option value="'.$value->id.'">'.$value->store_name.'</option>';
        }
        $str .= '</select>';
        return $str;
    }

    //得到仓库列表 
    public static function get_store(){
        if (isset(Yii::$app->session['manage_user']['store_id']) && Yii::$app->session['manage_user']['store_id'] > 0) {
            $store_list = static::findone(Yii::$app->session['manage_user']['store_id']);
        }else{
            $store_list = static::find()->all();
        }
        return $store_list;
    }
    public function getName($id){
        $query = new \yii\db\Query();
        $s=$query->select('store_name')->from('store')->where(['id'=>$id])->one();
        return $s['store_name'];
    }
}
