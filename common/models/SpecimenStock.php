<?php

namespace app\common\models;

use Yii;
use app\common\models\Goods;

/**
 * This is the model class for table "specimen_stock".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property string $stor_code
 * @property integer $number
 */
class SpecimenStock extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'specimen_stock';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'number'], 'integer'],
            [['stor_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => 'Goods ID',
            'stor_code' => 'Stor Code',
            'number' => 'Number',
            'goods&goods_name' => '商品名称',
            'goods&goods_sn' => '型号',
            'goods&isbn' => '条形码',
            'goods&market_price' => '市场价',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => 'Goods ID',
            'stor_code' => 'Stor Code',
            'number' => 'Number',
            'goods&goods_name' => '商品名称',
            'goods&goods_sn' => '型号',
            'goods&isbn' => '条形码',
            'goods&market_price' => '市场价',
        ];
    }

    public function getGoods()
    {
        return $this->hasone(Goods::classname(),['goods_id'=>'goods_id']);
    }

    static function goods_stock($goods_id,$store_id = 0){
        if ($store_id) {
            return static::find()->where(['goods_id'=>$goods_id,'store_id'=>$store_id])->andwhere(['>','number',0])->all();
        }else{
            return static::find()->where(['goods_id'=>$goods_id])->andwhere(['>','number',0])->all();
        }
    }


    public function add_stock($store_id,$store_code,$goods_id,$number,$price,$order_sn)
    {
        $this->store_id = $store_id;
        $this->stor_code = $store_code;
        $this->goods_id = $goods_id;

        if ($this->number && $this->number > 0) {
            $this->number = $this->number + $number;
        }else{
            $this->number = $number;
        }
        $this->save(false);

        //新建批次
        $batch = new Batch();
        $batch->batch_code = $order_sn;
        $batch->goods_id = $goods_id;
        $batch->price = $price;
        $batch->number = $number;
        $batch->store_id = $store_id;
        $batch->addtime = time();
        $batch->save(false);

        return true;
    }
}
