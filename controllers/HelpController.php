<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\Url;
use app\common\models\Help;

use app\includes\Message;
use app\includes\Common_fun;
use app\common\config\export_config;
use app\common\config\sys_config;
use app\controllers\BaseController;


class HelpController extends BaseController
{   
    public $enableCsrfValidation = false;
    public $page_title = '帮助文档';

    public function actionIndex()
    {
    	$module_list = [];
    	foreach (sys_config::$nav_list as $key => $value) {
    		$module_list[$key] = ['id'=>$key,'text'=>$value['name']];
    		if ($value['sub_list']) {
    			foreach ($value['sub_list'] as $kk => $vv) {
    				$module_list[$key]['children'][] = ['id'=>$kk,'text'=>$vv['name']];
    			}
    		}
    	}
 
		$module_list = Common_fun::module_arr2jstr($module_list);
        return $this->render('index', [
                                       'module_list' => $module_list,
        ]);
    }


    public function actionContentFrame()
    {
    	$this->layout = false;
        $module = Yii::$app->request->get('module');
        $content =  $this->render('help-content-frame', [
        								'module' => $module,
        ]);

        Message::result_json(1,'',$content);
    }

    public function actionContent()
    {
        $this->layout = 'empty';
        $module = Yii::$app->request->get('module');
        $help = Help::find()->where(['module'=>$module])->one();

        return  $this->render('help-content', [
                                        'module' => $module,
                                       'help' => $help,
        ]);
    }

    public function actionView()
    {
        $this->layout = 'empty';
        $module = Yii::$app->request->get('module');
        $module = Yii::$app->request->get('model');        
        $help = Help::find()->where(['module'=>$module])->one();

        return  $this->render('content', [
                                        'module' => $module,
                                       'help' => $help,
        ]);
    }

    public function actionUpdate()
    {
        $module = Yii::$app->request->get('module');
        $help = Help::find()->where(['module'=>$module])->one();
        if (!$help) {
           $help = new Help();
        }
        $help->load(Yii::$app->request->post());

        if (!isset($help->content) || $help->content == '') {
             Message::result_json(2,'请填写帮助文档内容');
        }

        $help->save(false);
        Message::result_json(1,'编辑成功');
    }

}
