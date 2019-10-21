<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "export_order_goods".
 *
 * @property integer $id
 * @property string $order_id
 * @property integer $goods_id
 * @property string $goods_name
 * @property string $isbn
 * @property string $goods_sn
 * @property string $market_price
 * @property integer $number
 * @property integer $is_self_sell
 * @property string $store_codes
 * @property integer $real_number
 */
class ExportOrderGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'export_order_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id','goods_id', 'number', 'is_self_sell', 'real_number'], 'integer'],
            [['market_price','final_cost'], 'number'],
            [['store_codes','batch_info'], 'string'],
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
            'store_codes' => '库位',
            'send_number' => '实发数量',
            'batch_info' => '批次信息',
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
            $this->isbn = $goods->isbn;
            $this->save(false);

            return true;
        }else{
            $this->add_goods_error = '缺少参数';
            return false;
        }
    }
}
