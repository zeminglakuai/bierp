<?php

namespace app\common\models;

use Yii;
use app\common\models\Batch;
use app\common\models\Store;
use app\common\models\Goods;

/**
 * This is the model class for table "stock".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property string $stor_code
 * @property integer $number
 * @property integer $store_id
 */
class Stock extends \yii\db\ActiveRecord
{
    public $goods_name='';
    public $store_name='';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stock';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'goods_id', 'number', 'store_id'], 'integer'],
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
            'stor_code' => '库位',
            'number' => 'Number',
            'store_id' => 'Store ID',
            'goods&goods_name' => '商品名称',
            'goods&goods_sn' => '型号',
            'goods&isbn' => '条形码',
            'goods&market_price' => '市场价',
            'store&store_name' => '仓库',
            'store_id'=>'仓库id',
        ];
    }
    public function exportLabels()
    {
        return [
            'goods_name' => '商品名称',
            'store_name' => '库存名称',
            'number' => '数量',
			'goods_id'=>'商品ID',
			'store_id'=>'库存ID',
        ];
    }

    static function goods_stock($goods_id,$store_id){
        if ($store_id) {
            return static::find()->where(['goods_id'=>$goods_id,'store_id'=>$store_id])->andwhere(['>','number',0])->one();

//            $customers = Yii::$app->db->createCommand("SELECT * FROM stock where goods_id=".$goods_id." and store_id=".$store_id." and number>0")->queryAll();
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
    public function add_stocks($store_id,$store_code,$goods_id,$number,$price,$order_sn,$time,$add_user_id,$add_user_name)
    {
        $this->store_id = $store_id;
        $this->stor_code = $store_code;
        $this->goods_id = $goods_id;
        $this->add_time=$time;
        $this->add_user_id=$add_user_id;
        $this->add_user_name=$add_user_name;
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

    public function reduce_stock($goods_id,$number,$store_id)
    {
        if ($this->number - $number >= 0) {
            $this->number = $this->number - $number;
        }else{
            return false;
        }
        
        $this->save(false);

        //消减批次
        $batch = Batch::find()->where(['goods_id'=>$goods_id,'store_id'=>$store_id])->andwhere(['>','number',0])->orderby('id ASC')->all();

        $batch_info = [];
        $relive_number = 0;
        foreach ($batch as $key => $value) {
            $relive_number = $value->number - $number;
            if ($relive_number >= 0) {
                $batch_info[] = ['id'=>$value->id,'batch_code'=>$value->batch_code,'price'=>$value->price,'number'=>$number];

                $value->number = $value->number - $number;
                $value->save(false);
                break;
            }else{
                $batch_info[] = ['id'=>$value->id,'batch_code'=>$value->batch_code,'price'=>$value->price,'number'=>$value->number];

                $number = abs($relive_number);
                $value->number = 0;
                $value->save(false);
            }
        }

        if ($relive_number < 0) {
           return false;
        }

        return $batch_info;
    }

    //根据数量 和 仓库ID 得到应该发货的库位数组
    //失败返回FALSE
    static function get_store_by_number($goods_id,$number,$store_id){
        //取得库存信息 并分配
        $store_info = self::goods_stock($goods_id,$store_id);

        if (!$store_info) {

            return false;
        }
        exit();
        $present_goods_store_info = [];
        $remain_number = $number;
        $remain_done = 0;
        foreach ($store_info as $store_key => $store_value) {
          $remain_number = $store_value->number - $remain_number;
          if ($remain_number >= 0) {
            $remain_done = 1;
            $present_goods_store_info[] = ['id'=>$store_value->id,'store_code'=>$store_value->stor_code,'number'=>$number];
            break;
          }else{
            $remain_done = 0;
            $remain_number = abs($remain_number);
            $present_goods_store_info[] = ['id'=>$store_value->id,'store_code'=>$store_value->stor_code,'number'=>$store_value->number];
          }
        }

        if (!$remain_done) {
          return false;
        }

        return $present_goods_store_info;
    }


    public function getStore()
    {
        return $this->hasone(Store::classname(),['id'=>'store_id']);
    }

    public function getGoods()
    {
        return $this->hasone(Goods::classname(),['goods_id'=>'goods_id']);
    }
    public function getName()
    {
//        $customers = Yii::$app->db->createCommand("SELECT * FROM config where id=".$id)->queryAll();
        $query = new \yii\db\Query();
        $data = $query->select()->from('stock')->all();
        return $data;
    }
	


}
