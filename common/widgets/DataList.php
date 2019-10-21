<?php
namespace app\common\widgets;

use Yii;
use yii\data\Pagination;

/*
$model          数据源对象
$title          ['id'=>1,'custom_name'=>0] 1:需要排序 0：不需要排序 默认为2
$search_date    ['id'=>1,'custom_name'=>2] 1:= ,2:like, 3,> 4,<

*/
class DataList extends \yii\base\Widget
{
    public $model;
    public $model_name;
    public $title_arr;
    public $init_condition;
    public $search_allowed;
    public $opration;
    public $page_size;
    public $slave;
    public $slave_condition;
    public $scope = 0;
    public $data_list;
    public $pages;
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

        if ($this->data_list) {
            $data_list = $this->data_list;
            $pages = $this->pages;
        }else{
            $data_list = $model::find();

            //用户权限范围

            if ($this->scope) {
                if (@$privi = unserialize(Yii::$app->session['manage_user']['role']->priv_arr)) {
                   if (isset($privi[$model_name]['scope']) && $privi[$model_name]['scope'] == 1) { //全部数据
                    
                   }elseif (isset($privi[$model_name]['scope']) && $privi[$model_name]['scope'] == 2) { //当前部门数据
                     
                       $data_list = $data_list->andwhere(['depart_id'=>Yii::$app->session['manage_user']['depart_id']]);
                   }elseif (isset($privi[$model_name]['scope']) && $privi[$model_name]['scope'] == 3) {//只查看自己添加的数据
                         
                        $data_list = $data_list->andwhere(['add_user_id'=>Yii::$app->session['manage_user']['id']]);
                   }else{

                   }
                }else{
                }
            }

            if ($this->slave) {
               $data_list = $data_list->joinwith($this->slave);
            }

            if (count($this->init_condition) > 0) {
                foreach ($this->init_condition as $key => $value) {
                    $data_list = $data_list->andwhere($value);
                }
            }
            if ($model_name=='Supplier'){
                $data_list = $data_list->andwhere('old_id=0 and supplier_status!=0');
            }

            $search_data= Yii::$app->request->get();

            $search_data['sortby'] = !empty(trim(Yii::$app->request->get('sortby')))?trim(Yii::$app->request->get('sortby')):'id';
            $search_data['order'] = trim(Yii::$app->request->get('order')) == 'SORT_ASC'?SORT_ASC:SORT_DESC;

            foreach ($search_data as $key => $value) {
                //替换@成.
                if (array_key_exists($key, $this->search_allowed)) {
                    if ($search_data[$key] <> '') {
                        $alias_key = str_replace('@', '.', $key);
                        switch ($this->search_allowed[$key]) {
                            case 1:
                                $search_arr = [$alias_key=>$search_data[$key]];
                                break;
                            case 2:
                                $search_arr = ['like',$alias_key,$search_data[$key]];
                                break;
                            case 3:
                                $search_arr = ['>',$alias_key,$search_data[$key]];
                                break;
                            case 4:
                                $search_arr = ['<',$alias_key,$search_data[$key]];
                                break;
                            case 5:
                                $search_arr = ['in',$alias_key,$search_data[$key]];
                                break;
                            default:
                                break;
                        }
                        $data_list = $data_list->andwhere($search_arr);
                    }
                }
            }

            if ($search_data['add_user_id']) {
               $data_list = $data_list->andwhere(['add_user_id'=>$search_data['add_user_id']]);
            }
            if ($search_data['add_depart_id']) {
               $data_list = $data_list->andwhere(['depart_id'=>$search_data['add_depart_id']]);
            }
            if ($search_data['order_start_time']) {
               $search_start_time = strtotime($search_data['order_start_time']);
               $data_list = $data_list->andwhere(['>','add_time',$search_start_time]);
            }
            if ($search_data['order_end_time']) {
               $search_end_time = strtotime($search_data['order_end_time']);
               $data_list = $data_list->andwhere(['<','add_time',$search_end_time]);
            }


            $countQuery = clone $data_list;
            $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>$this->page_size?$this->page_size:10,'pageSizeLimit'=>1]);

            $data_list = $data_list->offset($pages->offset)
                                    ->orderby([$search_data['sortby']=>$search_data['order']])
                                    ->limit($pages->limit)
                                    ->all();
        }

        return $this->render('data-list',[
                                        'data_list'=>$data_list,
                                        'pages'=>$pages,
                                        'title_arr'=>$this->title_arr,
                                        'search_allowed' => $this->search_allowed,
                                        'search_data' => $search_data,
                                        'model'=>$model,
                                        'model_name'=>$model_name,
                                        'model_name_lower'=>$model_name_lower,
                                        'opration' => $this->opration
                                        ]
            );
    }
}
