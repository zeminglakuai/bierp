<?php
namespace app\common\widgets;

use Yii;
use yii\data\Pagination;

/*
$model          数据源对象
$title          ['id'=>1,'custom_name'=>0] 1:需要排序 0：不需要排序 默认为2
$search_date    ['id'=>1,'custom_name'=>2] 1:= ,2:like, 3,> 4,<

*/
class FileList extends \yii\base\Widget
{
    public $model;
    public $file_list;
    public $delete_url;
    public $update_url;
    public $type;

    public function init()
    {
        parent::init();
    }

    public function run()
    {

        $model = $this->model;
        return $this->render('file-list',[
                            'model'=>$this->model,
                            'file_list'=>$this->file_list,
                            'delete_url'=>$this->delete_url,
                            'update_url'=>$this->update_url,
                            'type'=>$this->type,
                            ]
            );
    }
}