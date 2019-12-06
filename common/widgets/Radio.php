<?php
namespace app\common\widgets;

use Yii;

 
class Radio extends \yii\base\Widget
{
    public $label_name;
    public $name;
    public $init_value;  
    public $value;
    public $tips;
    public function init()
    {
        parent::init();
    }

    public function run()
    {

        return $this->render('radio',['label_name'=>$this->label_name,'init_value'=>$this->init_value,'name'=>$this->name,'value'=>$this->value,'tips'=>$this->tips]);
    }   
}
