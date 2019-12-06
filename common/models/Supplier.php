<?php

namespace app\common\models;

use Yii;
use yii\db\Query;
use app\includes\Pinyin;
use app\common\models\FileInfo;
use app\common\models\Admin;
use app\common\models\Contact;
use app\common\models\Address;
use app\common\models\BaseModel;
class Supplier extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'supplier';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_daifa','supplier_status'], 'integer'],
            [['supplier_name','title', 'tel','account_period','tax_code','address','store_address','position', 'contact', 'qq', 'remark', 'simple_name', 'depart', 'contact2', 'tel2', 'qq2', 'contact3', 'tel3', 'qq3', 'contact4', 'tel4', 'qq4', 'bank_name', 'bank_open', 'bank_code','alipay'], 'string', 'max' => 233],
            [['guhua'], 'string', 'max' => 213]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplier_name' => '供货商名称',
            'tel' => '电话',
            'contact' => '联系人',
            'qq' => 'Qq',
            'remark' => '备注',
            'simple_name' => '简称',
            'depart' => '部门',
            'contact2' => '联系人2',
            'tel2' => 'Tel2',
            'qq2' => 'Qq2',
            'contact3' => 'Contact3',
            'tel3' => 'Tel3',
            'qq3' => 'Qq3',
            'contact4' => 'Contact4',
            'tel4' => 'Tel4',
            'qq4' => 'Qq4',
            'is_daifa' => 'Daifa',
            'guhua' => '固话',
            'bank_name' => '银行',
            'bank_open' => '开户行',
            'bank_code' => '银行卡号',
            'alipay' => '支付宝',
            'account_period' => '账期',
            'store_address' => '仓库地址',
            'position' => '地址',
            'add_user_id' => '地址',
            'add_user_name' => '地址',
            'depart_name' => '地址',
            'add_time' => '添加时间',
            'supplier_status' => '状态',
            'tax_code' => '税号',
            'address' => '地址',
            'title' => '发票抬头',
        ];
    }
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'supplier_name' => '供货商名称',
            'tel' => '电话',
            'add_user_id' => '地址',
            'add_user_name' => '地址',
            'position' => '地址',
            'contact' => '联系人',
            'qq' => 'Qq',
            'remark' => '备注',
            'simple_name' => '简称',
            'depart' => '部门',
            'contact2' => '联系人2',
            'tel2' => 'Tel2',
            'qq2' => 'Qq2',
            'contact3' => 'Contact3',
            'tel3' => 'Tel3',
            'qq3' => 'Qq3',
            'contact4' => 'Contact4',
            'tel4' => 'Tel4',
            'qq4' => 'Qq4',
            'is_daifa' => 'Daifa',
            'guhua' => '固话',
            'bank_name' => '银行',
            'bank_open' => '开户行',
            'bank_code' => '银行卡号',
            'account_period' => '账期',
            'store_address' => '仓库地址',
            'address' => '公司地址',
        ];
    }
    public function get_select_list()
    {   
    //填充第一条为 请选择供货商
        $list_1 = [0=>['id'=>'0','simple_name'=>'选择供货商']];
        $list  =  self::find()->orderby(['CONVERT( simple_name USING gbk ) COLLATE gbk_chinese_ci '=>SORT_ASC])->all();
        //处理每条数据前 加上首字母
        foreach ($list as $key => $value) {
            $list[$key]['simple_name'] = Pinyin::encode($value['simple_name']).'-'.$value['simple_name'];
        }
        $new_list = array_merge($list_1,$list);
        return $new_list;
    }

    //3证
    public function getSupplierThreez()
    {   
        return $this->hasMany(FileInfo::className(), ['belong_id' => 'id'])->where(['model'=>$this->tableName(),'type'=>'3z']);
    }

    //廉洁协议
    public function getSupplierProtocol()
    {   
        return $this->hasMany(FileInfo::className(), ['belong_id' => 'id'])->where(['model'=>$this->tableName(),'type'=>'protocol']);
    }

    //合同
    public function getSupplierContract()
    {   
        return $this->hasMany(FileInfo::className(), ['belong_id' => 'id'])->where(['model'=>$this->tableName(),'type'=>'contract']);
    }

    public function getContactList()
    {   
        return $this->hasMany(Contact::className(), ['belong_id' => 'id'])->where(['model'=>'supplier']);
    }

    public function getAddress()
    {   
        return $this->hasMany(Address::className(), ['belong_id' => 'id'])->where(['type'=>'supplier']);
    }
    
    public function getDataOne($id){
        $query = new \yii\db\Query();
        $data= $query->select()->from('supplier')->where(['id'=>$id])->one();
        return $data;
    } 
}
