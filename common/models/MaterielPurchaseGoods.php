<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "materiel_purchase_goods".
 *
 * @property integer $id
 * @property string $order_id
 * @property integer $materiel_id
 * @property string $materiel_name
 * @property integer $number
 * @property string $purchase_price
 */
class MaterielPurchaseGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $add_goods_error;
    
    public static function tableName()
    {
        return 'materiel_purchase_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['materiel_id', 'number'], 'integer'],
            [['purchase_price'], 'number'],
            [['order_id', 'materiel_name'], 'string', 'max' => 255],
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
            'materiel_id' => 'Materiel ID',
            'materiel_name' => '物料名称',
            'unit' => 'unit',
            'use_to' => 'use_to',               
            'number' => 'Number',
            'purchase_price' => 'Purchase Price',
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
            'materiel_name' => '物料名称',
            'unit' => '单位',
            'use_to' => '用途',               
            'number' => 'Number',
            'purchase_price' => 'Purchase Price',
        ];
    }


    public function getXiaoji(){
        return round($this->purchase_price * $this->number,2);
    }

    public function AddGoods($order_id,$goods){
        //检查商品是不是已经存在
        $if_exitd = $this::find()->where(['materiel_id'=>$goods->id,'order_id'=>$order_id])->one();
        if ($if_exitd) {
            $this->add_goods_error = $goods->materiel_name.'已经存在';
            return false;
        }

        if ($goods) {
            $this->order_id = $order_id;
            $this->materiel_id = $goods->id;
            $this->materiel_name = $goods->materiel_name;
            $this->use_to = $goods->use_to;
            $this->unit = $goods->unit;
            $this->purchase_price = $goods->materiel_price;
            $this->save(false);
            
            return true;
        }else{
            $this->add_goods_error = '缺少参数';
            return false;
        }
    }

}
