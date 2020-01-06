<?php

namespace app\common\models;

use Yii;
use app\common\models\Supplier;

class CustomOrderGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $add_goods_error;
    public $goods_img;
    public static function tableName()
    {
        return 'custom_order_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'number', 'supplier_id', 'is_self_sell', 'supplier_number', 'is_priced', 'is_need_temp'], 'integer'],
            [['market_price', 'other_cost', 'ppt_price', 'other_cost_fee', 'sale_price', 'supplier_price', 'limit_price', 'taobao_price', 'dangdang_price', 'jd_price', 'tmall_price', 'final_cost', 'gross_profit', 'fax', 'shipping_fee', 'materiel_cost', 'platform_rate', 'tranform_rate'], 'number'],
            [['order_id', 'goods_name', 'goods_sn', 'isbn', 'supplier_name', 'shipping_place', 'huoqi', 'shipping_to_place', 'remark', 'goods_img'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'ID',
            'goods_img' => '商品图片',
            'goods_id' => '商品ID',
            'goods_name' => '商品名称',
            'goods_sn' => '型号',
            'market_price' => '市场价',
            'sale_price' => '售价',
            'number' => '数量',
            'saleTotal' => '售价小计',
            'supplierTotal' => '报价小计',
            'profit' => '毛利',
            'isbn' => '条形码',
            'supplierPrice' => '原供货商价格',
            'supplier_price' => '供货商最终报价',
            'supplier_id' => '供货商ID',
            'supplier_name' => '供货商名称',
            'is_self_sell' => '自营商品',
            'supplier_number' => '工厂库存',
            'limit_price' => '项目限价',
            'taobao_price' => '淘宝价格',
            'dangdang_price' => '当当价格',
            'jd_price' => '京东价格',
            'tmall_price' => '天猫价格',
            'final_cost' => '综合成本',
            'fax' => '税点',
            'shipping_fee' => '运费',
            'materiel_cost' => '物料消耗',
            'platform_rate' => '平台扣点',
            'tranform_rate' => '物流反点',
            'finalCost' => '综合成本',
            'faxPoint' => '税费',
            'consult' => '利润系数参考值',
            'profitRate' => '毛利率',
            'profitTotal' => '毛利小计',
            'other_cost' => '其他成本',
            'shipping_place' => '发货地',
            'huoqi' => '货期',
            'ppt_price' => 'PPT报价',
            'platformFee' => '平台扣点',
            'tranformFee' => '物流反点',
            'finalCostTotal' => '成本小计',
            'consultFee' => '利润系数参考值',
            'customOrder&add_time' => '添加时间',
            'customOrder&order_name' => '项目名称',
            'customOrder&add_user_name' => '添加人',
            'customOrder&depart_name' => '添加部门',
            'customOrder&status_name' => '项目状态',
            'is_priced' => '盖章报价',
            'shipping_to_place' => '配送地点',
            'is_need_temp' => '需要样板',
            'remark' => '备注',
        ];
    }

    public function exportLabels()
    {
        return [
            'goods_id' => '商品ID',
            'goods_name' => '商品名称',
            'goods&goods_img' => '商品图片',
            'goods_sn' => '型号',
            'isbn' => '条形码',
            'market_price' => '市场价',
            'sale_price' => '售价',
            'number' => '数量',
            'saleTotal' => '售价小计',
            'supplierTotal' => '报价小计',
            'ppt_price' => 'PPT报价',
            'supplier_price' => '供货商最终报价',
            'supplier_name' => '供货商名称',
            'is_self_sell' => '自营商品',
            'supplier_number' => '工厂库存',
            'limit_price' => '项目限价',
            'taobao_price' => '淘宝价格',
            'dangdang_price' => '当当价格',
            'jd_price' => '京东价格',
            'tmall_price' => '天猫价格',
            'finalCostTotal' => '成本小计',
            'fax' => '税点',
            'shipping_fee' => '运费',
            'materiel_cost' => '物料消耗',
            'platform_rate' => '平台扣点',
            'tranform_rate' => '物流反点',
            'finalCost' => '综合成本',
            'faxPoint' => '税费',
            'consult' => '利润系数参考值',
            'profit' => '毛利',
            'profitRate' => '毛利率',
            'profitTotal' => '毛利小计',
            'other_cost' => '其他成本',
            'shipping_place' => '发货地',
            'huoqi' => '货期',
            'platformFee' => '平台扣点',
            'tranformFee' => '物流反点',
            'consultFee' => '利润系数参考值',
        ];
    }


    public function AddGoods($order_id, $goods, $supplier)
    {
        //检查商品是不是已经存在
        $if_exitd = $this::find()->where(['goods_id' => $goods->goods_id, 'order_id' => $order_id])->one();
        if ($if_exitd) {
            $this->add_goods_error = $goods->goods_name . '已经存在';
            return false;
        }

        if ($goods) {
            $this->order_id = $order_id;
            $this->goods_id = $goods->goods_id;
            $this->goods_name = $goods->goods_name;
            $this->goods_sn = $goods->goods_sn;
            $this->market_price = $goods->market_price;
            $this->sale_price = $goods->shop_price;
            $this->isbn = $goods->isbn;
            $this->is_self_sell = $goods->is_self_sell ? $goods->is_self_sell : 0;
            $this->supplier_id = $supplier['id'] ? $supplier['id'] : 0;
            $this->supplier_name =  $supplier['supplier_name'] ? $supplier['supplier_name'] : '';
            $this->supplier_price =  $supplier['supplier_price'] ? $supplier['supplier_price'] : '';
            $this->ppt_price = $supplier['supplier_price'] ? $supplier['supplier_price'] : '';
            $this->save(false);

            return true;
        } else {
            $this->add_goods_error = '缺少参数已经存在';
            return false;
        }
    }

    public function getCustomOrder()
    {
        return $this->hasOne(CustomOrder::className(), ['id' => 'order_id']);
    }

    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier_id']);
    }

    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['goods_id' => 'goods_id']);
    }

    //计算得到售价小计
    public function getSaleTotal()
    {
        return round($this->sale_price * $this->number, 2);
    }

    //计算得到供货商价格小计
    public function getSupplierTotal()
    {
        return round($this->supplier_price * $this->supplier_number, 2);
    }

    //计算得到毛利
    public function getProfit()
    {
        return round(($this->sale_price - $this->finalCost), 2);
    }

    //计算得到毛利小计
    public function getProfitTotal()
    {
        return round(($this->profit * $this->number), 2);
    }

    //计算得到毛利率
    public function getProfitRate()
    {
        return (round($this->profit / $this->sale_price, 2) * (100)) . '%';
    }

    //计算得到综合成本
    public function getFinalCost()
    {
        // return round($this->supplier_price + $this->faxPoint + $this->shipping_fee + $this->materiel_cost + $this->platformFee + $this->tranformFee + $this->other_cost, 2);
        return round($this->supplier_price + $this->faxPoint + $this->shipping_fee + $this->materiel_cost, 2);
    }

    //计算得到综合成本小计
    public function getFinalCostTotal()
    {
        return round($this->finalCost * $this->number, 2);
    }
    //计算税点
    public function getFaxPoint($fax)
    {

        return round((($this->sale_price - $this->supplier_price) / 1.13 * 0.13 * 1.12) + ($this->sale_price * 0.0005), 2);
    }

    //计算平台费用
    public function getPlatformFee()
    {
        return round($this->platform_rate * $this->sale_price, 2);
    }

    //计算物流分成费用
    public function getTranformFee()
    {
        return round($this->tranform_rate * $this->sale_price, 2);
    }

    //计算利润参考系数
    public function getConsultFee()
    {
        return round($this->consult * $this->supplier_price, 2);
    }
}
