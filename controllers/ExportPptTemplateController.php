<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\Url;
use app\common\models\ExportPptTable;
use app\common\models\Uploadfile;
use yii\web\UploadedFile;
use app\includes\Message;
use app\includes\Common_fun;
use app\common\config\sys_config;
use app\controllers\BaseController;
use yii\helpers\BaseFileHelper;

class ExportPptTemplateController extends BaseController
{   
    public $enableCsrfValidation = false;
    
    public function actionIndex()
    {
    	$module_list = [];
    	foreach (sys_config::$nav_list as $key => $value) {
    		if (isset($value['sub_list'])) {
    			foreach ($value['sub_list'] as $kk => $vv) {
                    if (isset($vv['export_ppt'])) {
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
    	$template_list = false;
    	// foreach (sys_config::$nav_list as $key => $value) {
    	// 	if (array_key_exists($module, $value['sub_list'])) {
    	// 		if (isset($value['sub_list'][$module]['export_ppt'])) {
    	// 			$title['label_name'] = $value['sub_list'][$module]['name'].'单据列表';
    	// 			$title['title_name'] = $value['sub_list'][$module]['title'];
    	// 		}
    	// 		break;
    	// 	}
    	// }
 		$template_list = ExportPptTable::find()->where(['module_name'=>$module])->all();


        $content =  $this->render('template-list', [
        								'module' => $module,
                                       'template_list' => $template_list,
        ]);

        Message::result_json(1,'',$content);
    }

    public function actionCreate()
    {
    	$this->layout = 'empty';
    	$module = Yii::$app->request->get('module',0);
    	$type = Yii::$app->request->get('type',0);

    	if ($module) {
    		return $this->render('edit', [
    						       'module' => $module,
       		]);
    	}else{
    		message::show_message('asdasdas');
    	}
    }

    public function actionInsert()
    {
        $title = Yii::$app->request->post('title');
        $detail = Yii::$app->request->post('detail');

        $ExportPptTable = new ExportPptTable();
        if ($ExportPptTable->load(Yii::$app->request->post())) {
            if (!isset($ExportPptTable->template_name) || $ExportPptTable->template_name == '') {
                 Message::result_json(2,'模板名称必须填写');
            }

            $page_face = UploadedFile::getInstance($ExportPptTable, 'page_face');
            if ($page_face) {
                $filed_name = 'uploads/'.date('Ym');
                BaseFileHelper::createDirectory($filed_name);
                $new_img_name = $filed_name.'/'.Yii::$app->security->generateRandomString(20) . '.' . $page_face->extension;
                $page_face->saveAs($new_img_name);
                $ExportPptTable->page_face = $new_img_name;
            }

            $page_back = UploadedFile::getInstance($ExportPptTable, 'page_back');
            if ($page_back) {
                $filed_name = 'uploads/'.date('Ym');
                BaseFileHelper::createDirectory($filed_name);
                $new_img_name = $filed_name.'/'.Yii::$app->security->generateRandomString(20) . '.' . $page_back->extension;
                $page_back->saveAs($new_img_name);
                $ExportPptTable->page_back = $new_img_name;
            }

            foreach (sys_config::$nav_list as $key => $value) {
                if (array_key_exists($ExportPptTable->module_name, $value['sub_list'])) {
                    $ExportPptTable->title_module = $value['sub_list'][$ExportPptTable->module_name]['title'];
                    $ExportPptTable->module = $value['sub_list'][$ExportPptTable->module_name]['export_ppt'];
                    break;
                }
            }

            $ExportPptTable->save(false);
            Message::result_json(1,'添加成功');

        } else {
            Message::result_json(2,'缺少参数');
        }
    }

    public function actionEdit($id)
    {
        $this->layout = 'empty';
    	$ExportPptTable = ExportPptTable::findone($id);

        return $this->render('edit', [
                                    'export_ppt_table' => $ExportPptTable,
        ]);
    }

    public function actionUpdate($id)
    {

        $ExportPptTable = ExportPptTable::findone($id);
        if ($ExportPptTable->load(Yii::$app->request->post())) {
            if (!isset($ExportPptTable->template_name) || $ExportPptTable->template_name == '') {
                 Message::result_json(2,'模板名称必须填写');
            }

            $page_face = UploadedFile::getInstance($ExportPptTable, 'page_face');
            if ($page_face) {
                $filed_name = 'uploads/'.date('Ym');
                BaseFileHelper::createDirectory($filed_name);
                $new_img_name = $filed_name.'/'.Yii::$app->security->generateRandomString(20) . '.' . $page_face->extension;
                $page_face->saveAs($new_img_name);
                $ExportPptTable->page_face = $new_img_name;
            }

            $page_back = UploadedFile::getInstance($ExportPptTable, 'page_back');
            if ($page_back) {
                $filed_name = 'uploads/'.date('Ym');
                BaseFileHelper::createDirectory($filed_name);
                $new_img_name = $filed_name.'/'.Yii::$app->security->generateRandomString(20) . '.' . $page_back->extension;
                $page_back->saveAs($new_img_name);
                $ExportPptTable->page_back = $new_img_name;
            }

            foreach (sys_config::$nav_list as $key => $value) {
                if (array_key_exists($ExportPptTable->module_name, $value['sub_list'])) {
                    $ExportPptTable->title_module = $value['sub_list'][$ExportPptTable->module_name]['title'];
                    $ExportPptTable->module = $value['sub_list'][$ExportPptTable->module_name]['export_ppt'];
                    break;
                }
            }

            $ExportPptTable->save(false);
            Message::result_json(1,'编辑成功');

        } else {
            Message::result_json(2,'缺少参数');
        }
    }

    public function actionDelete($id)
    {
        $ExportPptTable = ExportPptTable::findone($id);
        $ExportPptTable->delete();
        Message::result_json(1,'删除成功');
    }
}
