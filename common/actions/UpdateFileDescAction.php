<?php
namespace app\common\actions;

use Yii;
use yii\base\Action;
use app\common\models\FileInfo;
use app\includes\Message;

class UpdateFileDescAction extends Action {
    public function run() {
    	$desc = Yii::$app->request->get('desc');
    	if (empty($desc)) {
    		Message::result_json(2,'描述内容不能为空');
    	}
    	$id = Yii::$app->request->get('id'); 	
    	$file = FileInfo::findone($id);
    	if ($file) {
    		$file->file_desc = $desc;
    		$file->save(false);
    		Message::result_json(1,'更新成功');
    	}else{
    		Message::result_json(2,'数据不存在');
    	}
	}
}