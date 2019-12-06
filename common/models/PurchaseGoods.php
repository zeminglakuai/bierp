<?php

namespace app\common\models;

use app\common\models\ImpoertOrderGoods;
use Yii;

class PurchaseGoods extends \yii\db\ActiveRecord
{
    public $add_goods_error;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purchase_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'number', 'supplier_id', 'is_self_sell', 'supplier_number'], 'integer'],
            [['market_price', 'sale_price', 'supplier_price'], 'number'],
            [['order_id', 'goods_name', 'goods_sn', 'isbn', 'supplier_name'], 'string', 'max' => 255]
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
            'goods_sn' => 'Goods Sn',
            'market_price' => 'Market Price',
            'sale_price' => 'Sale Price',
            'number' => 'Number',
            'isbn' => 'Isbn',
            'supplier_price' => 'Supplier Price',
            'supplier_id' => 'Supplier ID',
            'supplier_name' => 'Supplier Name',
            'is_self_sell' => 'Is Self Sell',
            'supplier_number' => 'Supplier Number',
            'xiaoji' => '小计',            
        ];
    }


    public function exportLabels()
    {
        return [
            'goods_id' => 'Goods ID',
            'goods_name' => 'Goods Name',
            'goods_sn' => 'Goods Sn',
            'market_price' => 'Market Price',
            'sale_price' => 'Sale Price',
            'number' => 'Number',
            'isbn' => 'Isbn',
            'supplier_price' => 'Supplier Price',
            'supplier_id' => 'Supplier ID',
            'supplier_name' => 'Supplier Name',
            'is_self_sell' => 'Is Self Sell',
            'supplier_number' => 'Supplier Number',
            'xiaoji' => '小计',
        ];
    }



    public function AddGoods($order_id,$goods,$order_type,$supplier_id){
        //检查商品是不是已经存在
        $if_exitd = $this::find()->where(['goods_id'=>$goods->goods_id,'order_id'=>$order_id])->one();
        if ($if_exitd) {
            $this->add_goods_error = $goods->goods_name.'已经存在';
            return false;
        }
        $sql="SELECT * FROM goods_supplier where supplier_id=".$supplier_id." and goods_id= ".$goods->goods_id;
         $data= Yii::$app->db->createCommand($sql)->queryOne();
        if ($goods) {
            $this->order_id = $order_id;
            $this->goods_id = $goods->goods_id;
            $this->goods_name = $goods->goods_name;
            $this->goods_sn = $goods->goods_sn;
            $this->market_price = $goods->market_price;
            $this->sale_price = $goods->shop_price;
            $this->purchase_price =$data['supplier_price'];
            $this->isbn = $goods->isbn;
            $this->order_type = $order_type;
            $this->is_self_sell = $goods->is_self_sell?$goods->is_self_sell:0;
            $this->save(false);
            
            return true;
        }else{
            $this->add_goods_error = '缺少参数已经存在';
            return false;
        }
    }

    public function getXiaoji(){
        return $this->purchase_price * $this->number;
    }

}
