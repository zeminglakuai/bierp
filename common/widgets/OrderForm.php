<?php
namespace app\common\widgets;

use Yii;

/*
$form_data[
    'type',
    'label_name',
    'name',
    'value',
    'tips',
    'id',
    'init_value',
    ]
*/

class OrderForm extends \yii\base\Widget
{
    public $form_id;
    public $form_data;
    public function init()
    {
        parent::init();
    }

    public function run()
    {

        return $this->render('order-form',['form_data'=>$this->form_data,'form_id'=>$form_id]);
    }
}
