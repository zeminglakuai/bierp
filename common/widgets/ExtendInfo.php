<?php
namespace app\common\widgets;

use Yii;
use yii\data\Pagination;
use app\common\models\ContactExtendInfo;
use app\common\models\DictionaryValue;
use yii\helpers\ArrayHelper;


class ExtendInfo extends \yii\base\Widget
{
    public $extend_info;
    public $extend_type;
    
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $dictionary_id = $this->extend_type?11:6;
        $filed_arr = DictionaryValue::find()->where(['dictionary_id'=>$dictionary_id])->orderby('sort desc')->all();

        //处理ExtendInfo 成id value 数组对
        $extend_info_arr = [];
        if (isset($this->extend_info)) {
            foreach ($this->extend_info as $key => $value) {
                $extend_info_arr[$value->filed_id]['id'] = $value->id;
                $extend_info_arr[$value->filed_id]['filed_id'] = $value->filed_id;
                $extend_info_arr[$value->filed_id]['filed_value'] = $value->filed_value;
            }

        }

        
        return $this->render('extend-info',[
                            'extend_info_arr'=>$extend_info_arr,
                            'filed_arr' => $filed_arr,
                            ]
        );
    }
}
