<?php
namespace app\common\widgets;

use Yii;

 
class GoodsList extends \yii\base\Widget
{
    public $model;
    public $model_name;
    public $title_arr;
    public $order_id;
    public $table_id_name;
    public $opration;
    public $init_condition;
    public $update_label_url;
    public $width;    
    public $present_action;    

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $model = $this->model;

        $model_name_arr = explode('\\', $model);
        $model_name = $this->model_name?$this->model_name:$model_name_arr[count($model_name_arr)-1];

        $model_name_lower = strtolower($model_name);

        $data_list = $model::find();
        if ($this->order_id) {
            $this->table_id_name = $this->table_id_name?$this->table_id_name:'order_id';
            $data_list = $data_list->where(['order_id'=>$this->order_id]);
        }
        if (count($this->init_condition) > 0) {
            foreach ($this->init_condition as $key => $value) {
                $data_list = $data_list->andwhere($value);
            }
        }

        $search_data= Yii::$app->request->get();

        $search_data['sortby'] = !empty(trim(Yii::$app->request->get('sortby')))?trim(Yii::$app->request->get('sortby')):'id';
        $search_data['order'] = trim(Yii::$app->request->get('order')) == 'SORT_ASC'?SORT_ASC:SORT_DESC;

        $data_list = $data_list->orderby([$search_data['sortby']=>$search_data['order']])
                               ->all();

        return $this->render('goods-list',[
                                        'data_list'=>$data_list,
                                        'title_arr'=>$this->title_arr,
                                        'search_data' => $search_data,
                                        'model'=>$model,
                                        'model_name'=>$model_name,
                                        'model_name_lower'=>$model_name_lower,
                                        'opration' => $this->opration,
                                        'update_label_url' => $this->update_label_url,
                                        'present_action' => $this->present_action,
                                        'width' => $this->width,                                        
                                        ]
            );
    }
}
