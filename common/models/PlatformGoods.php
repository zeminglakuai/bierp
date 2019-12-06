<?php

namespace app\common\models;

use app\common\models\ImpoertOrderGoods;
use Yii;

class PlatformGoods extends \yii\db\ActiveRecord
{
    public $add_goods_error;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_platform';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_platform_id', 'goods_id', 'platform_id', 'daifa'], 'integer'],
            [['platform_price'], 'number'],
            [['startdate', 'enddate'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goods_platform_id' => 'goods_platform_id',
            'goods_id' => 'Goods ID',
            'platform_id' => 'platform_id',
            'daifa' => 'daifa',
            'platform_price' => 'platform_price',
            'startdate' => 'startdate',
            'enddate' => 'enddate',
        ];
    }


    public function exportLabels()
    {
        return [
            'goods_platform_id' => 'goods_platform_id',
            'goods_id' => 'Goods ID',
            'platform_id' => 'platform_id',
            'daifa' => 'daifa',
            'platform_price' => 'platform_price',
            'startdate' => 'startdate',
            'enddate' => 'enddate',
        ];
    }



    /*public function AddGoods($order_id,$goods,$order_type,$supplier_id){
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
    }*/

}
