<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;

class OrderLog extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'add_user_id', 'depart_id'], 'integer'],
            [['model', 'order_sn', 'lable_name', 'origin_value', 'new_value', 'add_time', 'add_user_name', 'depart_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model' => 'Model',
            'order_id' => 'Order ID',
            'order_sn' => 'Order Sn',
            'lable_name' => 'Lable Name',
            'origin_value' => 'Origin Value',
            'new_value' => 'New Value',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'model' => 'Model',
            'order_id' => 'Order ID',
            'order_sn' => 'Order Sn',
            'lable_name' => 'Lable Name',
            'origin_value' => 'Origin Value',
            'new_value' => 'New Value',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
        ];
    }

    static function createLog($log_data){
        $depart_id = Yii::$app->session['manage_user']['depart_id'];
        $depart_name = Yii::$app->session['manage_user']['depart_name'];
        $ima_time = time();
        Yii::$app->db->createCommand()->insert('order_log', [
            'model' => $log_data['model'],
            'order_id' => $log_data['order_id'],
            'lable_name' => $log_data['lable_name'],
            'origin_value' => $log_data['origin_value'],
            'new_value' => $log_data['new_value'],
            'goods_id' => $log_data['goods_id'],
            'goods_name' => $log_data['goods_name'],
            'goods_sn' => $log_data['goods_sn'],
            'depart_id' => $depart_id,
            'depart_name' => $depart_name,
            'add_user_id' => Yii::$app->session['manage_user']['id'],
            'add_user_name' => Yii::$app->session['manage_user']['admin_name'],          
            'add_time' => $ima_time,
        ])->execute();

        return true;
    }
}
