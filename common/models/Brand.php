<?php

namespace app\common\models;

use Yii;
use app\includes\Pinyin;
use app\common\models\BrandSupplier;
use app\common\models\Supplier;
/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $brand_name
 * @property integer $supplier_id
 * @property string $remark
 */
class Brand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    public static function tableNameLabel()
    {
        return '品牌';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['supplier_id','self_sell','is_self_sell'], 'integer'],
            [['brand_name'], 'string', 'max' => 60],
            [['remark','add_user_id','add_user_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand_name' => '品牌名称',
            'supplier_id' => '供货商ID',
            'supplier_name' => '供货商名称',
            'remark' => '备注',
            'add_user_id' => '添加用户ID',
            'add_user_name' => '添加用户', 
            'is_self_sell' => '自有品牌', 
            'suppliers' => '供货商',   
        ];
    }
    
    public function getBrandSupplier(){
        return $this->hasMany(BrandSupplier::className(), ['brand_id' => 'id']);
    }

    public function getSuppliers(){
        return $this->hasMany(Supplier::className(), ['id' => 'supplier_id'])
            ->viaTable(BrandSupplier::tableName(),['brand_id'=>'id']);
    }
    
    public function get_select_list()
    {   
    //填充第一条为 请选择供货商
        $list_1 = [0=>['id'=>'0','brand_name'=>'选择品牌']];
        $list  =  self::get_list();

        //处理每条数据前 加上首字母
        foreach ($list as $key => $value) {
            $list[$key]['brand_name'] = Pinyin::encode($value['brand_name']).'-'.$value['brand_name'];
        }
        
        $new_list = array_merge($list_1,$list);
        
        return $new_list;
    }


    public function get_list()
    {
        $query = new \yii\db\Query();
        $list  =  $query->select('*')
                        ->from('brand')
                        ->orderby(['CONVERT( brand_name USING gbk ) COLLATE gbk_chinese_ci '=>SORT_ASC])
                        ->all();
        return $list;
    }

    public function getTokeninputlist()
    {
        $query = new \yii\db\Query();
        $list  =  $query->select('id,brand_name')
                        ->from('brand')
                        ->orderby(['CONVERT( brand_name USING gbk ) COLLATE gbk_chinese_ci '=>SORT_ASC])
                        ->all();
        $list_string = '[';
        foreach ($list as $key => $value) {
             $list_string .= '{id:'.$value['id'].',name:"'.$value['brand_name'].'"},';
        }
        $list_string .= ']';
        return $list_string;
    }
    public function getName($id){
        $query = new \yii\db\Query();
        $s=$query->select('brand_name')->from('brand')->where(['id'=>$id])->one();
        return $s['brand_name'];
    } 
}
