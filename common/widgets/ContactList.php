<?php
namespace app\common\widgets;

use Yii;
use yii\data\Pagination;
use app\common\models\DictionaryValue;


/*
$model          数据源对象
$title          ['id'=>1,'custom_name'=>0] 1:需要排序 0：不需要排序 默认为2
$search_date    ['id'=>1,'custom_name'=>2] 1:= ,2:like, 3,> 4,<

*/
class ContactList extends \yii\base\Widget
{
    public $contact_list;
    public $main_body;
    public $extend_type;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $dictionary_id = $this->extend_type?11:6;
        $filed_arr = DictionaryValue::find()->where(['dictionary_id'=>$dictionary_id])->orderby('sort desc')->all();

        return $this->render('contact-list',[
                            'contact_list'=>$this->contact_list,
                            'filed_arr'=>$filed_arr,
                            'main_body'=>$this->main_body,
                            ]
            );
    }
}
