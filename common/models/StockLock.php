<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;
class StockLock extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stock_lock';
    }

    public static function getSimpleCode()
    {
        return 'STOCLO';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_user_id', 'admit_user_id', 'depart_id', 'stock_lock_status', 'store_id'], 'integer'],
            [['add_time', 'admit_time', 'order_sn', 'add_user_name', 'admit_user_name', 'remark', 'depart_name', 'store_name', 'end_time'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'add_time' => 'Add Time',
            'admit_time' => 'Admit Time',
            'add_user_id' => 'Add User ID',
            'admit_user_id' => 'Admit User ID',
            'depart_id' => 'Depart ID',
            'order_sn' => 'Order Sn',
            'add_user_name' => 'Add User Name',
            'admit_user_name' => 'Admit User Name',
            'remark' => 'Remark',
            'depart_name' => 'Depart Name',
            'stock_lock_status' => '',
            'store_name' => '仓库',
            'store_id' => 'Store ID',
            'end_time' => '解锁时间',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'add_time' => 'Add Time',
            'admit_time' => 'Admit Time',
            'add_user_id' => 'Add User ID',
            'admit_user_id' => 'Admit User ID',
            'depart_id' => 'Depart ID',
            'order_sn' => 'Order Sn',
            'add_user_name' => 'Add User Name',
            'admit_user_name' => 'Admit User Name',
            'remark' => 'Remark',
            'depart_name' => 'Depart Name',
            'stock_lock_status' => 'Stock Lock Status',
            'store_name' => 'Store Name',
            'store_id' => 'Store ID',
            'end_time' => '解锁时间',
        ];
    }

    public function getGoodsNumber(){
        $goods_number = $this->hasMany(StockLockGoods::classname(),['order_id'=>'id'])->sum('number');
        return $goods_number?$goods_number:0;
    }

}
