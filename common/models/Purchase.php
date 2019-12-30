<?php

namespace app\common\models;

use Yii;

use app\common\models\Supplier;
use app\common\models\Contract;
use app\common\models\AcceptInvoice;
use app\common\models\Payment;
use app\common\models\ImpoertOrder;
use app\common\models\BaseModel;
use app\common\models\PurchaseGoods;

class Purchase extends BaseModel
{
    public $project_id = '';
    public $project_name = '';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purchase';
    }
    public static function getSimpleCode()
    {
        return 'PURC';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['supplier_id', 'purchase_status', 'admit_user_id', 'return_status', 'period', 'add_user_id', 'pay_status', 'store_id', 'depart_id', 'invoice_status', 'sell_order_id', 'pay_type', 'purchase_type', 'pack_num', 'fid', 'project_id'], 'integer'],
            [['order_name', 'consignee', 'consignee_tel', 'address', 'add_time', 'admit_time', 'order_sn', 'sell_order_sn', 'supplier_name', 'pay_method', 'shipping_method', 'store_name', 'remark', 'admit_user_name', 'add_user_name', 'depart_name', 'platform_beizhu', 'platform_name', 'qcode'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_name' => '项目名称',
            'add_time' => 'Add Time',
            'admit_time' => 'Admit Time',
            'order_sn' => 'Order Sn',
            'supplier_id' => 'Supplier ID',
            'supplier_name' => 'Supplier Name',
            'purchase_status' => 'Purchase Status',
            'remark' => 'Remark',
            'remark1' => '对供应商采购单备注',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'supplier&supplier_name' => '供货商',
            'invoice_status' => '供货商',
            'pay_method' => '供货商',
            'pay_status' => '',
            'payStatus' => '付款状态',
            'invoiceStatus' => '发票状态',
            'shipping_method' => '',
            'return_status' => '',
            'period' => '付款周期',
            'contract&order_sn' => '采购合同',
            'store_name' => '仓库名称',
            'store_id' => '仓库ID',
            'sellOrder&order_sn' => '销售单号',
            'sell_order_id' => '',
            'sell_order_sn' => '销售单号',
            'pay_type' => '付款方式',
            'purchase_type' => '采购单类型',
            'platform_name' => '平台名称',
            'platform_beizhu' => '平台备注',
            'qcode' => '二维码链接',
            'consignee_tel' => '收件人手机号码',
            'address' => '收件地址',
            'supplier_contact' => '供应商联系人',
            'supplier_tel' => '供应商联系电话',
            'pack_num' => '装箱数',


        ];
    }


    public function exportLabels()
    {
        return [
            'order_name' => '项目名称',
            'add_time' => 'Add Time',
            'admit_time' => 'Admit Time',
            'order_sn' => 'Order Sn',
            'supplier_id' => 'Supplier ID',
            'supplier_name' => 'Supplier Name',
            'purchase_status' => 'Purchase Status',
            'remark' => 'Remark',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'supplier&supplier_name' => '供货商',
            'pay_method' => '供货商',
            'pay_status' => '',
            'payStatus' => '付款状态',
            'invoiceStatus' => '发票状态',
            'shipping_method' => '',
            'return_status' => '',
            'period' => '付款周期',
            'contract&order_sn' => '采购合同',
            'store_name' => '仓库名称',
            'pay_type' => '付款方式',
            'qcode' => '二维码',
        ];
    }


    public function getSupplier()
    {
        return $this->hasone(Supplier::classname(), ['id' => 'supplier_id']);
    }

    public function getContract()
    {
        return $this->hasone(Contract::classname(), ['order_id' => 'id'])->andwhere(['type' => 2]);
    }

    public function getPayStatus()
    {
        if ($this->pay_status < 1) {
            return '未付款';
        } else {
            $payment_order = $this->hasMany(Payment::classname(), ['order_id' => 'id', 'model' => 'Purchase']);
            if ($payment_order) {
                foreach ($payment_order as $key => $value) {
                    if ($value->payment_status < 1) {
                        return '未付款';
                    }
                }
                return '已付款';
            } else {
                return '未付款';
            }
        }
    }

    public function getInvoiceStatus()
    {
        if ($this->invoice_status < 1) {
            return '未收发票';
        } else {
            $invioce = $this->hasMany(AcceptInvoice::classname(), ['order_id' => 'id', 'model' => 'Purchase']);
            if ($invioce) {
                foreach ($invioce as $key => $value) {
                    if ($value->accept_invoice_status < 1) {
                        return '未收发票';
                    }
                }
                return '已收发票';
            } else {
                return '未收发票';
            }
        }
    }

    public function getImportOrder()
    {
        return $this->hasMany(ImportOrder::classname(), ['order_id' => 'id']);
    }

    public function getGoodsNumber()
    {
        $goods_number_total = $this->hasMany(PurchaseGoods::classname(), ['order_id' => 'id'])->sum('number');
        if ($goods_number_total) {
            return $goods_number_total;
        }
        return 0;
    }

    public function getTotal()
    {
        return $this->hasMany(PurchaseGoods::classname(), ['order_id' => 'id'])->sum('purchase_price * number');
    }

    public function getSellOrder()
    {
        return $this->hasMany(SellOrder::classname(), ['id' => 'sell_order_id']);
    }
    public static function getValueList()
    {
        return static::find()->where(['dictionary_id!=null'])->all();
    }
}
