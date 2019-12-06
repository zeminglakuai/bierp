<?php
namespace app\common\actions;

use Yii;
use yii\base\Action;
use app\common\models\FileInfo;
use app\includes\Message;

class DeleteFileAction extends Action {
    public function run() {
    	$id = Yii::$app->request->get('id'); 	
    	$file = FileInfo::findone($id);
    	if ($file) {
    		$file->delete();
            //清除相关文件
    		Message::result_json(1,'seccess');
    	}else{
    		Message::result_json(2,'数据不存在');
    	}
	}
}