<?php
namespace app\common\queue;

use Yii;
use yii\base\BaseObject;

class DoSth extends BaseObject implements \yii\queue\JobInterface
{
    public $url;
    public $file;
    
    public function execute($queue)
    {
        file_put_contents($this->file, file_get_contents($this->url));
        echo 'done';
        return true;
    }
}

?>