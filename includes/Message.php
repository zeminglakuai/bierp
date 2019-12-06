<?php
namespace app\includes;

use Yii;
use yii\web\Controller;

class Message extends Controller
{	
	public $layout = 'main';
	public function show_message($message='',$links=array(),$type='notic'){

		//更改显示的基本模板
		$this->layout = 'empty';

		if (empty($message)) {
			$message = '没有任何提示';
		}

		if (count($links) < 1 || empty($links)) {
			$links = [['link_name'=>'返回上一页','link_url'=>'javascript:history.back(-1);']];
		}

		$this->id='default';
		
		$html_content = $this->render('message', [
		        'message' => $message,
		        'type' => $type,
		        'links' => $links
		]);
		die($html_content);
		return false;
	}

 	public function result_json($error = 0,$message='',$content = '',$calculate_value){
		$result = ['error'=>$error,'message'=>$message,'content'=>$content,'calculate_value'=>$calculate_value];
		die(json_encode($result));
		 
	}
} 

?>