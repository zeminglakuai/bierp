<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;

class AssetPurchase extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'asset_purchase';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['asset_purchase_status', 'admit_user_id', 'add_user_id', 'depart_id', 'pay_status', 'status_done', 'number', 'depreciation_year_limit'], 'integer'],
            [['purchase_price', 'depreciation_per_month','speed_depreciation_fee','salvage'], 'number'],
            [['add_time', 'admit_time', 'remark', 'admit_user_name', 'add_user_name', 'depart_name', 'pay_method', 'status_name', 'asset_cate', 'asset_name', 'asset_sn', 'asset_unit', 'depreciation_status', 'expire_time'], 'string', 'max' => 255],
            [['asset_name', 'asset_sn','depreciation_per_month', 'asset_cate', 'number', 'purchase_price','depreciation_year_limit', 'depreciation_status','salvage'],'required','message'=>'{attribute}为必填项'],
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
            'asset_purchase_status' => 'Asset Purchase Status',
            'remark' => 'Remark',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'pay_method' => 'Pay Method',
            'pay_status' => 'Pay Status',
            'status_name' => 'Status Name',
            'status_done' => '审核流程结束',
            'asset_cate' => '固定资产类型',
            'asset_name' => '固定资产名称',
            'asset_sn' => '型号',
            'number' => '数量',
            'purchase_price' => '采购价',
            'asset_unit' => '计量单位',
            'depreciation_year_limit' => '折旧年限',
            'depreciation_status' => '折旧方法',
            'depreciation_per_month' => '每月折旧',
            'expire_time' => '到期时间',
            'total' => '合计',
            'speed_depreciation_fee' => '加速折旧值',
            'depreciatedFee' => '累计折旧',
            'salvage' => '单品残值',
            'salvage_total' => '残值小计',
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
            'asset_purchase_status' => 'Asset Purchase Status',
            'remark' => 'Remark',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'pay_method' => 'Pay Method',
            'pay_status' => 'Pay Status',
            'status_name' => 'Status Name',
            'status_done' => '审核流程结束',
            'asset_cate' => '固定资产类型',
            'asset_name' => '固定资产名称',
            'asset_sn' => '型号',
            'number' => 'Number',
            'purchase_price' => '采购价',
            'origin_price' => '资产原值',
            'asset_unit' => '计量单位',
            'depreciation_year_limit' => '折旧年限',
            'depreciation_status' => '折旧方法',
            'depreciation_per_month' => '每月折旧',
            'expire_time' => '到期时间',
            'speed_depreciation_fee' => '加速折旧值',
            'salvage' => '单品残值',   
        ];
    }

    public function getTotal(){
        return $this->purchase_price*$this->number;
    }

    //残值小计
    public function getSalvage_total(){
        return $this->salvage*$this->number;
    }    

    //“账面净值”＝“合计原值”－“累计折旧”－“加速折旧额”
    public function getSalvageValue(){
        return $this->total - $this->depreciatedFee - $this->speed_depreciation_fee;
    }

    public function getDepreciatedFee(){
        //“累计折旧”＝“每月折旧”*入账日期次月至当下的月数（每过一个自然月，自动增加一个月的累计折旧
        $add_year = date('Y',$this->add_time);
        $add_month = date('m',$this->add_time);
        $add_day = date('d',$this->add_time);

        $curr_year = date('Y');
        $curr_month = date('m');
        $curr_day = date('d');

        $correction = ($curr_day - $add_day)?0:-1;
        return (($curr_year - $add_year)*12 + ($curr_month - $add_month) + $correction)*$this->depreciation_per_month;
    }

    //“到期时间”＝（“账面净值”-“残值”）/每月折旧（得出的月数，自动与当下日期相加）
    public function getExpire_end(){
        return ceil(($this->salvageValue - $this->leavefee)/$this->depreciation_per_month);
    }



}
