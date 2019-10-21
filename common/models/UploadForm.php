<?php

namespace app\common\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\BaseFileHelper;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file', 'extensions' => 'jpg,png', 'maxSize'=>2330000],
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
        $image_arr = [];
        $result_arr = [];
        $img_desc = Yii::$app->request->post('img_desc');

        if (count($file) < 1) {
           return false;
        }
        foreach($file['name'] as $key=>$value){
            $data['name'] = $file['name'][$key];
            $data['type'] = $file['type'][$key];
            $data['tmp_name'] = $file['tmp_name'][$key];
            $data['error'] = $file['error'][$key];
            $data['size'] = $file['size'][$key];

            //检查是不是图片类型
            $data_extension = explode('/', $file['type'][$key]);
            if ($data_extension[0] !== 'image') {
                $result_arr[] = $file['name'][$key].'不是图片类型';
                break;
            }
            //检查大小
            if ($file['size'][$key] > 2330000) {
                $result_arr[] = $file['name'][$key].'图片大于2M';
                break; 
            }

            $filed_name = 'uploads/'.date('Ym');
            BaseFileHelper::createDirectory($filed_name);
            $file_name = $filed_name.'/'.Yii::$app->security->generateRandomString(20) . '.' . $data_extension[1];
            move_uploaded_file($file['tmp_name'][$key], $file_name);

            $image_arr[] = ['file_name'=>$file_name,'img_desc'=>$img_desc[$key]];
        }


        $result = ['file'=>$image_arr,'error'=>$image_arr];
        return $result;
    }


    static function upload_files()
    {
        $file = $_FILES['file'];
        $file_desc = Yii::$app->request->post('file_desc');
        $file_type = Yii::$app->request->post('file_type');

        $file_arr = [];
        $result_arr = [];
        if (count($file) < 1) {
           return false;
        }

        $allow_arr = ['txt','pdf','doc','docx','xlsx','ppt','xls','pptx','jpg','png','gif','jpeg','pdf'];
        foreach($file['name'] as $key=>$value){
            $data['name'] = $file['name'][$key];
            $data['type'] = $file['type'][$key];
            $data['tmp_name'] = $file['tmp_name'][$key];
            $data['error'] = $file['error'][$key];
            $data['size'] = $file['size'][$key];

            //获取文件后缀名
            $dot_pos = strrpos($data['name'],".");
            $file_houzhui = substr($data['name'], $dot_pos+1);

            //检查是不是允许类型
            if (!in_array($file_houzhui, $allow_arr)) {
                $result_arr[] = $file['name'][$key].'不是允许上传'.$data_extension[1].'类型文件';
                continue;
            }
            //检查大小
            if ($file['size'][$key] > 2330000) {
                $result_arr[] = $file['name'][$key].'文件大于2M';
                continue;
            }

            $filed_name = 'uploads/files/'.date('Ym');
            BaseFileHelper::createDirectory($filed_name);
            $file_name = $filed_name.'/'.Yii::$app->security->generateRandomString(20) . '.' . $file_houzhui;
            move_uploaded_file($file['tmp_name'][$key], $file_name);
            $file_arr[] = ['file_name'=>$file_name,'file_desc'=>$file_desc[$key],'file_type'=>$file_type[$key]];
            
        }

        $result = ['file'=>$file_arr,'error'=>$result_arr];
        return $result;
    }

}
