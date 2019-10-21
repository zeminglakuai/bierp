<?php
namespace app\common\actions;

use Yii;
use yii\base\Action;
use app\common\models\ExportTable;
use app\common\models\Category;
use app\common\models\Brand;
use app\common\models\Supplier;
use app\common\config\lang_config;
use app\common\config\lang_value_config;

class ExportgAction extends Action {
    public $letter = ['A','B','C','D','E','F','G','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
                      'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',
                      'BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ'
                      ];
    public function run() {
    	$template_id = Yii::$app->request->get('template_id',0);
    	$id = Yii::$app->request->get('id',0);

    	$export = ExportTable::findone($template_id);
    	if (!$export) {
    		echo 'foo2bar';
    	}

      $title_arr = @unserialize($export->data);

    	if ($export->type == 'title') {
    		$title_model = 'app\common\models\\'.$export->title_module;
        	$data_list = $title_model::find();

	        //用户权限范围
	        if ($this->controller->scope) {
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

	        if (isset($this->controller->slave) && $this->controller->slave) {
	           $data_list = $data_list->joinwith($this->controller->slave);
	        }

	        if (isset($this->controller->init_condition) && count($this->controller->init_condition) > 0) {
	            foreach ($this->controller->init_condition as $key => $value) {
	                $data_list = $data_list->andwhere($value);
	            }
	        }

	        $search_data= Yii::$app->request->get();
	        /*判断是否存在搜索*/
	        if (isset($search_data['sea'])){
                    switch ($search_data['seaId']){
//                        1为商品
                        case 1:
                          foreach ($search_data['sea'] as $key=>$val){
                              switch ($key){
                                  case 'goods_name':
                                      $data_list = $data_list->andwhere(['like','goods_name',trim($val)]);
                                      break;
                                  case 'goods_id':
                                      $data_list = $data_list->andwhere(['goods_id'=>$val]);
                                      break;
                                  case 'category':
                                      //得到分类的子分类
                                      $children_cate_id = Category::cat_children_str($val);
                                      $data_list = $data_list->andwhere(['IN','cat_id',$children_cate_id]);
                                      break;
                                  case 'brand_id':
                                      $data_list = $data_list->andwhere(['brand_id'=>$val]);
                                      break;
                                  case 'supplier_name':
                                      $data_list = $data_list->andwhere(['like','supplier_name',$val]);
                                      break;
                                  case 'purchase_start':
                                      $data_list = $data_list->andwhere(['>','supplier_price',$val]);
                                      break;
                                  case 'purchase_end':
                                      $data_list = $data_list->andwhere(['<','supplier_price',$val]);
                                      break;
                                  case 'market_start':
                                      $data_list = $data_list->andwhere(['>','market_price',$val]);
                                      break;
                                  case 'market_end':
                                      $data_list = $data_list->andwhere(['<','market_price',$val]);
                                      break;
                                  case 'sale_start':
                                      $data_list = $data_list->andwhere(['>','shop_price',$val]);
                                      break;
                                  case 'sale_end':
                                      $data_list = $data_list->andwhere(['<','shop_price',$val]);
                                      break;
                                  case 'type':
                                      $data_list = $data_list->andwhere(['type'=>$val]);
                                      break;
                              }
                          }
                            break;

                    }

            }
	        $search_data['sortby'] = !empty(trim(Yii::$app->request->get('sortby')))?trim(Yii::$app->request->get('sortby')):'id';
	        $search_data['order'] = trim(Yii::$app->request->get('order')) == 'SORT_ASC'?SORT_ASC:SORT_DESC;

	        foreach ($search_data as $key => $value) {
	            //替换@成.
	            if (array_key_exists($key, $this->controller->search_allowed)) {

	                if ($search_data[$key] <> '') {
	                    $alias_key = str_replace('@', '.', $key);
	                    switch ($this->controller->search_allowed[$key]) {
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

            $countQuery = clone $data_list;
            $pages_count = $countQuery->count();
            /*  if ($pages_count > 300) {
                  echo '不允许导出300条以上的结果';
              }*/
            // print_r($search_data);exit;
            $data_list = $data_list->offset($pages->offset)
                //   ->orderby([$search_data['sortby']=>$search_data['order']])
                ->limit($pages->limit)
                ->all();
            // print_r($data_list);exit;
            $title_data = '';


            $this->export_to_file($export->type,$title_arr,$title_data,$data_list);

    	}else{
    		//检查是不是有ID
    		if (!$id) {
				//echo 'ssssssss';
    		}

    		$title_model = 'app\common\models\\'.$export->title_module;
    		$detail_model = 'app\common\models\\'.$export->detail_module;

    		$title_data  = $title_model::findone($id);

    		$data_list = $detail_model::find()->where(['order_id'=>$id])->all();

        $file_name = isset($title_data->order_name)?$title_data->order_name:isset($title_data->order_sn)?$title_data->order_sn:'';

        $this->export_to_file($export->type,$title_arr,$title_data,$data_list,$file_name);

    	}
        //print_r(unserialize($export->data));
    }

    private function export_to_file($type,$title_arr,$title_data,$data_list,$file_name=''){

        $excel = new \PHPExcel();

        /*设置文本对齐方式*/
        $excel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet = $excel->getActiveSheet();

        $file_name = $file_name<>''?$file_name:date('Y-m-d H:i');
        $excel = $this->set_excel_name($excel,$file_name);

        if ($type == 'title') {
          $excel = $this->set_excel_list($excel,$title_arr['title'],$data_list);
        }else{
          $excel = $this->set_excel_title($excel,$title_arr['title'],$title_data);
          $excel = $this->set_excel_list($excel,$title_arr['detail'],$data_list);
        }
		ob_end_clean();
        /*实例化excel输入类并完成输出excel文件*/
        $write = new \PHPExcel_Writer_Excel5($excel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        $file_name = $file_name.'.xls';
        header('Content-Disposition:attachment;filename="'.$file_name.'"');
        header("Content-Transfer-Encoding:binary");
        $write->save('php://output');
    }

    private function set_excel_name($excel,$excel_name){

      $excel->getActiveSheet()->setCellValue('A1',$excel_name);
      return $excel;
    }

    private function set_excel_title($excel,$title_label,$title_data){
        /*填充表格表头*/
        $title_i = 0;
        foreach ($title_label as $key => $value) {
            $excel->getActiveSheet()->getColumnDimension($this->letter[$title_i])->setWidth(20);
//            $excel->getActiveSheet()->setCellValue($this->letter[$title_i]."3",isset(lang_config::${$key})?lang_config::${$key}:$value);
            $excel->getActiveSheet()->setCellValue($this->letter[$title_i]."3",$value);
            $title_i++;

        $excel->getActiveSheet()->getColumnDimension($this->letter[$title_i])->setWidth(20);


        if (strpos($key, '_status')) {
           $true_value = lang_value_config::${$key}[$title_data[$key]];
        }elseif(strpos($key, '_time')){
          $true_value = date('Y-m-d H:i:s',$true_value);
        }elseif(strpos($key, '&') > 0){
          $data_label_arr = explode('&', $kkk);
          $true_value = $value->{$data_label_arr[0]}->$data_label_arr[1];
        }else{
          $true_value = $title_data[$key]?$title_data[$key]:'';
        }

        $excel->getActiveSheet()->setCellValue($this->letter[$title_i]."3",$true_value);
        $title_i++;
      }
      return $excel;
    }
    /*
    $list_data 数据
    $title_label 模板名称
    */
    private function set_excel_list($excel,$title_label,$list_data){
        /*填充表格表头*/
        $header_i = 0;
        $excel->getActiveSheet()->setCellValue($this->letter[$header_i]."5",'序号');
        $header_i++;
        foreach ($title_label as $key => $value) {

            $excel->getActiveSheet()->getColumnDimension($this->letter[$header_i])->setWidth(20);
//            $excel->getActiveSheet()->setCellValue($this->letter[$header_i]."5",isset(lang_config::${$key})?lang_config::${$key}:$value);
            $excel->getActiveSheet()->setCellValue($this->letter[$header_i]."5",$value);

            $header_i++;
        }

        /*填充表格内容*/
        $i = 0;
        foreach ($list_data as $key => $value) {
            if (!empty($value['cat_id'])) {
                $category=new Category();
                $value['cat_id']=$category->getName($value['cat_id']);

            }
            if (!empty($value['brand_id'])) {
                $brand=new brand();
                $value['brand_id']=$brand->getName($value['brand_id']);
            }
            $j = $i + 6;
            /*向每行单元格插入数据*/
            $col = 0;
            $excel->getActiveSheet()->setCellValue($this->letter[$col]."$j",$i+1);
            $col++;
            foreach($title_label as $kk => $vv){


                $is_img = false;
                if ($kk == 'goods_img') {
                    $is_img = true;
                    $excel->getActiveSheet()->getRowDimension($j) -> setRowHeight(60);

                    $objDrawing = new \PHPExcel_Worksheet_Drawing();
                    $fileName = str_replace("\\","/",Yii::getAlias('@app'));

                    $objDrawing->setPath($fileName.'/web/'.$value->$kk);
                    // 设置宽度高度
                    $objDrawing->setHeight(80);//照片高度
                    $objDrawing->setWidth(80); //照片宽度
                    /*设置图片要插入的单元格*/
                    $letter_label = $this->letter[$col];
                    $objDrawing->setCoordinates($letter_label."$j");
                    // 图片偏移距离
                    $objDrawing->setOffsetX(2);
                    $objDrawing->setOffsetY(2);
                    $objDrawing->setWorksheet($excel->getActiveSheet());

                }
                if (isset($value->$kk)) {
                    if (strpos($kk, '_status')) {
                        $true_value = lang_value_config::${$kk}[$true_value];
                    }
                    elseif (strpos($kk, '_time')) {
                        $true_value = date('Y-m-d H:i:s',$true_value);

                    }

                    elseif(strpos($kk, '&') > 0){
                    }else{
                        $true_value = $value->$kk;

                    }
                }else{
                    $true_value =0;
                }
                if (!$is_img) {
                    $letter_label = $this->letter[$col];

                    $excel->getActiveSheet()->setCellValue($letter_label."$j",$true_value);
                }

            $col++;
          }
          $i++;
        }
        return $excel;
    }

}