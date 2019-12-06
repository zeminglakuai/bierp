<?php
namespace app\common\widgets;

use Yii;

 
class Submit extends \yii\base\Widget
{
    public $model;
    public $model_name;
    public $form_name;
    public $url;
    public $defined_function = false;
    public $if_has_parent = true;
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('submit',[
                                    'model'=>$this->model,
                                    'model_name'=>$this->model_name,
                                    'form_name'=>$this->form_name,
                                    'url'=>$this->url,
                                    'defined_function'=>$this->defined_function,
                                    'if_has_parent'=>$this->if_has_parent,
                                    ]
                            );
    }
}
