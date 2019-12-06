<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "goods_auth_goods".
 *
 * @property integer $id
 * @property string $auth_id
 * @property integer $goods_id
 * @property string $goods_name
 * @property string $goods_sn
 * @property string $market_price
 * @property string $sale_price
 * @property integer $number
 * @property string $isbn
 */
class GoodsAuthGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_auth_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'number'], 'integer'],
            [['market_price', 'sale_price'], 'number'],
            [['order_id', 'goods_name', 'goods_sn', 'isbn'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Auth ID',
            'goods_id' => 'Goods ID',
            'goods_name' => 'Goods Name',
            'goods_sn' => 'Goods Sn',
            'market_price' => 'Market Price',
            'sale_price' => 'Sale Price',
            'number' => 'Number',
            'isbn' => 'Isbn',
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
}
