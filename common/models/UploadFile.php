<?php

namespace app\common\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\BaseFileHelper;

class UploadFile extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file', 'maxSize'=>2330000],
        ];
    }

    public function attributeLabels(){  
        return [  
            'file'=>'fdfd ',
        ];  
    } 

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->file as $key=> $fl) {
                $filed_name = 'uploads/'.date('Ym');
                BaseFileHelper::createDirectory($filed_name);
                $file_name[] = $filed_name.'/'.Yii::$app->security->generateRandomString(20) . '.' . $fl->extension;
                $fl->saveAs($filed_name);
            }
            return $file_name;
        } else {
            return false;
        }
    }

    static function upload_mul()
    {
        $file = $_FILES['file'];
        $file_arr = [];
        if (count($file) < 1) {
           return false;
        }

        $allow_arr = ['pdf','doc','docx','xlsx','ppt','xls','pptx','jpeg','jpg','txt','gif','png'];
        foreach($file['name'] as $key=>$value){
            $data['name'] = $file['name'][$key];
            $data['type'] = $file['type'][$key];
            $data['tmp_name'] = $file['tmp_name'][$key];
            $data['error'] = $file['error'][$key];
            $data['size'] = $file['size'][$key];


            //检查是不是图片类型
            $data_extension = explode('/', $file['type'][$key]);
            if (!in_array($data_extension[1], $allow_arr)) {
                return $file['name'][$key].'不是允许上传'.$data_extension[1].'类型文件';
                break;
            }
            //检查大小
            if ($file['size'][$key] > 2330000) {
                return $file['name'][$key].'文件大于2M';
                break; 
            }

            $filed_name = 'uploads/files/'.date('Ym');
            BaseFileHelper::createDirectory($filed_name);
            $file_name = $filed_name.'/'.Yii::$app->security->generateRandomString(20) . '.' . $data_extension[1];
            move_uploaded_file($file['tmp_name'][$key], $file_name);
            $file_arr[] = $file_name;
        }
        return $file_arr;
    }




}
