<?php

namespace app\common\models;

use Yii;
use app\common\models\FileInfo;
use app\common\models\Contact;
use app\common\models\Consignee;
use app\common\models\BaseModel;

class Custom extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'custom';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['depart_id', 'add_user_id'], 'integer'],
            [['custom_name', 'contact', 'tel', 'qq','area_1','area_2','sub_compny','custom_prop','tax_code', 'email', 'custom_depart','position','address', 'remark', 'sample_name', 'add_time', 'alipay', 'open_bank', 'bank_code', 'bank_name', 'add_user_name', 'depart_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'custom_name' => 'Custom Name',
            'contact' => 'Contact',
            'tel' => 'Tel',
            'qq' => 'Qq',
            'email' => 'Email',
            'address' => 'Address',
            'remark' => 'Remark',
            'depart_id' => 'Depart ID',
            'sample_name' => 'Sample Name',
            'add_time' => 'Add Time',
            'alipay' => 'Alipay',
            'open_bank' => 'Open Bank',
            'bank_code' => 'Bank Code',
            'bank_name' => 'Bank Name',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_name' => 'Depart Name',
            'custom_depart' => '所属部门',
            'position' => '职位',
            'custom_prop' => '客户属性', 
            'tax_code' => '税号',
            'area_1' => '区域一',
            'area_2' => '区域二',
            'sub_compny' => '子公司',
        ];
    }

    public function getCustomFile()
    {   
        return $this->hasMany(FileInfo::className(), ['belong_id' => 'id'])->where(['model'=>$this->tableName()]);
    }

    public function getContactList()
    {   
        return $this->hasMany(Contact::className(), ['belong_id' => 'id'])->where(['model'=>'custom']);
    }

    public function getConsignee()
    {   
        return $this->hasMany(Consignee::className(), ['belong_id' => 'id'])->where(['model'=>'custom']);
    }

}
