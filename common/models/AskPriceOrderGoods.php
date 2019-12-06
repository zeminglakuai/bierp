<?php
namespace app\common\models;

use Yii;

class AskPriceOrderGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ask_price_order_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'number'], 'integer'],
            [['market_price', 'sale_price', 'ask_price', 'return_ask_price','is_self_sell'], 'number'],
            [['order_id', 'goods_name', 'isbn', 'goods_sn'], 'string', 'max' => 255]
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
            'goods_id' => '商品ID',
            'goods_name' => '商品名',
            'isbn' => 'ISBN',
            'goods_sn' => '型号',
            'market_price' => '市场价',
            'sale_price' => '售价',
            'number' => '数量',
            'ask_price' => '原供货商报价',
            'return_ask_price' => '供货商报价',
            'return_number' => '可供数量',
            'is_self_sell' => '',
            'goodsImg' => '商品图片',            
        ];
    }

    public function exportLabels()
    {
        return [
            'goods_id' => '商品ID',
            'goods_name' => '商品名',
            'isbn' => 'ISBN',
            'goods_sn' => '型号',
            'market_price' => '市场价',
            'sale_price' => '售价',
            'number' => '数量',
            'ask_price' => '原供货商报价',
            'return_ask_price' => '供货商报价',
            'return_number' => '可供数量',
            'is_self_sell' => '',
        ];
    }
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['goods_id' => 'goods_id']);
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
            $this->ask_price = $goods->supplier_price;
            $this->isbn = $goods->isbn;
            $this->is_self_sell = $goods->is_self_sell?$goods->is_self_sell:0;
            $this->save(false);
            
            return true;
        }else{
            $this->add_goods_error = '缺少参数';
            return false;
        }
    }

    public function getGoodsImg(){
        $goods = $this->hasone(Goods::className(),['goods_id'=>'goods_id'])->one();
        return $goods->goods_img;
    }    


}
