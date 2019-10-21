<?php

namespace app\common\models;

use Yii;
use app\common\models\AdjustStoreGoods;

/**
 * This is the model class for table "adjust_store".
 *
 * @property integer $id
 * @property integer $add_user_id
 * @property string $add_user_name
 * @property string $add_time
 * @property integer $depart_id
 * @property string $depart_name
 * @property integer $admit_user_id
 * @property string $admit_user_name
 * @property integer $admit_depart_id
 * @property string $admit_depart_name
 * @property integer $store_id
 * @property string $store_name
 */
class AdjustStore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'adjust_store';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'add_user_id', 'depart_id', 'admit_user_id', 'admit_depart_id', 'store_id'], 'integer'],
            [['add_user_name', 'add_time', 'depart_name', 'admit_user_name', 'admit_depart_name', 'store_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'add_time' => 'Add Time',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'admit_user_id' => 'Admit User ID',
            'admit_user_name' => 'Admit User Name',
            'admit_depart_id' => 'Admit Depart ID',
            'admit_depart_name' => 'Admit Depart Name',
            'store_id' => 'Store ID',
            'store_name' => 'Store Name',
        ];
    }

    public function getGoodsNumber(){
        $goods_number_total = $this->hasMany(AdjustStoreGoods::classname(),['order_id'=>'id'])->sum('number');

        if ($goods_number_total) {
            return $goods_number_total;
        }
        return 0;
    }

}
