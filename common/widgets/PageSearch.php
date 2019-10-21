<?php
namespace app\common\widgets;

use Yii;

 
class PageSearch extends \yii\base\Widget
{
    public $condition;
    public $url;
    public $more_search;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $more_search = isset($this->more_search)?$this->more_search:true;
        return $this->render('page-search',['condition'=>$this->condition,'url'=>$this->url,'more_search'=>$more_search]);
    }
}
