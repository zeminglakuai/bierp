<?php
namespace app\teamwork\controllers;

use yii;
use yii\web\Controller;
use app\teamwork\controllers\BaseController;
use app\includes\Common_fun;
use app\common\models\Teamwork;
use app\includes\message;
use app\common\config\sys_config;
use yii\helpers\ArrayHelper;

class DefaultController extends BaseController
{
    public $page_title = 'BUG跟踪反馈';
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionTest()
    {
        return $this->render('test');
    }
    public function actionGetItemList()
    {
        $status = Yii::$app->request->get('status');

        $data = Teamwork::find();
        if (!empty($status)) {
            if ($status == '99') {
                $data = $data->where(['is_archive'=>1]);
            }else{
                $data = $data->where(['status'=>$status,'is_archive'=>0]);
            }
        }else{
            $data = $data->where(['is_archive'=>0]);
        }
        $data = $data->orderby('id desc')->all();

        $data_list = [];

        for ($i=0; $i < count($data); $i++) { 
            $data_list[$i] = $this->create_return_teamwork_arr($data[$i]);
        }

        message::result_json(1,'success',$data_list);
    }


    public function actionInsert()
    {
 		$Teamwork = new Teamwork();
        $Teamwork->load(Yii::$app->request->post());
        
        $Teamwork->content = urldecode($Teamwork->content);
        $Teamwork->add_time = time();
        $Teamwork->status = 1;
        $Teamwork->add_user_id = Yii::$app->session['manage_user']['id'];
        $Teamwork->add_user_name = Yii::$app->session['manage_user']['admin_name'];

        $Teamwork->save(false);

        $data = $this->create_return_teamwork_arr($Teamwork);

        Message::result_json(1,'添加成功',$data);
    }


    public function actionUpdate()
    {
        $content = Yii::$app->request->post('content');
        $id = Yii::$app->request->post('id');

        $Teamwork = Teamwork::findone($id);
        $Teamwork->content = urldecode($content);
        $Teamwork->save(false);

        $data = $this->create_return_teamwork_arr($Teamwork);

        Message::result_json(1,'更新成功',$data);
    }

    public function actionReply()
    {
        $id = Yii::$app->request->post('id');
        $question = Yii::$app->request->post('question');

        $Teamwork = Teamwork::findone($id);
 
        $Teamwork->question = urldecode($question);
        $Teamwork->save(false);


        Message::result_json(1,'回复成功',$Teamwork->question);
    }

    public function actionChangeStatus($id,$status)
    {

        $Teamwork = Teamwork::findone($id);
        if ($status == 99) {
            $Teamwork->is_archive = 1;
        }else{
           $Teamwork->status = $status; 
        }
        $Teamwork->save(false);

        Message::result_json(1,'处理成功',$status);
    }


    public function actionDelete($id)
    {
        $Teamwork = Teamwork::findone($id);
        $Teamwork->delete();

        Message::result_json(1,'删除成功');
    }



    public function actionGetMainNav()
    {
        $main_nav = [];
        foreach (sys_config::$nav_list as $key => $value) {
            $main_nav[$key]['label'] = $key;
            $main_nav[$key]['name'] = $value['name'];
        }
        message::result_json(1,'success',$main_nav);
    }


    public function actionGetSubNav($label)
    {
        $sub_nav = [];
        foreach (sys_config::$nav_list[$label]['sub_list'] as $key => $value) {
            $sub_nav[$key]['label'] = $key;
            $sub_nav[$key]['name'] = $value['name'];
        }
        message::result_json(1,'success',$sub_nav);
    }

    private function get_content_img($html){
        //检查是不是有img
        $html_arr = explode('<img src="', $html);
        if (count($html_arr) == 1) {
            return false;
        }

        $img_arr = [];
        for ($i=1; $i < count($html_arr); $i++) { 
            //截取data:image/png;base64,
            $img_arr[] = substr($html_arr[$i], 0,strpos($html_arr[$i],'"'));
        }
        return $img_arr;
    }


    public function actionGetNav(){
        $nav_arr =  [];
        foreach (sys_config::$nav_list as $key => $value) {
            $nav_arr[$key] = ['name'=>$value['name']];
            foreach ($value['sub_list'] as $kk => $vv) {
                $nav_arr[$key]['sub_list'][$kk] = ['name'=>$vv['name']];
            }
        }
        message::result_json(1,'success',$nav_arr);
    }

    private function create_return_teamwork_arr($Teamwork){
        $data = [];
        //返回添加的数据
        $data = [   'id'=>$Teamwork->id,
                    'content'=>$Teamwork->content,
                    'add_user_name'=>$Teamwork->add_user_name,
                    'add_time'=>date('Y/m/d H:i',$Teamwork->add_time),
                    'status'=>$Teamwork->status,
                    'question'=>$Teamwork->question,
                    'main_nav'=>$Teamwork->main_nav,
                    'sub_nav'=>$Teamwork->sub_nav,
                ];
        $data['attach'] = $this->get_content_img($Teamwork->content);
        return $data;
    }

}
