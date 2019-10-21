<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\Url;
use app\common\models\ExportTable;
use app\includes\Message;
use app\includes\Common_fun;
use app\common\config\export_config;
use app\common\config\sys_config;
use app\controllers\BaseController;


class ExportTemplateController extends BaseController
{   
    public $enableCsrfValidation = false;
    
    public function actionIndex()
    {
    	$module_list = [];
    	foreach (sys_config::$nav_list as $key => $value) {
    		if (isset($value['sub_list'])) {
    			foreach ($value['sub_list'] as $kk => $vv) {
                    if (isset($vv['title'])) {
                        if (!isset($module_list[$key])) {
                            $module_list[$key] = ['id'=>$key,'text'=>$value['name']];
                        }
                        $module_list[$key]['children'][] = ['id'=>$kk,'text'=>$vv['name']];
                    }
    			}
    		}
    	}
 
		$module_list = Common_fun::module_arr2jstr($module_list);
        return $this->render('index', [
                                       'module_list' => $module_list,
        ]);
    }


    public function actionTemplateList()
    {
    	$this->layout = false;

    	$module = Yii::$app->request->get('module');
    	$title = false;
    	$detail_list = false;
    	foreach (sys_config::$nav_list as $key => $value) {
    		if (array_key_exists($module, $value['sub_list'])) {
    			if (isset($value['sub_list'][$module]['title'])) {
    				$title['label_name'] = $value['sub_list'][$module]['name'].'单据列表';
    				$title['title_name'] = $value['sub_list'][$module]['title'];
    			}

    			if (isset($value['sub_list'][$module]['detail_list'])) {
    				$detail_list['label_name'] = '单据详情';
    				$detail_list['title_name'] = $value['sub_list'][$module]['detail_list'];
    			}
    			break;
    		}
    	}
    	if ($title) {
    		$title['template_list'] = ExportTable::find()->where(['module_name'=>$module,'type'=>'title'])->orderby(['id'=>'desc'])->all();
    	}

    	if ($detail_list) {
    		$detail_list['template_list'] = ExportTable::find()->where(['module_name'=>$module,'type'=>'detail'])->orderby(['id'=>'desc'])->all();
    	}

        $content =  $this->render('template-list', [
        								'module' => $module,
                                       'title' => $title,
                                       'detail_list' => $detail_list,
        ]);

        Message::result_json(1,'',$content);
    }

    public function actionCreate()
    {
    	$this->layout = 'empty';
    	$module = Yii::$app->request->get('module',0);
    	$type = Yii::$app->request->get('type',0);

    	if ($module && $type) {

            foreach (sys_config::$nav_list as $key => $value) {
                if (array_key_exists($module, $value['sub_list'])) {
                        $title_path = 'app\common\models\\'.$value['sub_list'][$module]['title'];
                        $title_ob = new $title_path;
                        $title_label = $title_ob->exportLabels();

                        $detail_label = false;
                        if ($type == 'detail') {
                            $detail_path = 'app\common\models\\'.$value['sub_list'][$module]['detail_list'];
                            $detail_ob = new $detail_path;
                            $detail_label = $detail_ob->exportLabels();
                        }
                    break;
                }
            }

    		return $this->render('edit', [
    						       'module' => $module,
                                   'type' => $type,
                                   'title_label' => $title_label,
                                   'detail_label' => $detail_label,
       		]);
    	}else{
    		message::show_message('asdasdas');
    	}
 		
    }

    public function actionInsert()
    {
        $title = Yii::$app->request->post('title');
        $detail = Yii::$app->request->post('detail');

        $ExportTable = new ExportTable();
        $ExportTable->template_name=$title;
        if ($ExportTable->load(Yii::$app->request->post())) {
            if (!isset($ExportTable->template_name) || $ExportTable->template_name == '') {
                 Message::result_json(2,'模板名称必须填写');
            }

            foreach (sys_config::$nav_list as $key => $value) {
                if (array_key_exists($ExportTable->module_name, $value['sub_list'])) {
                    $ExportTable->title_module = $value['sub_list'][$ExportTable->module_name]['title'];
                    if (isset($value['sub_list'][$ExportTable->module_name]['detail_list'])) {
                       $ExportTable->detail_module = $value['sub_list'][$ExportTable->module_name]['detail_list'];
                    }
                    break;
                }
            }

            if ($ExportTable->type == 'title') {
                $ExportTable->data = serialize(['title'=>$title]);
            }else{
                $ExportTable->data = serialize(['title'=>$title,'detail'=>$detail]);
            }

            $ExportTable->save(false);
            Message::result_json(1,'添加成功');

        } else {
            Message::result_json(2,'缺少参数');
        }
    }

    public function actionEdit($id)
    {
        $this->layout = 'empty';
    	$ExportTable = ExportTable::findone($id);

        $title_path = 'app\common\models\\'.$ExportTable->title_module;
        $title_ob = new $title_path;
        $title_label = $title_ob->exportLabels();

        $detail_label = false;
        if ($ExportTable->type == 'detail' && !empty($ExportTable->detail_module)) {
            $detail_path = 'app\common\models\\'.$ExportTable->detail_module;
            $detail_ob = new $detail_path;
            $detail_label = $detail_ob->exportLabels();
        }
        $ExportTable->data = unserialize($ExportTable->data);

        return $this->render('edit', [
                                    'export_table' => $ExportTable,
                                    'title_label' => $title_label,
                                    'detail_label' => $detail_label,
        ]);
    }

    public function actionUpdate($id)
    {
        $title = Yii::$app->request->post('title');
        $detail = Yii::$app->request->post('detail');

        $ExportTable = ExportTable::findone($id);
        $ExportTable->load(Yii::$app->request->post());
        if (!isset($ExportTable->template_name) || $ExportTable->template_name == '') {
             Message::result_json(2,'模板名称必须填写');
        }

        if ($ExportTable->type == 'title') {
            $ExportTable->data = serialize(['title'=>$title]);
        }else{
            $ExportTable->data = serialize(['title'=>$title,'detail'=>$detail]);
        }

        $ExportTable->save(false);
        Message::result_json(1,'编辑成功');
    }

    public function actionDelete($id)
    {
        $ExportTable = ExportTable::findone($id);
        $ExportTable->delete();
        Message::result_json(1,'删除成功');
    }
}
