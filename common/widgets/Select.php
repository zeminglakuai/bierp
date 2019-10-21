<?php
namespace app\common\widgets;

use Yii;

 
class Select extends \yii\base\Widget
{
    public $label_name;
    public $name;
    public $init_value;  
    public $value;
    public $tips;
    public $id;
    public $inneed;
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('select',['label_name'=>$this->label_name,'init_value'=>$this->init_value,'name'=>$this->name,'value'=>$this->value,'tips'=>$this->tips,'inneed'=>$this->inneed]);
    }
}
