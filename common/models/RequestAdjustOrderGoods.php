<?php

namespace app\common\models;

use Yii;
 
class RequestAdjustOrderGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'request_adjust_order_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'number'], 'integer'],
            [['market_price', 'final_cost'], 'number'],
            [['batch_info'], 'string'],
            [['order_id', 'goods_name', 'goods_sn', 'isbn'], 'string', 'max' => 255],
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
            'number' => 'Number',
            'isbn' => 'Isbn',
            'batch_info' => 'Batch Info',
            'final_cost' => '最终成本',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'goods_id' => 'Goods ID',
            'goods_name' => 'Goods Name',
            'goods_sn' => 'Goods Sn',
            'market_price' => 'Market Price',
            'number' => 'Number',
            'isbn' => 'Isbn',
            'batch_info' => 'Batch Info',
            'final_cost' => '最终成本',
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
            $this->number = 1;
            $this->isbn = $goods->isbn;
            $this->save(false);
            
            return true;
        }else{
            $this->add_goods_error = '缺少参数已经存在';
            return false;
        }
    }



}
