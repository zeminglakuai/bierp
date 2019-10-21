<?php
namespace app\common\widgets;

use Yii;
use app\common\models\ExportTable;
use app\common\models\ExportgTable;
use app\common\models\ExportPptTable;
use app\common\models\ApprovalProcess;
/*
$other_btn
[
    ['btn_label'=>'审核','type'=>'js','id'=>'admit','action'=>'admit','icon'=>'eyes']
    ['btn_label'=>'红冲']
    ['btn_label'=>'审核']
    ['btn_label'=>'审核']
]
*/
 
class OperateBar extends \yii\base\Widget
{
    public $create;
    public $export;
    public $exports;
    public $export_ppt;
    public $backup;
    public $admit;
    public $admit_process;
    public $refresh;
    public $other_btn;
    public function init()
    {
        parent::init();
    }

    public function run()
    {

        $export_list = [];
        
        if ($this->export['module_name'] && $this->export['type']) {
            $export_list = ExportTable::find()->where(['module_name'=>$this->export['module_name'],'type'=>$this->export['type']])->all();
        }
        $exports_list = [];

        if ($this->exports['module_name'] && $this->exports['type']) {
            $exports_list = ExportTable::find()->where(['module_name'=>$this->exports['module_name'],'type'=>$this->exports['type']])->all();
        }

        $export_ppt_list = [];
        
        if ($this->export['module_name']) {
            $export_ppt_list = ExportPptTable::find()->where(['module_name'=>$this->export['module_name']])->all();
        }
        
        if ($this->admit_process) {
           $approval_process = ApprovalProcess::find()->where(['label_name' => $this->admit_process['controller']])->one();
           $process_data_arr = @unserialize($approval_process->process_data);
        }


        return $this->render('operate-bar',['create'=>$this->create,
                                            'export'=>$this->export,
                'exports'=>$this->exports,
                                            'export_ppt'=>$this->export_ppt,
                                            'export_list'=>$export_list,
                                            'export_ppt_list'=>$export_ppt_list,
                                            'backup'=>$this->backup,
                                            'admit'=>$this->admit,
                                            'refresh'=>$this->refresh,
                                            'other_btn'=>$this->other_btn,
                                            'admit_process'=>$this->admit_process,
                                            'approval_process'=>$process_data_arr,
                                            ]
                            );
    }
}
