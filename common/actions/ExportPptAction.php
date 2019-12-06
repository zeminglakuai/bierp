<?php
namespace app\common\actions;

use Yii;
use yii\base\Action;
use yii\helpers\FileHelper;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use app\common\models\ExportPptTable;

use app\common\config\lang_config;
use app\common\config\lang_value_config;


class ExportPptAction extends Action {
 
	public function run() {

    	$template_id = Yii::$app->request->get('template_id',0);
    	$id = Yii::$app->request->get('id',0);

    	$export = ExportPptTable::findone($template_id);
    	if (!$export) {
    		echo 'foo2bar';
    	}

    	$oReader = IOFactory::createReader('PowerPoint2007');
		$ppt_file = $oReader->load('12.pptx');

		//print_r($ppt_file);
 		try {
            $com = new \COM('com.sun.star.ServiceManager');
        } catch (Exception $e) {
            die('Please be sure that OpenOffice.org is installed.');
        }

  //   	//得到商品列表
  //   	if ($export->title_module) {
  //   		$title_model = 'app\common\models\\'.$export->title_module;
  //   	}
    	
		// $list_model = 'app\common\models\\'.$export->module;

		// $list_date = $list_model::find()->where(['order_id'=>$id])->all();

		// $oPHPPresentation = new PhpPresentation();
		// $oPHPPresentation->getProperties()->setCreator('My name')->setCompany('My factory')->setTitle('My title')->setCategory('My category')->setSubject('My subject');
		// $oPHPPresentation->removeSlideByIndex(0);

		// //创建封面
		// if ($export->page_face) {
		// 	$oPHPPresentation = $this->create_slide_by_img($oPHPPresentation,$export->page_face,960);
		// }
		
		// //创建商品循环
		// foreach ($list_date as $key => $value) {
		// 	if ($value->goods->goods_img) {
		// 		$oPHPPresentation = $this->create_slide_by_img($oPHPPresentation,$value->goods->goods_img,960);
		// 	}
		// }

		// //创建封底
		// if ($export->page_back) {
		// 	$oPHPPresentation = $this->create_slide_by_img($oPHPPresentation,$export->page_back,960);
		// }

		$objWriter = IOFactory::createWriter($ppt_file, 'PowerPoint2007');   
	 	header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-powerpoint");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        $file_name = ($title_date->order_name?$title_date->order_name:'').$export->page_title.'.pptx';
        $file_name = '123123.pptx';
        header('Content-Disposition:attachment;filename="'.$file_name.'"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
	}

	private function create_slide_by_img($PhpPresentation,$img,$width=0,$height=0){
		
		$width = min($width,960);
		$height = min($height,720);
		//检查图片信息
		if ($width && !$height) {
			//如果有设置宽度的情况下 高度超出
		}
		$currentSlide = $PhpPresentation->createSlide();
		$oShape = $currentSlide->createDrawingShape();
		$oShape->setName(time());

		if (!file_exists(Yii::getAlias('@webroot').'/'.$img)) {
			return $PhpPresentation;
		}
		$oShape->setPath($img);
		if ($width) {
			$oShape->setWidth($width);
		}
		if ($height) {
			$oShape->setHeight($height);
		}

		return $PhpPresentation;

	}
}