<?php
namespace app\common\widgets;

use Yii;
use yii\helpers\Url;
use app\common\models\ApprovalLog;
 
class OrderTitle extends \yii\base\Widget
{
    public $model;
    public $model_name;    
    public $id;
    public $label_arr = [];  
    public $status_label; 
    public $model_data; 
    public $controller_id;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        if ($this->model_data) {
            $model = $this->model_data;    
        }else{
            $new_model = $this->model;
            $model = $new_model::findone($this->id);  
        }

        //得到审批流程
        $ApprovalLog = ApprovalLog::find()->where(['controller_label'=>$this->controller_id,'order_id'=>$this->id])->all();
 
        $model_label = $model->attributeLabels();

        $update_url = Url::to(['/'.$this->model_name.'/edit','id'=>$this->id]);

        return $this->render('order-title',['model'=>$model,
                                            'model_label'=>$model_label,
                                            'id'=>$this->id,
                                            'label_arr'=>$this->label_arr,
                                            'status_label'=>$this->status_label,
                                            'update_url'=>$update_url,
                                            'approval_log'=>$ApprovalLog,
                                            ]);
    }
}
