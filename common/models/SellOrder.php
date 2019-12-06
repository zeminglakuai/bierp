<?php

namespace app\common\models;

use Yii;
use app\common\models\CustomOrder;
use app\common\models\Custom;
use app\common\models\Contract;
use app\common\models\Invoice;
use app\common\models\Receipt;
use app\common\models\SellInvoice;

use app\common\models\BaseModel;


class SellOrder extends BaseModel
{
    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'sell_order';
    }
    public static function getSimpleCode()
    {
        return 'SELLOR';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_user_id', 'depart_id', 'sell_order_status', 'admit_user_id', 'custom_id','period','return_status','store_id'], 'integer'],
            [['order_sn', 'custom_order_id','consignee','tel','address', 'add_time', 'add_user_name', 'depart_name','shipping_method', 'remark','store_name', 'admit_user_name', 'admit_time', 'custom_name','pay_method','shipping_status','return_status'], 'string', 'max' => 255]
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
            'custom_order_id' => 'Custom Order ID',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'sell_order_status' => '状态',
            'remark' => 'Remark',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'admit_time' => 'Admit Time',
            'custom_id' => 'Custom ID',
            'custom_name' => 'Custom Name',
            'customOrder&order_name' => '客户方案',
            'invoice_status' => '发票状态',
            'pay_method' => '付款方式',
            'shipping_method' => '发货方式',
            'shipping_status' => '发货状态',
            'return_status' => '退货状态',
            'invoice' => '发票状态',
            'contract' => '相关合同',
            'contract&order_sn' => '合同编号',
            'pay_status' => '收款状态', 
            'custom&custom_name' => '客户名称',
            'customOrder&order_sn' => '客户方案',
            'period' => '付款周期', 
            'goodsNumber' => ' ', 
            'payStatus' => '收款状态',
            'store_id' => '',
            'store_name' => '发货仓库', 
            'consignee' => '发货仓库',
            'tel' => '发货仓库',
            'address' => '发货仓库',
            'invoiceStatus' => '发票状态', 
            'status_name' => '',                        
        ];
    }

    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'order_sn' => 'Order Sn',
            'add_time' => 'Add Time',
            'add_user_name' => 'Add User Name',
            'depart_name' => 'Depart Name',
            'remark' => 'Remark',
            'custom_name' => 'Custom Name',
            'customOrder&order_name' => '客户方案',
            'pay_method' => '付款方式',
            'shipping_method' => '发货方式',
            'shipping_status' => '发货状态',
            'return_status' => '退货状态',
            'contract' => '相关合同',
            'contract&order_sn' => '合同编号',
            'pay_status' => '收款状态', 
            'custom&custom_name' => '客户名称',
            'customOrder&order_sn' => '客户方案',
            'period' => '付款周期', 
            'goodsNumber' => ' ', 
            'payStatus' => '收款状态',
            'store_name' => '发货仓库', 
            'consignee' => '收货人',
            'tel' => '收货电话',
            'address' => '收货地址',
            'invoiceStatus' => '发票状态',
            'status_name' => '', 
        ];
    }




    public function getCustomOrder(){
        return $this->hasone(CustomOrder::classname(), ['id' => 'custom_order_id']);
    }

    public function getB2cOrder(){
        return $this->hasone(B2cOrder::classname(), ['id' => 'custom_order_id']);
    }

    public function getCustom(){
        return $this->hasone(Custom::classname(), ['id' => 'custom_id']);
    }

    public function getContract(){
        return $this->hasone(Contract::classname(), ['order_id' => 'id']);
    }

    // public function getInvoice(){
    //     return $this->hasMany(SellInvoice::classname(), ['order_id' => 'id']);
    // }

    public function getGoodsNumber(){
        $goods_number = $this->hasMany(SellOrderGoods::classname(),['order_id'=>'id'])->sum('number');
        return $goods_number?$goods_number:0;
    }

    public function getPayStatus(){
        if ($this->pay_status >= 1) {
            return '已收款';
        }else{
            $receipt_order = Receipt::find()->where(['order_id'=>$this->id,'model'=>'SELLORDER'])->andwhere(['>','receipt_status',0])->all();
            if ($receipt_order) {
                $receipt_fee = 0;
                foreach ($receipt_order as $key => $value) {
                    //收款金额
                    $receipt_fee += $value->fee;
                }
                return $receipt_fee > 0?'已收款'.$receipt_fee.'元':'未收款';
            }else{
                return '未收款';
            }
        }
    }

    public function getInvoiceStatus(){
        if ($this->invoice_status >= 1) {
            return '已付发票';
        }else{
            $invioce = SellInvoice::find()->where(['order_id'=>$this->id,'model'=>'SELLORDER'])->andwhere(['>','sell_invoice_status',0])->all();
            if ($invioce) {
                $invioce_fee = 0;
                foreach ($invioce as $key => $value) {
                    //收款金额
                    $invioce_fee += $value->fee;
                }
                return $invioce_fee > 0?'已付发票'.$invioce_fee.'元':'未付发票';
            }else{
                return '未付发票';
            }
        }
    }

    public function getTotal(){
        return $this->hasMany(SellOrderGoods::classname(),['order_id'=>'id'])->sum('sale_price * number');
    }    

}
