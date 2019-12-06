<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "other_export_order_goods".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $goods_id
 * @property string $goods_name
 * @property string $isbn
 * @property string $goods_sn
 * @property string $market_price
 * @property integer $number
 * @property integer $is_self_sell
 * @property string $store_codes
 * @property string $batch_info
 * @property string $final_cost
 */
class OtherExportOrderGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'other_export_order_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'goods_id', 'number', 'is_self_sell'], 'integer'],
            [['market_price', 'final_cost'], 'number'],
            [['store_code', 'batch_info'], 'string'],
            [['goods_name', 'isbn', 'goods_sn'], 'string', 'max' => 255],
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
            'is_self_sell' => 'Is Self Sell',
            'store_code' => 'Store Codes',
            'batch_info' => 'Batch Info',
            'final_cost' => 'Final Cost',
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
            'isbn' => 'Isbn',
            'goods_sn' => 'Goods Sn',
            'market_price' => 'Market Price',
            'number' => 'Number',
            'is_self_sell' => 'Is Self Sell',
            'store_codes' => 'Store Codes',
            'batch_info' => 'Batch Info',
            'final_cost' => 'Final Cost',
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
         
            $this->number = isset($goods->number)?$goods->number:1;
            $this->is_self_sell = $goods->is_self_sell?$goods->is_self_sell:0;
            $this->save(false);
            
            return true;
        }else{
            $this->add_goods_error = '缺少参数已经存在';
            return false;
        }
    }




}
