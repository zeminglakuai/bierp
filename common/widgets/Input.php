<?php
namespace app\common\widgets;

use Yii;

 
class Input extends \yii\base\Widget
{
    public $label_name;
    public $name;
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

        return $this->render('input',['label_name'=>$this->label_name,'name'=>$this->name,'value'=>$this->value,'tips'=>$this->tips,'id'=>$this->id,'inneed'=>$this->inneed]);
    }   
}
