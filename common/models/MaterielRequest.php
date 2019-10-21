<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;
use app\common\models\MaterielRequestGoods;

class MaterielRequest extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'materiel_request';
    }

    public static function getSimpleCode()
    {
        return 'MATERE';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_user_id', 'depart_id', 'materiel_request_status', 'store_id', 'status_done'], 'integer'],
            [['order_sn', 'add_time', 'add_user_name', 'depart_name', 'remark', 'store_name', 'status_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_sn' => 'Order Sn',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'materiel_request_status' => 'Materiel Request Status',
            'remark' => 'Remark',
            'store_id' => 'Store ID',
            'store_name' => 'Store Name',
            'status_name' => 'Status Name',
            'status_done' => 'Status Done',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'order_sn' => 'Order Sn',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'materiel_request_status' => 'Materiel Request Status',
            'remark' => 'Remark',
            'store_id' => 'Store ID',
            'store_name' => 'Store Name',
            'status_name' => 'Status Name',
            'status_done' => 'Status Done',
        ];
    }

    public function getTotal()
    {
        return $this->hasMany(MaterielRequestGoods::classname(),['order_id'=>'id'])->sum('purchase_price * number'); 
    } 
}
