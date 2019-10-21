<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "approval_process".
 *
 * @property integer $id
 * @property string $label_name
 * @property string $process_data
 */
class ApprovalProcess extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'approval_process';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label_name', 'process_data'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label_name' => 'Label Name',
            'process_data' => 'Process Data',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'label_name' => 'Label Name',
            'process_data' => 'Process Data',
        ];
    }

    static function getApprovalValue($label_name){
        $approval = static::find()->where(['label_name'=>$label_name])->one();
        $approval_arr = @unserialize($approval->process_data);

        $result_approval_arr = [];
        foreach ($approval_arr as $key => $value) {
            $result_approval_arr[$key] = $value['process_name'];
        }
        return $result_approval_arr;
    }


}
