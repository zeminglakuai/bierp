<?php

namespace app\controllers;

use app\includes\Pinyin;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Supplier;
use app\common\models\Category;
use app\common\models\Goods;
use app\common\models\Stock;
use app\common\models\Store;
use yii\web\UploadedFile;
use app\common\models\AskPriceOrder;
use app\common\models\AskPriceOrderGoods;
use app\common\models\ExportOrder;
use app\common\models\ExportOrderGoods;

use app\common\models\ContactExtendInfo;
use app\common\models\Contact;
use app\common\models\Platform;
use app\common\models\Brand;
use app\common\models\BrandSupplier;
use app\common\models\UserExtendInfo;
use app\includes\Message;
use yii\helpers\BaseFileHelper;



class SupplierPriceController extends Controller
{
    public $enableCsrfValidation = false;
    public function actionIndex($id)
    {
        $this->layout = 'empty';
        $secrect = Yii::$app->request->post('secrect');
        $export = Yii::$app->request->get('export');

        $edit_able = false;
        $order_goods = [];
        $error_msg = '';
        $ask_price_order = AskPriceOrder::findone($id);
        if ($ask_price_order) {
            if ($secrect == $ask_price_order->access_secrect || Yii::$app->session['edit_able_' . $id] > 0) {
                $edit_able = true;
                Yii::$app->session['edit_able_' . $id] = 999;
                $order_goods = AskPriceOrderGoods::find()->where(['order_id' => $id])->all();
            }
        } else {
            $error_msg = '单据不存在';
        }

        if ($export) {
            $excel = new \PHPExcel();

            /*设置文本对齐方式*/
            $excel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objActSheet = $excel->getActiveSheet();
            $letter = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

            //设置标题
            $excel->getActiveSheet()->setCellValue('A1', 'vghfghfghfgh');


            //设置表头字段





            /*设置表头数据*/
            $tableheader = array('商品名称', '商品图片', '商品货号', 'ISBN', '需求数量', '供货价', '可供货数量');
            /*填充表格表头*/
            for ($i = 0; $i < count($tableheader); $i++) {

                $objActSheet->getColumnDimension("$letter[$i]")->setWidth(30);
                $excel->getActiveSheet()->setCellValue("$letter[$i]3", "$tableheader[$i]");
            }
            /*填充表格内容*/
            $i = 0;
            $title_arr = ['goods_name', 'goodsImg', 'isbn', 'goods_sn', 'number', 'return_ask_price', 'return_number'];
            foreach ($order_goods as $key => $value) {
                $j = $i + 4;
                /*设置表格高度*/
                $excel->getActiveSheet()->getRowDimension($j)->setRowHeight(100);
                /*向每行单元格插入数据*/
                $row = 0;
                foreach ($title_arr as $kk => $vv) {
                    if ($vv == 'goodsImg') {
                        if (!empty($value->$vv) && file_exists($value->$vv)) {

                            /*实例化插入图片类*/
                            $objDrawing = new \PHPExcel_Worksheet_Drawing();

                            /*设置图片路径 切记：只能是本地图片*/
                            $objDrawing->setPath($value->$vv);
                            /*设置图片高度*/

                            $objDrawing->setHeight(100);
                            /*设置图片要插入的单元格*/
                            $objDrawing->setCoordinates("$letter[$row]$j");
                            /*设置图片所在单元格的格式*/

                            $objDrawing->setOffsetX(10);
                            $objDrawing->setOffsetY(10);
                            $objDrawing->setRotation(20);
                            $objDrawing->getShadow()->setVisible(true);
                            $objDrawing->getShadow()->setDirection(50);
                            $objDrawing->setWorksheet($excel->getActiveSheet());
                        } else {
                            $cell_position = $letter[$row];
                            $excel->getActiveSheet()->setCellValue($cell_position . $j, $value->$vv);
                        }
                    } else {

                        $letter_label = $letter[$row];
                        $excel->getActiveSheet()->setCellValue($letter_label . $j, $value->$vv);
                    }
                    $row++;
                }
                $i++;
            }


            /*实例化excel输入类并完成输出excel文件*/
            $write = new \PHPExcel_Writer_Excel5($excel);
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
            header("Content-Type:application/force-download");
            header("Content-Type:application/vnd.ms-execl");
            header("Content-Type:application/octet-stream");
            header("Content-Type:application/download");
            $file_name = date('Y-m-d H:i') . '.xls';
            header('Content-Disposition:attachment;filename="' . $file_name . '"');
            header("Content-Transfer-Encoding:binary");
            $write->save('php://output');
        }

        return $this->render('index', ['edit_able' => $edit_able, 'ask_price_order' => $ask_price_order, 'order_goods' => $order_goods, 'error_msg' => $error_msg, 'id' => $id]);
    }

    public function actionUpdateLabel()
    {
        $id = Yii::$app->request->get('id', 0);
        $data_id = Yii::$app->request->get('data_id', 0);
        $data_type = Yii::$app->request->get('data_type', 0);
        $data_value = Yii::$app->request->get('data_value', 0);

        if (!is_numeric($data_value)) {
            Message::result_json(2, '请填写数字');
        }

        $order = AskPriceOrder::findone($id);
        if ($order) {
            if ($order->ask_price_order_status > 0) {
                Message::result_json(2, '已审核单据不允许修改');
            }
        }

        $order_goods = AskPriceOrderGoods::find()->where(['id' => $data_id, 'order_id' => $id])->one();
        if ($order_goods) {
            $order_goods->$data_type = $data_value;
            $order_goods->save(false);
            Message::result_json(1, 'seccess', $data_value);
        } else {
            Message::result_json(2, 'wrong');
        }
    }

    public function actionShowTables()
    {

        $table_list = Yii::$app->db->createCommand('show tables')->queryAll();
        foreach ($table_list as $key => $value) {
            try {
                Yii::$app->db->createCommand("ALTER TABLE `" . $value['Tables_in_kewei'] . "`ADD COLUMN `status_done`  tinyint(2) NULL DEFAULT 0 COMMENT '审核流程结束';")->query();
            } catch (Exception $e) {
                echo $value['Tables_in_kewei'] . '<br>';
            }
        }
    }

    public function actionCreateContact()
    {
        echo 'sssssss';

        $supplier = Supplier::find()->all();
        foreach ($supplier as $key => $value) {

            if (!empty($value->contact)) {
                $contact = new Contact;
                $contact->name = $value->contact;
                $contact->tel = $value->tel;
                $contact->position = $value->position;
                $contact->belong_id = $value->id;
                $contact->belong_name = $value->supplier_name;
                $contact->model = 'supplier';
                $contact->add_time = time();
                $contact->is_active = 1;
                $contact->save(false);

                $UserExtendInfo = new UserExtendInfo;
                $UserExtendInfo->filed_id = 20;
                $UserExtendInfo->filed_value = $value->qq;
                $UserExtendInfo->contact_id = $contact->id;
                $UserExtendInfo->save(false);

                $UserExtendInfo = new UserExtendInfo;
                $UserExtendInfo->filed_id = 31;
                $UserExtendInfo->filed_value = $value->position;
                $UserExtendInfo->contact_id = $contact->id;
                $UserExtendInfo->save(false);
            }

            if (!empty($value->contact2)) {
                $contact = new Contact;
                $contact->name = $value->contact2;
                $contact->tel = $value->tel2;
                $contact->position = '采购经理';
                $contact->belong_id = $value->id;
                $contact->belong_name = $value->supplier_name;
                $contact->model = 'supplier';
                $contact->add_time = time();
                $contact->is_active = 1;
                $contact->save(false);

                $UserExtendInfo = new UserExtendInfo;
                $UserExtendInfo->filed_id = 31;
                $UserExtendInfo->filed_value = '采购经理';
                $UserExtendInfo->contact_id = $contact->id;
                $UserExtendInfo->save(false);
            }
        }
    }

    public function actionImportSupplier()
    {
        $reader = \PHPExcel_IOFactory::createReader('Excel2007'); // 读取 excel 文档

        $PHPExcel = $reader->load('supplier_list.xlsx'); // 文档名称

        $objWorksheet = $PHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E', 6 => 'F', 7 => 'G', 8 => 'H', 9 => 'I', 10 => 'J', 11 => 'K', 12 => 'L', 13 => 'M', 14 => 'N', 15 => 'O', 16 => 'P', 17 => 'Q', 18 => 'R', 19 => 'S', 20 => 'T', 21 => 'U', 22 => 'V', 23 => 'W', 24 => 'X', 25 => 'Y', 26 => 'Z');
        //echo $highestRow.$highestColumn;
        // 一次读取一列
        $res = array();
        $insert_title = [
            'supplier_name',
            'simple_name',
            'contact',
            'position',
            'tel',
            'guhua',
            'qq',
            'contact2',
            'tel2',
            'qq2',
            'address',
            'store_address',
            'is_daifa',
            'remark',
            'bank_name',
            'bank_open',
            'bank_code',
            'tax_code',
            'account_period',
            'alipay', //其他
            'add_user_id',
            'add_user_name',
            'depart_id',
            'depart_name',
            'add_time',
            'status_name',
            'status_done',
            'supplier_status',
        ];
        $insert_data = [];
        for ($row = 2; $row <= $highestRow; $row++) {
            $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
            for ($column = 1; $arr[$column] != 'U'; $column++) {
                if ($arr[$column] == 'N') {
                    $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                    $insert_data[$row - 2][$column] = empty($val) ? '1' : '0';
                } else {
                    $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                    $insert_data[$row - 2][$column] = $val;
                }
            }
            $insert_data[$row - 2][$column + 1] = '1';
            $insert_data[$row - 2][$column + 2] = 'admin';
            $insert_data[$row - 2][$column + 3] = '1';
            $insert_data[$row - 2][$column + 4] = '广州客维商务';
            $insert_data[$row - 2][$column + 5] = time();
            $insert_data[$row - 2][$column + 6] = "已复核";
            $insert_data[$row - 2][$column + 7] = '1';
            $insert_data[$row - 2][$column + 8] = '1';
        }
        $res = Yii::$app->db->createCommand()->batchInsert(Supplier::tableName(), $insert_title, $insert_data)->execute();
        // $write = new \PHPExcel_Writer_Excel5($excel);
        // $write->save('php://output');

    }


    public function actionImportBrand()
    {

        $reader = \PHPExcel_IOFactory::createReader('Excel2007'); // 读取 excel 文档

        $PHPExcel = $reader->load('brand_list.xlsx'); // 文档名称

        $objWorksheet = $PHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E', 6 => 'F', 7 => 'G', 8 => 'H', 9 => 'I', 10 => 'J', 11 => 'K', 12 => 'L', 13 => 'M', 14 => 'N', 15 => 'O', 16 => 'P', 17 => 'Q', 18 => 'R', 19 => 'S', 20 => 'T', 21 => 'U', 22 => 'V', 23 => 'W', 24 => 'X', 25 => 'Y', 26 => 'Z');
        // 一次读取一列
        $res = array();
        $insert_title = [
            'brand_name',
            'add_user_id',
            'add_user_name',
            'is_self_sell',
        ];

        $insert_title = [
            'brand_id',
            'supplier_id',
        ];
        $insert_data = [];
        for ($row = 2; $row <= $highestRow; $row++) {
            $brand = new Brand;
            $brand->brand_name = $objWorksheet->getCellByColumnAndRow('A', $row)->getValue();
            $brand->add_user_id = '1';
            $brand->add_user_name = 'admin';
            $brand->is_self_sell = '0';
            $brand->save(false);

            $supplier_data = [];
            if ($brand) {
                for ($colume = 3; $colume <= 13; $colume++) {
                    $supplier_name = $objWorksheet->getCellByColumnAndRow($colume, $row)->getValue();
                    $supplier_name = trim($supplier_name);
                    if (!empty($supplier_name)) {
                        $Supplier = Supplier::find()->where(['supplier_name' => $supplier_name])->one();
                        if ($Supplier) {
                            $brand_supplier = new BrandSupplier;
                            $brand_supplier->brand_id = $brand->id;
                            $brand_supplier->supplier_id = $Supplier->id;
                            $brand_supplier->save(false);
                        } else {
                            $Supplier = new Supplier;
                            $Supplier->supplier_name = $supplier_name;
                            $Supplier->add_user_id = 1;
                            $Supplier->add_user_name = 'admin';
                            $Supplier->depart_id = 1;
                            $Supplier->depart_name = '广州客维商务';
                            $Supplier->add_time = time();
                            $Supplier->status_name = '已复核';
                            $Supplier->status_done = 1;
                            $Supplier->supplier_status = 1;
                            $Supplier->save(false);

                            $brand_supplier = new BrandSupplier;
                            $brand_supplier->brand_id = $brand->id;
                            $brand_supplier->supplier_id = $Supplier->id;
                            $brand_supplier->save(false);
                        }
                    }
                }
            }
        }
    }

    public function actionImportOrder()
    {
        ini_set("max_execution_time",  "7200");

        $limit_start = 974;
        $platform = new Platform();
        // 获取上传的execl文件
        $platform_id = Yii::$app->request->post('id');
        if (isset($platform_id)) {
            $platform_data=$platform->find()->where(['id'=>$platform_id])->one();
            $plat_name=$platform_data['plat_name'];
        }else{
            $plat_name='';
        }
        $platform->load(Yii::$app->request->post());

        $ppt_file = UploadedFile::getInstance($platform, 'ppt_file');
        if ($ppt_file) {
            $filed_name = 'uploads/' . date('Ym');
            BaseFileHelper::createDirectory($filed_name);
            $new_img_name = $filed_name . '/' . Yii::$app->security->generateRandomString(20) . '.' . $ppt_file->extension;
            $ppt_file->saveAs($new_img_name);
            $platform->ppt_file = $new_img_name;
        }
        //获取文件后缀：xls、xlsx等
        $extension = strtolower(pathinfo($ppt_file, PATHINFO_EXTENSION));
        //加上这个判断的目的就是防止报错，但目前只支持Excel5
        if ($extension == 'xls') {
            $reader = \PHPExcel_IOFactory::createReader('Excel5');
        } else {
            $reader = \PHPExcel_IOFactory::createReader('excel2007');
        }

        $PHPExcel = $reader->load($new_img_name); // 文档名称

        $objWorksheet = $PHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E', 6 => 'F', 7 => 'G', 8 => 'H', 9 => 'I', 10 => 'J', 11 => 'K', 12 => 'L', 13 => 'M', 14 => 'N', 15 => 'O', 16 => 'P', 17 => 'Q', 18 => 'R', 19 => 'S', 20 => 'T', 21 => 'U', 22 => 'V', 23 => 'W', 24 => 'X', 25 => 'Y', 26 => 'Z');
        //  订单号	商品编号	下单数量	支付时间	联系人	联系电话	联系地址
        /* 3	东莞银行
    4	光大够精彩
    5	广发积分商城
    6	广发银行员工福利
    7	国航凤凰知音
    8	华夏E社区
    9	交通银行
    10	联通积分商城
    11	平安邮购商城
    12	上海银行
    13	壹钱包订单原表
    14	长沙银行
    15	招商银行
    16	中国移动
    17	中石化加油广东
    18	中油BP商城
    19	农行银行
    20	广发财富
    21	广发商城
     */
        switch ($platform_id) {
            case 3:
                //     $goods = new Goods;
                //     $goods->goods_name = trim($objWorksheet->getCellByColumnAndRow('0', $row)->getValue());
                //  $goods->goods_name = $goods->goods_name?trim($goods->goods_name):'';
                $data = $this->getData(0, 2, 0, 7, 8, 9);
                break;
            case 4:
                $address = array(27, 28);
                $data = $this->getData(1, 43, 46, 25, 29, $address);
                break;
            case 5:
                $data = $this->getData(1, 3, 8, 11, 13, 15);

                break;

            case 7:
                $data = $this->getData(4, 9, 13, 14, 15, 16);

                break;
            /*    case 8:
          没有条码
              $data=$this->getData(0,,,,,);
                break; */
            case 9:
                $data = $this->getData(0, 9, 12, 25, 26, 27);

                break;
            case 10:
                $address = array(13, 14, 15, 16, 17);
                $data = $this->getData(0, 5, 6, 11, 12, $address);
                break;
            case 11:
                $data = $this->getData(0, 4, 52, 16, 17, 23);
                break;
            /*   case 12:
              上海的使用代码表示产品
              $data=$this->getData(0,,,,,);
                break; */
            case 13:
                $data = $this->getData(1, 11, 13, 20, 22, 27);
                break;
            case 14:
                $data = $this->getData(0, 24, 20, 7, 8, 5);
                break;
            case 15:
                $data = $this->getData(0, 2, 7, 14, 16, 17);
                break;
            /* case 16:
               $data=$this->getData(0,3,,,,);
                break;
            case 17:
             /*  $data=$this->getData(,,,,,);
    中石化加油广东 没有商品编码
                break;    */
            /*  case 19:
            农行没有商品编码
              $data=$this->getData(0,,15,3,5,4);
              break; */
            case 18:
                $data = $this->getData(1, 14, 17, 8, 9, 10);
                break;
            /* case 20:
              没有条码
              $data=$this->getData(,,,,,);
                break; */
            case 21:
                $data = $this->getData(1, 12, 17, 30, 31, 33);
                break;
            case 22:
                $data = $this->getData(0, 7, 12, 51, 52, 50);
                break;
            default:
                $data = $this->getData(0, 1, 2, 3, 4, 5);
                break;
        }
        $tel = '';
        $orderid = '';
        $goodsdata='';
        $goods = new Goods();
        for ($row = 2; $row <= $highestRow; $row++) {

            if (!$objWorksheet->getCellByColumnAndRow(0, $row)->isInMergeRange() ||  $objWorksheet->getCellByColumnAndRow(0, $row)->isMergeRangeValueCell()) {
                // 判断号码相同为同一个单
                if ($tel == trim($objWorksheet->getCellByColumnAndRow($data['tel'], $row)->getValue())) {
                    $exportOrderGoods = new ExportOrderGoods();
                    $exportOrderGoods->order_id = $orderid;
                    $isbn= trim($objWorksheet->getCellByColumnAndRow($data['isbn'], $row)->getValue());
                    $exportOrderGoods->isbn =$isbn;
                    $goodsdata=$goods->find()->where(['isbn'=>$isbn])->one();
                    $exportOrderGoods->goods_id=$goodsdata['goods_id'];
                    $exportOrderGoods->goods_name=$goodsdata['goods_name'];
                    $exportOrderGoods->goods_sn=$goodsdata['goods_sn'];
                    $exportOrderGoods->market_price=$goodsdata['market_price'];
                    $exportOrderGoods->number = trim($objWorksheet->getCellByColumnAndRow($data['number'], $row)->getValue());
                    $exportOrderGoods->save(false);
                } else {
                    $exportOrder = new ExportOrder();
                    $exportOrder->order_sn = trim($objWorksheet->getCellByColumnAndRow($data['order_sn'], $row)->getValue());
                    $exportOrder->platform_id=$platform_id;

                    $exportOrder->platform_name=$plat_name;
                    $exportOrder->consignee = trim($objWorksheet->getCellByColumnAndRow($data['consinee'], $row)->getValue());
                    $tel = trim($objWorksheet->getCellByColumnAndRow($data['tel'], $row)->getValue());
                    $exportOrder->tel = $tel;
                    $exportOrder->address = trim($objWorksheet->getCellByColumnAndRow($data['address'], $row)->getValue());
                    $exportOrder->platform_id=$platform_id;
                    $exportOrder->platform_name=$plat_name;
                    $exportOrder->save(false);
                    $exportOrderGoods = new ExportOrderGoods();
                    $orderid = Yii::$app->db->getLastInsertID();
                    $exportOrderGoods->order_id = $orderid;
                    $exportOrderGoods->isbn =$isbn;
                    $goodsdata=$goods->find()->where(['isbn'=>$isbn])->one();
                    $exportOrderGoods->goods_id=$goodsdata['goods_id'];
                    $exportOrderGoods->goods_name=$goodsdata['goods_name'];
                    $exportOrderGoods->goods_sn=$goodsdata['goods_sn'];
                    $exportOrderGoods->market_price=$goodsdata['market_price'];
                    $exportOrderGoods->number = trim($objWorksheet->getCellByColumnAndRow($data['number'], $row)->getValue());
                    $exportOrderGoods->save(false);
                }
            } else {
                $exportOrderGoods = new ExportOrderGoods();
                $exportOrderGoods->order_id = $orderid;
                $exportOrderGoods->isbn =$isbn;
                $goodsdata=$goods->find()->where(['isbn'=>$isbn])->one();
                $exportOrderGoods->goods_id=$goodsdata['goods_id'];
                $exportOrderGoods->goods_name=$goodsdata['goods_name'];
                $exportOrderGoods->goods_sn=$goodsdata['goods_sn'];
                $exportOrderGoods->market_price=$goodsdata['market_price'];
                $exportOrderGoods->number = trim($objWorksheet->getCellByColumnAndRow($data['number'], $row)->getValue());
                $exportOrderGoods->save(false);
            }
        }
        exit;
    }
    // 获取位置
    public function getData($order_sn, $isbn, $number, $consinee, $tel, $address)
    {
        if ($number == 0) {
            $number = '';
        }
        return array(
            'order_sn' => $order_sn,
            'isbn' => $isbn,
            'number' => $number,
            'consinee' => $consinee,
            'tel' => $tel,
            'address' => $address
        );
    }
    /* // 匹配相同的手机号码来获取相同的订单
  public function getOrder($tel){

  } */

    public function actionImportGoods()
    {
        ini_set("max_execution_time",  "7200");

        $limit_start = 974;
        $goods = new Goods();
        // 获取上传的execl文件
        $goods->load(Yii::$app->request->post());

        $ppt_file = UploadedFile::getInstance($goods, 'ppt_file');
        if ($ppt_file) {
            $filed_name = 'uploads/' . date('Ym');
            BaseFileHelper::createDirectory($filed_name);
            $new_img_name = $filed_name . '/' . Yii::$app->security->generateRandomString(20) . '.' . $ppt_file->extension;
            $ppt_file->saveAs($new_img_name);
            $goods->ppt_file = $new_img_name;
        }
        //获取文件后缀：xls、xlsx等
        $extension = strtolower(pathinfo($ppt_file, PATHINFO_EXTENSION));
        //加上这个判断的目的就是防止报错，但目前只支持Excel5
        if ($extension == 'xls') {
            $reader = \PHPExcel_IOFactory::createReader('Excel5');
        } else {
            $reader = \PHPExcel_IOFactory::createReader('excel2007');
        }

        $PHPExcel = $reader->load($new_img_name); // 文档名称

        $objWorksheet = $PHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E', 6 => 'F', 7 => 'G', 8 => 'H', 9 => 'I', 10 => 'J', 11 => 'K', 12 => 'L', 13 => 'M', 14 => 'N', 15 => 'O', 16 => 'P', 17 => 'Q', 18 => 'R', 19 => 'S', 20 => 'T', 21 => 'U', 22 => 'V', 23 => 'W', 24 => 'X', 25 => 'Y', 26 => 'Z');

        for ($row = 2; $row <= $highestRow; $row++) {
            $goods = new Goods;
            $goods->goods_name = trim($objWorksheet->getCellByColumnAndRow('0', $row)->getValue());
            $goods->goods_name = $goods->goods_name ? trim($goods->goods_name) : '';
            $goods->goods_sn = $objWorksheet->getCellByColumnAndRow('1', $row)->getValue();
            $goods->goods_sn = $goods->goods_sn ? trim($goods->goods_sn) : '';
            $goods->goods_desc = '';
            $goods->default_supplier_id = '';
            $goods->default_supplier_name = '';
            $goods->type = 1;
            $goods->isbn = $objWorksheet->getCellByColumnAndRow('2', $row)->getValue();

            $cate_name = $objWorksheet->getCellByColumnAndRow("3", $row)->getValue();
            $cate = Category::find()->where(['cat_name' => $cate_name])->one();
            if (!$cate) {
                $cate = new Category;
                if ($cate_name == null) {
                    $cate_name = '';
                }
                $cate->cat_name = $cate_name;
                $cate->grade = 1;
                $cate->save(false);
            }
            $goods->cat_id = $cate->cat_id;
            $brand_name = $objWorksheet->getCellByColumnAndRow('4', $row)->getValue();
            $Brand = Brand::find()->where(['brand_name' => $brand_name])->one();
            if (!$Brand) {
                $Brand = new Brand;
                $Brand->brand_name = $brand_name;
                $Brand->add_user_id = 1;
                $Brand->add_user_name = 'admin';
                $Brand->save(false);
            }
            $goods->brand_id = $Brand->id;

            $goods->is_self_sell = 0;
            $goods->market_price = $objWorksheet->getCellByColumnAndRow('5', $row)->getValue();
            $goods->market_price = $goods->market_price ? $goods->market_price : 0;
            /*固定零售价*/
            $goods->fixed_price = $objWorksheet->getCellByColumnAndRow('6', $row)->getValue();
            $goods->fixed_price = $goods->fixed_price ? $goods->fixed_price : 0;
            //          $goods->goods_img = 'uploads/goods_img/'.$file_num.'.jpg';
            //          $goods->ppt_file = 'uploads/ppt_file/'.$file_num.'.pptx';
            $goods->goods_status = 1;
            $goods->is_clude_tax = 1;
            $goods->goods_brief = $objWorksheet->getCellByColumnAndRow('9', $row)->getValue();

            $goods->special_price = $objWorksheet->getCellByColumnAndRow('12', $row)->getValue();

            $goods->online_project = $objWorksheet->getCellByColumnAndRow('13', $row)->getValue();
            $goods->online_project_effective = $objWorksheet->getCellByColumnAndRow('14', $row)->getValue();
            //          有效期
            $expires = $objWorksheet->getCellByColumnAndRow('15', $row)->getValue();

            $goods->expire = preg_replace('/[^0-9]/', '', $expires);
            $goods->expire_unit = $this->getUnit($expires);
            $goods->duty_period = $objWorksheet->getCellByColumnAndRow('16', $row)->getValue();
            $goods->remarks = $objWorksheet->getCellByColumnAndRow('17', $row)->getValue();

            $goods->is_active = 1;
            $goods->save(false);
            $goods_id = Yii::$app->db->getLastInsertID();
            $supplier_name = trim($objWorksheet->getCellByColumnAndRow('8', $row)->getValue());
            $supplier = supplier::find()->where(['supplier_name' => $supplier_name])->one();
            if (!$supplier) {
                $supplier = new Supplier;
                $supplier->add_user_id = 1;
                $supplier->add_user_name = 'admin';
                $supplier->supplier_status = 1;
                $supplier->supplier_name = $supplier_name;
                $supplier->contact = $objWorksheet->getCellByColumnAndRow('16', $row)->getValue();
                $supplier->tel = $objWorksheet->getCellByColumnAndRow('17', $row)->getValue();
                $supplier->save(false);
                $supplier_id = Yii::$app->db->getLastInsertID();
            } else {
                $supplier_id = $supplier->id;
            }
            /*采购价*/
            $supplier_price = $objWorksheet->getCellByColumnAndRow('7', $row)->getValue();
            if (empty($supplier_price)) {
                $supplier_price = 0;
            }

            Yii::$app->db->createCommand("insert into goods_supplier   (goods_id,supplier_id,supplier_price) values (" . $goods_id . "," . $supplier_id . "," . $supplier_price . ")")->execute();
        }
        $links = [['link_name' => '返回列表', 'link_url' => URL::to(['/goods'])]];
        Message::show_message('添加成功', $links);
    }

    public function actionImportStock()
    {
        ini_set("max_execution_time",  "7200");

        $limit_start = 974;
        $stock = new Stock();
        $goods = new Goods();
        $store = new Store();

        // 获取上传的execl文件
        $goods->load(Yii::$app->request->post());

        $ppt_file = UploadedFile::getInstance($goods, 'ppt_file');
        if ($ppt_file) {
            $filed_name = 'uploads/' . date('Ym');
            BaseFileHelper::createDirectory($filed_name);
            $new_img_name = $filed_name . '/' . Yii::$app->security->generateRandomString(20) . '.' . $ppt_file->extension;
            $ppt_file->saveAs($new_img_name);
            $goods->ppt_file = $new_img_name;
        }
        //        $reader = \PHPExcel_IOFactory::createReader('Excel2007'); // 读取 excel 文档
        $extension = strtolower(pathinfo($ppt_file, PATHINFO_EXTENSION));
        if ($extension == 'xls') {
            $reader = \PHPExcel_IOFactory::createReader('Excel5');
        } else {
            $reader = \PHPExcel_IOFactory::createReader('excel2007');
        }

        $PHPExcel = $reader->load($new_img_name); // 文档名称

        $objWorksheet = $PHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $arr = array(1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E', 6 => 'F', 7 => 'G', 8 => 'H', 9 => 'I', 10 => 'J', 11 => 'K', 12 => 'L', 13 => 'M', 14 => 'N', 15 => 'O', 16 => 'P', 17 => 'Q', 18 => 'R', 19 => 'S', 20 => 'T', 21 => 'U', 22 => 'V', 23 => 'W', 24 => 'X', 25 => 'Y', 26 => 'Z');

        for ($row = 2; $row <= $highestRow; $row++) {

            //            $stock->goods_id=$objWorksheet->getCellByColumnAndRow('4', $row)->getValue();
            //            $stock->store_id=$objWorksheet->getCellByColumnAndRow('5', $row)->getValue();
            //            $stock->number = $objWorksheet->getCellByColumnAndRow('3', $row)->getValue();

            $isbn = trim($objWorksheet->getCellByColumnAndRow('1', $row)->getValue());
            $store_name = trim($objWorksheet->getCellByColumnAndRow('2', $row)->getValue());
            $number = trim($objWorksheet->getCellByColumnAndRow('3', $row)->getValue());
            $new_number = trim($objWorksheet->getCellByColumnAndRow('4', $row)->getValue());
            $store = Yii::$app->db->createCommand("SELECT * FROM store where store_name='" . $store_name . "'")->queryOne();

            /*商品存在库存，但是库存和盘点的不相等*/
            if (isset($number) && $number != '' && $new_number != $number) {
                $transaction = Yii::$app->db->beginTransaction();
                $stock = Yii::$app->db->createCommand("SELECT * FROM stock where store_id=" . $store['id'] . " and isbn='" . $isbn . "'")->queryOne();
                $stor_code = serialize($stock['stor_code']);
                Yii::$app->db->createCommand("UPDATE stock SET number=" . $new_number . ", stor_code='" . $stor_code . "' WHERE isbn='" . $isbn . "' and store_id=" . $store['id'])->execute();
            }
            /*新增商品*/ elseif (empty($number)) {
                /*不存在仓库*/
                if (empty($store)) {
                    Yii::$app->db->createCommand("insert into store (store_name) values ('" . $store_name . "')")->execute();
                    $store['id'] = Yii::$app->db->getLastInsertID();
                }
                $pro = Yii::$app->db->createCommand("SELECT goods_id FROM goods where isbn='" . $isbn . "'")->queryOne();

                if ($pro != '') {
                    $manage_user = Yii::$app->session['manage_user'];
                    Yii::$app->db->createCommand("insert into stock (isbn,number,goods_id,store_id,add_time,add_user_id,add_user_name) values ('" . $isbn . "'," . $new_number . "," . $pro['goods_id'] . "," . $store['id'] . "," . time() . "," . $manage_user['id'] . ",'" . $manage_user['admin_name'] . "')")->execute();
                }
            }



            //            $stock->save(false);

        }
        $links = [['link_name' => '返回列表', 'link_url' => URL::to(['/storage'])]];
        Message::show_message('添加成功', $links);
    }



    public function actionDueActionToArr()
    {
        //得到目录下的文件
        //echo getcwd() . "<br>";
        chdir("../controllers");
        $d = dir(getcwd());

        $return_arr = []; //初始化要返回的数组
        while (($file = $d->read()) !== false) {
            //去除结尾的Controller.php
            if (strpos($file, "Controller.php")) {
                $file_name = substr($file, 0, strpos($file, 'Controller.php'));
                $file_name = lcfirst($file_name); //首字母小写
                $new_file_name = '';
                for ($i = 0; $i < strlen($file_name); $i++) {
                    if (ctype_upper($file_name[$i])) {
                        $new_file_name .= '-' . strtolower($file_name[$i]);
                    } else {
                        $new_file_name .= $file_name[$i];
                    }
                }
            } else {
                continue;
            }

            $return_arr[$new_file_name] = [];

            //读取指定文件
            $file_content = file_get_contents($file);

            //切分函数 把文件字符串按照函数分解成数组

            $file_content_arr = explode('public function action', $file_content);
            $return_arr[$new_file_name] = "'index','create',";
            foreach ($file_content_arr as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                $maybe_1 = strpos($value, ' (') ? strpos($value, ' (') : 9999;
                $maybe_2 = strpos($value, '(') ? strpos($value, '(') : 9999;
                if ($maybe_1 || $maybe_2) {
                    $start = min($maybe_1, $maybe_2);
                    $action_name = substr($value, 0, $start);
                    $action_name = lcfirst($action_name); //首字母小写
                    $new_action_name = '';
                    for ($i = 0; $i < strlen($action_name); $i++) {
                        if (ctype_upper($action_name[$i])) {
                            $new_action_name .= '-' . strtolower($action_name[$i]);
                        } else {
                            $new_action_name .= $action_name[$i];
                        }
                    }
                    //$return_arr[$new_file_name][] = $new_action_name;
                    // if (!isset($return_arr[$new_file_name]['string'])) {
                    //   $return_arr[$new_file_name]['string'] = '';
                    // }
                    $return_arr[$new_file_name] .= "'" . $new_action_name . "',";
                }
            }
        }
        echo '<pre>';
        print_r($return_arr);
        echo '</pre>';
        $d->close();
    }
    public function getUnit($can)
    {
        $s = '';
        switch ($can) {
            case 7:
                $s = '秒';
                break;
            case 8:
                $s = '分';
                break;
            case 9:
                $s = '时';
                break;
            case 10:
                $s = '天';
                break;
            case 11:
                $s = '周';
                break;
            case 12:
                $s = '月';
                break;
            case 13:
                $s = '年';
                break;
            case '秒':
                $s = 7;
                break;
            case '分':
                $s = 8;
                break;
            case '时':
                $s = 9;
                break;
            case '天':
                $s = 10;
                break;
            case '周':
                $s = 11;
                break;
            case 12:
                $s = '月';
                break;
            case '年':
                $s = 13;
                break;
        }
        return $s;
    }
}
