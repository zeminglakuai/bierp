<?php

namespace app\common\models;

use Yii;
use app\common\models\SupplierGoods;

class CustomOrderGoods extends \yii\db\ActiveRecord
{
    public $add_goods_error;
    /**
     * @inheritdoc
     */
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
            [['goods_id', 'number','is_self_sell'], 'integer'],
            [['market_price', 'sale_price'], 'number'],
            [['order_id', 'goods_name', 'goods_sn','isbn'], 'string', 'max' => 255]
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
            'goods_id' => '商品ID',
            'goods_name' => '商品名称',
            'goods_sn' => '型号',
            'market_price' => '市场价',
            'sale_price' => '售价',
            'number' => '数量',
            'saleTotal' => '售价小计',
            'supplierTotal' => '报价小计',
            'profit' => '利润',
            'isbn' => '条形码',
            'supplierPrice' => '原供货商价格',
            'supplier_price' => '反馈价格',
            'supplier_id' => '供货商ID',
            'supplier_name' => '供货商名称',
            'is_self_sell' => '自营商品',
            'supplier_number' => '可供数',
        ];
    }

    public function exportLabels()
    {
        return [
            'goods_id' => '商品ID',
            'goods_name' => '商品名称',
            'goods_sn' => '型号',
            'market_price' => '市场价',
            'sale_price' => '售价',
            'number' => '数量',
            'saleTotal' => '售价小计',
            'supplierTotal' => '供货商报价小计',
            'profit' => '利润',
            'isbn' => '条形码',
            'supplierPrice' => '原供货商价格',
            'supplier_price' => '反馈价格',
            'supplier_id' => '供货商ID',
            'supplier_name' => '供货商名称',
            'is_self_sell' => '自营商品',
            'supplier_number' => '可供数',
        ];
    }

    public function AddGoods($order_id,$goods){
        //检查商品是不是已经存在
        $if_exitd = $this::find()->where(['goods_id'=>$goods->goods_id,'order_id'=>$order_id])->one();
        if ($if_exitd) {
            $this->add_goods_error = $goods->goods_name.'已经存在';
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
            $this->is_self_sell = $goods->is_self_sell?$goods->is_self_sell:0;
            $this->save(false);
            
            return true;
        }else{
            $this->add_goods_error = '缺少参数已经存在';
            return false;
        }
    }

    public function getSupplierPrice()
    {
        return $this->hasMany(SupplierGoods::className(), ['goods_id' => 'goods_id']);
    }
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['goods_id' => 'goods_id']);
    }

    public function getSaleTotal(){
        return round($this->sale_price * $this->number,2);
    }

    public function getSupplierTotal(){
        return round($this->supplier_price * $this->supplier_number,2);
    }

    public function getProfit(){
        return round(($this->sale_price - $this->supplier_price)*$this->number,2);
    }
}