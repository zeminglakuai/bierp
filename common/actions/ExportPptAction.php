<?php

namespace app\common\actions;

use Yii;
use yii\base\Action;
use yii\helpers\FileHelper;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Style\Alignment;
use app\common\models\CustomOrderGoods;
use app\common\models\ExportPptTable;

use app\common\config\lang_config;
use app\common\config\lang_value_config;

\PhpOffice\PhpPresentation\Autoloader::register();


class ExportPptAction extends Action
{

    public function run()
    {
        $id = Yii::$app->request->get('id', 0);

        //得到商品列表
        $data_list = Yii::$app->db->createCommand("select g.*,cog.jd_price,cog.tmall_price,cog.ppt_price,b.brand_name,co.* from custom_order_goods as cog left join custom_order as co on co.id=cog.order_id left join goods as g on g.goods_id=cog.goods_id left join brand as b on b.id=g.brand_id where co.id=" . $id)->queryAll();

        $objPHPPowerPoint = new PhpPresentation();

        if (isset($data_list)) {
            foreach ($data_list as $key => $value) {
                if ($key==0) {
                    $currentSlide = $objPHPPowerPoint->getActiveSlide();
                }else{
                    $currentSlide = $objPHPPowerPoint->createSlide();
                }

                $shape = $currentSlide->createDrawingShape();
                $shape->setName($value['goods_name'])
                    ->setDescription($value['goods_name'])
                    ->setPath($value['goods_img'])
                    ->setHeight(450)
                    ->setWidth(450)
                    ->setOffsetX(75)
                    ->setOffsetY(120);
                $img_list= Yii::$app->db->createCommand("select * from file_info where belong_id=". $value['goods_id']. " and model= 'goods' order by id limit 0,3 ")->queryAll();
                $x = 75;
                foreach ($img_list as $k => $v) {
                    $shape = $currentSlide->createDrawingShape();

                    $shape->setName($k)
                        ->setDescription($k)
                        ->setPath($v['file_path'])
                        ->setHeight(100)
                        ->setWidth(100)
                        ->setOffsetX($x)
                        ->setOffsetY(570);
                    $x=150+$x;

                }
                $shape = $currentSlide->createRichTextShape()
                    ->setHeight(180)
                    ->setWidth(600)
                    ->setOffsetX(75)
                    ->setOffsetY(60);

                $textRun = $shape->createTextRun('产品名称: '.$value['goods_name']);

                $textRun->getFont()->setBold(true)
                    ->setSize(16)
                    ->setColor(new Color('000'));

                $shape = $currentSlide->createRichTextShape()
                    ->setHeight(50)
                    ->setWidth(400)
                    ->setOffsetX(550)
                    ->setOffsetY(100);

                $textRun = $shape->createTextRun('产品信息: ');
                $textRun->getFont()->setBold(true)
                    ->setSize(16)
                    ->setColor(new Color('000'));

                $shape = $currentSlide->createRichTextShape()
                    ->setHeight(470)
                    ->setWidth(400)
                    ->setOffsetX(550)
                    ->setOffsetY(140);
                $info= '名称：'.$value['goods_name']. '
' . '品牌：' . $value['brand_name'] . '
' . '型号：' . $value['goods_sn'] ;
                $textRun = $shape->createTextRun($info);
                $textRun->getFont()
                    ->setSize(12)
                    ->setColor(new Color('000'));

                $shape = $currentSlide->createRichTextShape()
                    ->setHeight(250)
                    ->setWidth(400)
                    ->setOffsetX(550)
                    ->setOffsetY(570);

                $textRun = $shape->createTextRun('报价: '.$value['ppt_price']);
                $textRun->getFont()->setBold(true)
                    ->setSize(16)
                    ->setColor(new Color('000'));
            }




            $oWriterPPTX = IOFactory::createWriter($objPHPPowerPoint, 'PowerPoint2007');
            $file_name = date('Y-m-d H:i');
            ob_end_clean();

            /*实例化excel输入类并完成输出excel文件*/
            // $write = new \PHPExcel_Writer_Excel5($excel);
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition:inline;filename=\"" .  date('YmdHis') . ".pptx\"");
            header("Content-Transfer-Encoding: binary");
            header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Pragma: no-cache");
            $oWriterPPTX->save('php://output');
            exit;
        }
    }
}
