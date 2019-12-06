<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "materiel_request_goods".
 *
 * @property integer $id
 * @property string $order_id
 * @property integer $materiel_id
 * @property string $materiel_name
 * @property integer $number
 * @property string $purchase_price
 * @property string $unit
 * @property string $use_to
 * @property integer $status_done
 */
class MaterielRequestGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $add_goods_error;
    public static function tableName()
    {
        return 'materiel_request_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['materiel_id', 'number', 'status_done'], 'integer'],
            [['purchase_price'], 'number'],
            [['order_id', 'materiel_name', 'unit', 'use_to'], 'string', 'max' => 255],
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
            'materiel_name' => 'Materiel Name',
            'number' => 'Number',
            'purchase_price' => 'Purchase Price',
            'unit' => 'Unit',
            'use_to' => 'Use To',
            'status_done' => 'Status Done',
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
            'materiel_id' => 'Materiel ID',
            'materiel_name' => 'Materiel Name',
            'number' => 'Number',
            'purchase_price' => 'Purchase Price',
            'unit' => 'Unit',
            'use_to' => 'Use To',
            'status_done' => 'Status Done',
        ];
    }

    public function AddGoods($order_id,$goods){
        //检查商品是不是已经存在
        $if_exitd = $this::find()->where(['materiel_id'=>$goods->id,'order_id'=>$order_id])->one();
        if ($if_exitd) {
            $this->add_goods_error = $goods->goods_name.'已经存在';
            return false;
        }

        if ($goods) {
            $this->order_id = $order_id;
            $this->materiel_id = $goods->id;
            $this->materiel_name = $goods->materiel_name;
            $this->purchase_price = $goods->materiel_price;
            $this->unit = $goods->unit;
            $this->use_to = $goods->use_to;
            $this->number = 1;
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
