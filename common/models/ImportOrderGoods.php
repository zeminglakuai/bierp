<?php

namespace app\common\models;

use Yii;

class ImportOrderGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $add_goods_error = [];
    public static function tableName()
    {
        return 'import_order_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'number', 'is_self_sell', 'real_number'], 'integer'],
            [['market_price', 'purchase_price'], 'number'],
            [['batch_info'], 'string'],
            [['order_id', 'goods_name', 'isbn', 'goods_sn', 'store_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'goods_id' => 'Goods ID',
            'goods_name' => 'Goods Name',
            'isbn' => 'Isbn',
            'goods_sn' => 'Goods Sn',
            'market_price' => 'Market Price',
            'number' => 'Number',
            'purchase_price' => 'Purchase Price',
            'is_self_sell' => 'Is Self Sell',
            'store_code' => 'Store Code',
            'real_number' => 'Real Number',
            'batch_info' => 'Batch Info',
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
            $this->isbn = $goods->isbn;
            $this->save(false);
            
            return true;
        }else{
            $this->add_goods_error = '缺少参数';
            return false;
        }
    }

    public function getXiaoji(){
        return round($this->purchase_price * $this->number,2);
    }

    public function getXiaoji2(){
        return round($this->purchase_price * $this->real_number,2);
    }
}
