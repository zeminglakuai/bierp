<?php
namespace app\common\models;

use Yii;


class BaseModel extends \yii\db\ActiveRecord
{
    public function __construct ($config = [])
    {
        $this->setAttribute('add_user_id', Yii::$app->session['manage_user']['id']);
        $this->setAttribute('add_user_name', Yii::$app->session['manage_user']['admin_name']);
        $this->setAttribute('depart_id', Yii::$app->session['manage_user']['depart_id']?Yii::$app->session['manage_user']['depart_id']:1);
        $this->setAttribute('depart_name', Yii::$app->session['manage_user']['depart_name']?Yii::$app->session['manage_user']['depart_name']:'总部');
        $this->setAttribute('add_time',  (string)time());
        
        parent::__construct($config);
    }

    public function getSupplier(){
        return $this->hasone(Supplier::classname(), ['id' => 'supplier_id']);
    }

    public function getContract(){
        return $this->hasone(Contract::classname(), ['order_id' => 'id'])->andwhere(['type'=>2]);
    }

    // public function getInvoice(){
    //     return $this->hasMany(Invoice::classname(), ['order_id' => 'id']);
    // }

    public function getImpoertOrder(){
        return $this->hasMany(ImpoertOrder::classname(), ['order_id' => 'id']);
    }
    
    // public function getStatusName(){
    //     $ApprovalLog = $this->hasone(ApprovalLog::classname(), ['order_id' => 'id'])->order('id DESC');
    //     return 
    // }



    
}
