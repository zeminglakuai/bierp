<?php

namespace app\controllers;

use app\common\models\Platform;
use Yii;
use yii\helpers\Arrayhelper;

use yii\helpers\BaseFileHelper;
use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\PurchaseGoods;
use app\common\models\Purchase;
use app\common\models\PurchaseReturn;
use app\common\models\PurchaseReturnGoods;
use app\common\models\Goods;
use app\common\models\Project;
use app\common\models\Supplier;
use app\common\models\Store;
use app\common\models\Stock;
use app\common\models\Payment;
use app\common\models\AcceptInvoice;
use app\common\models\ImportOrder;
use app\common\models\ImportOrderGoods;
use Mpdf\Mpdf;
use Da\QrCode\QrCode;
use yii\helpers\Url;
use app\includes\Message;
use app\controllers\BaseController;
use app\includes\Common_fun;

class PurchaseController extends BaseController
{
    public $page_title = '采购单';
    public $title_model = 'app\common\models\Purchase';
    public $detail_model = 'app\common\models\PurchaseGoods';
    public $scope = true;
    public $search_allowed = '';

    public $status_label = 'purchase_status';

    public function beforeAction($action)
    {
      parent::beforeAction($action);

      //检查当前单据用户是不是有操作权限
      $need_privi_arr = ['delete','edit','admit','update','insert-goods','delete-goods','update-goods-label'];
      $admit_allow_arr = ['edit','view','create-purchase-return','un-admit','create-import'];

      $scope_model = 'app\common\models\Purchase';
      $status_label = 'purchase_status';

      if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
        return true;
      }else{
        return false;
      }
    }
    public function actionCreate1(){
      $order_id = Yii::$app->request->get('order_id');
      if (isset($order_id)) {
        return $this->render('create1',['order_id'=>$order_id]);
      }else {
        message::show_message('订单不存在',[],'error');
      }

    }
    //删除
    public function actionDelete($id){
      $custom_order = Purchase::findone($id);
      $custom_order->delete();
      message::result_json(1,'删除成功');
    }

    //添加
    public function actionInsert(){
        $Purchase = new Purchase();
        $Purchase->load(Yii::$app->request->post());
    if ($Purchase->purchase_type==6) {
          $Purchase->order_sn = Common_fun::create_sn('app\common\models\Purchase',5);
          $sql="insert into purchase(order_sn,consignee,consignee_tel,address,fid,purchase_type) value ('". $Purchase->order_sn."','". $Purchase->consignee."','". $Purchase->consignee_tel."','". $Purchase->address."',". $Purchase->fid.",6) ";
         Yii::$app->db->createCommand($sql)->execute();
         
          Message::result_json(1,'添加成功');
    }
        if (!$Purchase->supplier_id) {
          Message::result_json(2,'供货商不能为空');
        }
        if (!$Purchase->pay_type) {
            Message::result_json(2,'付款方式不能为空');
        }

        $supplier = Supplier::findone($Purchase->supplier_id);
        if (!$supplier) {
          Message::result_json(2,'供货商不存在，请重新选择');
        }else{
          $Purchase->supplier_name = $supplier->supplier_name;
        }
        // 添加项目
        if (empty($Purchase->project_id)&&empty($Purchase->order_name)) {
            Message::result_json(2,'项目不能为空');
        }elseif(empty($Purchase->project_id)) {
          Yii::$app->db->createCommand("insert into project(project_name,add_user_id,add_user_name,add_time) value('".$Purchase->order_name."',".Yii::$app->session['manage_user']['id'].",'".Yii::$app->session['manage_user']['admin_name']."',".time().") ")->execute();
          $data=Yii::$app->db->createCommand("SELECT project_id FROM project order by project_id desc limit 1")->queryOne();
          
          $Purchase->project_id=$data['project_id'];
        }
        

        $store = Store::findone($Purchase->store_id);
        if (!$store) {
          Message::result_json(2,'仓库不存在，请重新选择');
        }else{
          $Purchase->store_name = $store->store_name;
        }
        /*$platform=Platform::findOne($Purchase->platform_id);
        if (empty($platform)) {
            Message::result_json(2,'平台不存在，请重新选择');
        }else{
            $Purchase->platform_name = $platform->plat_name;
        }*/
        $Purchase->order_sn = Common_fun::create_sn('app\common\models\Purchase',5);

        $Purchase->save(false);
        Message::result_json(1,'添加成功');
    }

    public function actionView($id){
      
        $Purchase = Purchase::findone($id);
        $project=Project::findOne($Purchase->project_id);
        $supplier=Supplier::findOne($Purchase->supplier_id);
        switch ($Purchase['pay_type']){
            case 1:
                $Purchase['pay_type']='多次付款';
                break;
            case 2:
                $Purchase['pay_type']='一次性付款';
                break;
            case 3:
                $Purchase['pay_type']='月结商户';
            default:
                break;
        }
        
        if ($Purchase['purchase_type']==5) {
            $purchase_goods['k']=Yii::$app->db->createCommand("select * from purchase where fid=".$id)->queryAll();
            
            foreach ($purchase_goods['k'] as $k => $val) {
                $purchase_goods['k'][$k]['goods']=Yii::$app->db->createCommand("select pg.id,pg.order_id,pg.platform_id,pg.platform_beizhu,g.goods_name,g.goods_sn,g.isbn,g.market_price,pg.purchase_price,pg.number,g.goods_id from purchase_goods as pg left join goods as g on g.goods_id=pg.goods_id where pg.order_id=".$val['id'])->queryAll();
            }
         
            return $this->render('view3', ['purchase'=>$Purchase,'id'=>$id,'supplier'=>$supplier,'purchase_good'=>$purchase_goods,'project'=>$project]);
        }else {
          if (isset($Purchase->store_id)) {
            $store=Yii::$app->db->createCommand("select * from store where id=".$Purchase->store_id)->queryOne();
            $Purchase['consignee']=$store['contact'];
            $Purchase['consignee_tel']=$store['tel'];
            $Purchase['address']=$store['address'];
          }
           
            $purchase_goods=Yii::$app->db->createCommand("select pg.id,pg.order_id,pg.platform_id,pg.platform_beizhu,g.goods_name,g.goods_sn,g.isbn,g.market_price,pg.purchase_price,pg.number,g.goods_id from purchase_goods as pg left join goods as g on g.goods_id=pg.goods_id where pg.order_id=".$id)->queryAll();
            if (isset($purchase_goods)){
                foreach ($purchase_goods as $k=>$v){
                    $sql="select gp.startdate,gp.enddate,gp.daifa,p.plat_name,p.id from goods_platform as gp left join platform as p on p.id=gp.platform_id  where gp.goods_id=".$v['goods_id'];
                    $purchase_goods[$k]['platform']=Yii::$app->db->createCommand($sql)->queryAll();
                    
                }
            }
            return $this->render('view', ['purchase'=>$Purchase,'id'=>$id,'supplier'=>$supplier,'purchase_good'=>$purchase_goods,'project'=>$project]);
        }
    }
    public function actionChange(){
        $info = Yii::$app->request->get();
        Yii::$app->db->createCommand("update purchase_goods set platform_id=".$info['platform_id']."  where id=".$info['purchase_goods_id'])->execute();
    }
    public function actionEdit($id){
      $this->layout = 'empty';
      $Purchase = Purchase::findone($id);
        $pay_type=array(
            array(
                'id'=>1,
                'val'=>'多次付款',
            ),
            array(
                'id'=>2,
                'val'=>'一次付款',
            ),
            array(
                'id'=>3,
                'val'=>'月结商户',
            )
        );
        $purchase_type=array(
            array(
                'id'=>1,
                'val'=>'项目采购',
            ),
            array(
                'id'=>2,
                'val'=>'样品采购',
            ),
            array(
                'id'=>3,
                'val'=>'非经营性采购',
            ),
            array(
                'id'=>4,
                'val'=>'配料物流采购',
            ),
            array(
                'id'=>5,
                'val'=>'代发',
            )
        );

      return $this->render('edit', ['purchase'=>$Purchase,'id'=>$id,'pay_type'=>$pay_type,'purchase_type'=>$purchase_type]);
    }

    public function actionUpdate($id){
      $Purchase = Purchase::findone($id);
      if ($Purchase) {
        $Purchase->load(Yii::$app->request->post());
        if ($Purchase->store_id > 0) {
          $store = Store::findone($Purchase->store_id);
          $Purchase->store_name = $store->store_name;
        }

        $Purchase->save(false);
        Message::result_json(1,'编辑成功');
      }else{
        Message::result_json(2,'编辑失败');
      }

    }

    public function actionAdmit($id,$process_status){
      //修改订单状态
      $Purchase = Purchase::findone($id);

      //检查供货商是不是存在
      if ($Purchase->supplier_id > 0) {
      }else{
        Message::result_json(2,'请登记供货商!');
      }

      //检查商品进货价 和 数量 是不是齐备
      $order_goods = PurchaseGoods::find()->where(['order_id'=>$id])->all();

      if (empty($order_goods)) {
        if ($Purchase['purchase_type']!=5) {
          Message::result_json(2,'商品不存在，不能复核！');
        }
        
      }
      foreach ($order_goods as $key => $value) {
        if ($value->purchase_price <= 0 || $value->number <= 0) {
          Message::result_json(2,$value->goods_name.'信息不完整！');
          break;
        }
      }

    if ($process_status==2) {
    $admit_result = $this->base_admit($Purchase,'purchase_status',$process_status);
      if ($admit_result['error'] > 2) {
        Message::result_json(2,$admit_result['message']);
      }else {
        
        switch ($Purchase['pay_type']){
          case 1:
              $Purchase['pay_type']='多次付款';
              break;
          case 2:
              $Purchase['pay_type']='一次性付款';
              break;
          case 3:
              $Purchase['pay_type']='月结商户';
          default:
              break;
      }
      $purchase_goods=Yii::$app->db->createCommand("select pg.id,pg.order_id,pg.platform_id,pg.platform_beizhu,g.goods_name,g.goods_sn,g.isbn,g.market_price,pg.purchase_price,pg.number,g.goods_id from purchase_goods as pg left join goods as g on g.goods_id=pg.goods_id where order_id=".$id)->queryAll();
      if (isset($purchase_goods)){
          foreach ($purchase_goods as $k=>$v){
              $sql="select gp.*,p.plat_name,p.id from goods_platform as gp left join platform as p on p.id=gp.platform_id  where gp.goods_id=".$v['goods_id'];
              $purchase_goods[$k]['platform']=Yii::$app->db->createCommand($sql)->queryAll();
          }
      }
      $supplier=Yii::$app->db->createCommand("select * from supplier where id=".$Purchase['supplier_id'])->queryOne();
      $mpdf=new Mpdf(['zh-CN','A4','','',23,23,40,'setAutoTopMargin' => 'stretch']);
          //设置中文字符集
          $mpdf->useAdobeCJK = true;
          $mpdf->autoScriptToLang = true;
          $mpdf->autoLangToFont = true;          
          $x='';$y='';
          $pype='采购单';
          switch ($Purchase['purchase_type']) {
            case 1:
              $purchase_type='项目采购';
              break;
            case 2:
              $purchase_type='样品采购';
              break;
            case 3:
              $purchase_type='非经营性采购';
              break;
            case 4:
              $purchase_type='配件物流采购';
              break;
            default:
            $purchase_type='代发';
            $pype='代发单';
              break;
          }
           switch ($Purchase['pay_type']){
          case 1:
              $Purchase['pay_type']='多次付款';
              break;
          case 2:
              $Purchase['pay_type']='一次性付款';
              break;
          case 3:
              $Purchase['pay_type']='月结商户';
          default:
              break;
      }
          $strContent1='<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"> </head><body><table style="font-size:14px;color:#333333;border-width:1px;border-color:#999999;border-collapse:collapse;line-height: 200%;" width="100%"><tr><td width="40%"><img src="/img/kewei.png" width="119px" height="54px"/></td><td style="font-size:30px;line-height: 200%;">'.$pype.'</td></tr><tr><td>订单日期:  '.date("Y-m-d H:i",$Purchase['add_time']).'</td><td>单号:  '.$Purchase['order_sn'].'</td></tr><tr><td colspan="5" style="font-size:20px;line-height: 200%;font-weight: bold;">下单公司信息:  </td></tr><tr><td>下单公司:  广州市客维商务服务有限公司</td><td>部门:  '.$Purchase['depart_name'].'</td><td>下单人:  '.$Purchase['add_user_name'].'</td></tr><tr><td colspan="5" >联系电话:  '.$supplier['tel'].'</td></tr><tr><td colspan="5" style="font-size:20px;line-height: 200%;font-weight: bold;">供应商信息:  </td></tr><tr><td>供货商名称:  '.$supplier['supplier_name'].'</td><td>联系人:  '.$Purchase['supplier_contact'].'</td><td>联系电话:  '.$Purchase['supplier_tel'].'</td></tr>';
          $strContent2='<tr><td colspan="5" style="font-size:20px;line-height: 200%;font-weight: bold;">收货信息： </td></tr><tr><td>收货仓库:  '.$Purchase['store_name'].'</td><td>收货人:  '.$Purchase['consignee'].'</td><td>联系电话:  '.$Purchase['consignee_tel'].'</td></tr><tr><td colspan="5" >收货地址:  '.$Purchase['address'].'</td></tr>';
          $strContent3=' <tr><td colspan="5" style="font-size:20px;line-height: 200%;font-weight: bold;">收款信息： </td></tr><tr><td>收款银行:  '.$supplier['bank_name'].'</td><td>收款账号:  '.$supplier['bank_code'].'</td><td>收款人:  '.$supplier['bank_payee'].'</td></tr><tr><td>结算方式:  '.$Purchase['pay_type'].'</td></tr><tr><td >备注:  '.$Purchase['remark'].'</td></tr></table><div style="width: 100%;height: 60px;margin-top: 5%;margin-left: 40%;"><span style="font-size: 30px;">商品列表</span></div>';
          // 采购为1代发为2
          if ($Purchase['purchase_type']==5) {
            // 头部信息
            $strContent=$strContent1.$strContent3;
            //下面为商品信息
            $strContent.='<table style="font-size:10px;color:#333333;border-width:1px;border-color:#999999;" width="100%"><tr style="border-collapse:collapse;border:1px solid black;"><td width="5%">序号</td><td width="20%">商品名称</td><td width="12%">商品型号</td><td width="10%">条形码</td><td  width="5%">数量</td><td  width="5%">单价</td><td  width="5%">小计</td><td  width="8%">收货人</td><td  width="10%">联系电话</td><td  width="25%">联系地址</td></tr>';

            $purchase_daifa=Yii::$app->db->createCommand("select * from purchase where fid=$id")->queryAll();
            // $strContent.='<tr style="border-collapse:collapse;border:1px solid black;"><td>'.($key+1).'</td><td>'.$value['goods_name'].'</td><td>'.$value['goods_sn'].'</td><td>'.$value['isbn'].'</td><td>'.$value['number'].'</td><td>'.$value['purchase_price'].'</td><td>'.$value['number']*$value['purchase_price'].'</td><td>'.$value['consignee'].'</td><td>'.$value['consignee_tel'].'</td><td>'.$value['address'].'</td></tr>';
            
              foreach ($purchase_daifa as $k => $val) {
                $purchase_goods=Yii::$app->db->createCommand("select pg.id,pg.order_id,pg.platform_id,pg.platform_beizhu,g.goods_name,g.goods_sn,g.isbn,g.market_price,pg.purchase_price,pg.number,g.goods_id from purchase_goods as pg left join goods as g on g.goods_id=pg.goods_id where order_id=".$val['id'])->queryAll();

                // 获取所有为代发单下面的子订单
                foreach ($purchase_goods as $key => $value) {
                  $strContent.='<tr style="border-collapse:collapse;border:1px solid black;"><td>'.($key+1).'</td><td>'.$value['goods_name'].'</td><td>'.$value['goods_sn'].'</td><td>'.$value['isbn'].'</td><td>'.$value['number'].'</td><td>'.$value['purchase_price'].'</td><td>'.$value['number']*$value['purchase_price'].'</td>';
                  if ($key==0) {
                    $strContent.='<td>'.$val['consignee'].'</td><td >'.$val['consignee_tel'].'</td><td >'.$val['address'].'</td>';
                  }
                  $strContent.='</tr>';
                  $x+=$value['number'];
                  $y+=$value['number']*$value['purchase_price'];
                }
                $strContent.='<tr ><td colspan="5">总数量：'.$x.'</td><td></td><td colspan="5">合计：'.$y.'</td><td></td></tr>'; 
              }
            $strContent.='</table></body></html>';
          }else {
            $strContent=$strContent1.$strContent2.$strContent3;
            $strContent.='<table style="font-size:14px;color:#333333;border-width:1px;border-color:#999999;" width="100%"><tr style="border-collapse:collapse;border:1px solid black;"><td width="5%">序号</td><td width="25%">商品名称</td><td width="20%">商品型号</td><td width="20%">条形码</td><td  width="5%">数量</td><td  width="9%">单价</td><td  width="8%">小计</td><td  width="8%">装箱数</td></tr>';
            foreach ($purchase_goods as $key => $value) {
              $strContent.='<tr style="border-collapse:collapse;border:1px solid black;"><td>'.($key+1).'</td><td>'.$value['goods_name'].'</td><td>'.$value['goods_sn'].'</td><td>'.$value['isbn'].'</td><td>'.$value['number'].'</td><td>'.$value['purchase_price'].'</td><td>'.$value['number']*$value['purchase_price'].'</td><td>'.$value['pack_num'].'</td></tr>';
              $x+=$value['number'];
              $y+=$value['number']*$value['purchase_price'];
            }
            $strContent.='<tr ><td colspan="3">总数量：'.$x.'</td><td></td><td colspan="3">合计：'.$y.'</td><td></td></tr>';  
            $strContent.='</table></body></html>';
          }
         
          $mpdf->WriteHTML($strContent);
         $mpdf->SetFooter(' |广州市客维商务服务有限公司|联系方式：+86 4006088183   |','D');
          //设置水印
        $mpdf->SetWatermarkImage('/img/shuiying.png','-0.8','A','F');
        $mpdf->showWatermarkImage = true;
        $date_path='uploads/'.$Purchase['order_sn'].'.pdf';
       
        
          $mpdf->Output($date_path,'F');
           // 生成二维码地址
          $qrCode = (new QrCode('https://'.$_SERVER['HTTP_HOST'].'/'.$date_path))
          ->setSize(250)
          ->setMargin(5)
          ->useForegroundColor(0,0,0);
          
      // now we can display the qrcode in many ways
      // saving the result to a file:
      $img='/uploads/'.$Purchase['order_sn'].'.png';
      $qrCode->writeFile('uploads/'.$Purchase['order_sn'].'.png'); // writer defaults to PNG when none is specified
      Yii::$app->db->createCommand("update purchase set qcode='".$img."'  where id=".$id)->execute();
      }
      }else {
        $admit_result = $this->base_admit($Purchase,'purchase_status',$process_status);
      }
          if ($admit_result['error'] > 2) {
            Message::result_json(2,$admit_result['message']);
          }
          Message::result_json(1,'复核成功');
          }

    public function actionUnAdmit($id){
      //检查是不是已经复合结束
      $Purchase = Purchase::findone($id);
      if ($this->if_approval_done($Purchase,$this->status_label)) {
        //更新单据到复核之前
        $process_data_arr = $this->get_approval_data($this->id);
        $Purchase->purchase_status = $Purchase->purchase_status - 1;
        $Purchase->status_name = $process_data_arr[$Purchase->purchase_status]['processed_name'];
        $Purchase->status_done = 0;
        $Purchase->save(false);

        //记录复核记录
        $this->add_process_log($Purchase->id,$Purchase->purchase_status,$process_data_arr[$Purchase->purchase_status]['processed_name']);

        Message::result_json(1,'反复核成功');
      }else{
        Message::result_json(2,'未走完复核流程，不允许反复核！');
      }
    }


    //生成入库单
    public function actionCreateImport($id){
      $Purchase = Purchase::findone($id);
      if (!$this->if_approval_done($Purchase,$this->status_label)) {
        Message::result_json(2,'未走完复核流程，不允许此操作！');
      }

      if ($Purchase->is_create_import > 0) {
        Message::result_json(2,'单据已经生成入库单!');
      }

      $purchase_goods = PurchaseGoods::find()->where(['order_id'=>$id])->all();

      $transaction = Yii::$app->db->beginTransaction();

      //新建出库单
      $ImportOrder = new ImportOrder();
      $ImportOrder->supplier_id = $Purchase->supplier_id;
      $ImportOrder->supplier_name = $Purchase->supplier_name;
      $ImportOrder->store_id = $Purchase->store_id;
      $ImportOrder->store_name = $Purchase->store_name;

      $ImportOrder->purchase_id = $Purchase->id;
      $ImportOrder->purchase_sn = $Purchase->order_sn;

      $ImportOrder->order_sn = Common_fun::create_sn('app\common\models\ImportOrder',5);
      $ImportOrder->save(false);

      foreach ($purchase_goods as $key => $value) {
        $purchase_goods_arr[$value->goods_id]['order_id'] = $ImportOrder->id;
        $purchase_goods_arr[$value->goods_id]['goods_id'] = $value->goods_id;
        $purchase_goods_arr[$value->goods_id]['goods_name'] = $value->goods_name;
        $purchase_goods_arr[$value->goods_id]['goods_sn'] = $value->goods_sn;
        $purchase_goods_arr[$value->goods_id]['isbn'] = $value->isbn;
        $purchase_goods_arr[$value->goods_id]['market_price'] = $value->market_price;
        $purchase_goods_arr[$value->goods_id]['purchase_price'] = $value->purchase_price;
        $purchase_goods_arr[$value->goods_id]['number'] = $value->number;
        $purchase_goods_arr[$value->goods_id]['real_number'] = $value->number;
        $purchase_goods_arr[$value->goods_id]['store_code'] = '';
        //得到最近使用的库位
        $stock = Stock::find()->where(['goods_id'=>$value->goods_id,'store_id'=>$Purchase->store_id])->orderby('id DESC')->one();
        if ($stock) {
          $purchase_goods_arr[$value->goods_id]['store_code'] = $stock->stor_code;
        }
      }

      if ($purchase_goods_arr) {
          $goods_title =['order_id','goods_id','goods_name','goods_sn','isbn','market_price','purchase_price','number','real_number','store_code'];//数据键
          $res= Yii::$app->db->createCommand()->batchInsert(ImportOrderGoods::tableName(), $goods_title, $purchase_goods_arr)->execute();

          $Purchase->is_create_import = 1;
          $Purchase->save(false);

          //提交事务
          $transaction->commit();
          message::result_json(1,'生成入库单成功');
      }else{
        $transaction->rollback();
        message::result_json(2,'没有商品记录');
      }
    }

    public function actionInsertGoods(){
      $goods_id = Yii::$app->request->get('goods_id',0);
        $order_type = Yii::$app->request->get('order_type',0);

        $order_id = Yii::$app->request->get('order_id',0);
      $search_data = Yii::$app->request->get('search_data',0);

      if (!$order_id) {
        message::result_json(2,'数据错误');
      }else{
        $order = Purchase::findone($order_id);
      }
      
      $add_goods_error = [];
      if ($goods_id) {
          if (is_array($goods_id)) {
            foreach ($goods_id as $key => $value) {
              $goods = Goods::findone($value);
              if ($goods) {
                //插入数据
                $PurchaseGoods = new PurchaseGoods();
                $add_goods_result = $PurchaseGoods->AddGoods($order_id,$goods,$order_type,$order->supplier_id);
                
                if (!$add_goods_result) {
                    $add_goods_error[] = $PurchaseGoods->add_goods_error;
                }
              }
            }
            if (count($add_goods_error) > 0) {
              message::result_json(1,$add_goods_error);
            }else{
              message::result_json(1,'添加成功');
            }

          }else{
            $goods = Goods::findone($goods_id);
            //插入数据
            $PurchaseGoods = new PurchaseGoods();
            $add_goods_result = $PurchaseGoods->AddGoods($order_id,$goods,$order_type,$order->supplier_id);

            if (!$add_goods_result) {
                $add_goods_error = $PurchaseGoods->add_goods_error;
            }

            if (count($add_goods_error) > 0) {
              message::result_json(2,$add_goods_error);
            }else{
              message::result_json(1,'添加成功');
            }
          }
      }

      if ($search_data) {

      }

      message::result_json(2,'数据错误');
    }
    public function actionDeleteGoods($id){
        $data_id = Yii::$app->request->get('data_id');
        $PurchaseGoods = PurchaseGoods::find()->where(['id'=>$data_id])->one();
        $PurchaseGoods->delete();
        message::result_json(1,'删除成功');
    }

    public function actionUpdateGoodsLabel(){
        $data_type  = trim(Yii::$app->request->get('data_type'));
        $value  = trim(Yii::$app->request->get('value'));
        $id  = Yii::$app->request->get('id');
        $data_id  = Yii::$app->request->get('data_id');

        $PurchaseGoods = PurchaseGoods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        if($PurchaseGoods){
          $PurchaseGoods->$data_type = $value;
          $PurchaseGoods->save(false);
          message::result_json(1,'chengg',$value);
        }else{
          message::result_json(2,'没有此记录');
        }
    }

    //生成 退货单
    public function actionCreatePurchaseReturn($id){
      $goods_id_arr = Yii::$app->request->get('goods_id_arr');

      $Purchase = Purchase::findone($id);
      if (!$Purchase) {
        message::result_json(2,'没有单据记录');
      }else{
        if ($Purchase->purchase_status <> 1) {
          message::result_json(2,'未复核单据不能生成退货单');
        }
      }

      $PurchaseGoods = PurchaseGoods::find()->where(['order_id'=>$id])->all();

      $transaction = Yii::$app->db->beginTransaction();
      //新建退货单
      $purchase_return = new PurchaseReturn();
      $purchase_return->purchase_id = $Purchase->id;
      $purchase_return->purchase_sn = $Purchase->order_sn;
      $purchase_return->supplier_id = $Purchase->supplier_id;
      $purchase_return->supplier_name = $Purchase->supplier_name;
      $purchase_return->order_sn = Common_fun::create_sn('app\common\models\PurchaseReturn',5);
      $purchase_return->save(false);

      $purchase_goods_arr = [];
      foreach ($PurchaseGoods as $key => $value) {
        if (in_array($value->goods_id, $goods_id_arr)) {
          $purchase_goods_arr[$value->goods_id]['order_id'] = $purchase_return->id;
          $purchase_goods_arr[$value->goods_id]['goods_id'] = $value->goods_id;
          $purchase_goods_arr[$value->goods_id]['goods_name'] = $value->goods_name;
          $purchase_goods_arr[$value->goods_id]['goods_sn'] = $value->goods_sn;
          $purchase_goods_arr[$value->goods_id]['isbn'] = $value->isbn;
          $purchase_goods_arr[$value->goods_id]['number'] = $value->number;
          $purchase_goods_arr[$value->goods_id]['return_number'] = $value->number;
          $purchase_goods_arr[$value->goods_id]['market_price'] = $value->market_price;
          $purchase_goods_arr[$value->goods_id]['purchase_price'] = $value->purchase_price;
        }
      }

      if ($purchase_goods_arr) {
        $goods_title =['order_id','goods_id','goods_name','goods_sn','isbn','number','return_number','market_price','purchase_price'];//数据键
        try {
          $res= Yii::$app->db->createCommand()->batchInsert(PurchaseReturnGoods::tableName(), $goods_title, $purchase_goods_arr)->execute();
        } catch (Exception $e) {
          $transaction->rollBack();
          message::result_json(2,'插入商品错误');
        }
        $transaction->commit();

      }else{
        message::result_json(2,'没有商品记录');
      }
      message::result_json(1,'采购退货单生成成功');
    }

    //得到相关付款单
    public function actionPayment($id){
      $this->layout = 'empty';
      $payment = Payment::find()->where(['order_id'=>$id])->all();
        if (count($payment)==0){
            $Payment = new Payment();
            $Payment->load(Yii::$app->request->post());


            $Payment->remain_time = $Payment->remain_time?strtotime($Payment->remain_time):time();

            $Purchase = Purchase::findone($Payment->order_id);

            $Payment->supplier_id = $Purchase->supplier_id;
            $Payment->supplier_name = $Purchase->supplier_name;
            $Payment->order_total = $Purchase->total;
            $Payment->relate_order_sn = $Purchase->order_sn;
            $Payment->model = 'Purchase';
            $Payment->order_id=$id;

            //计算收款单总金额 收款单总金额不能大于销售单金额
            $Purchase->total = Payment::find()->where(['order_id'=>$Payment->order_id,'model'=>'Purchase'])->sum('fee');

            $Payment->order_sn = Common_fun::create_sn('app\common\models\Payment',5);
            $Payment->save(false);
            $payment = Payment::find()->where(['order_id'=>$id])->all();
        }


      return $this->render('payment', ['payment'=>$payment,'id'=>$id]);
    }

    //创建付款单
    public function actionCreatePayment($id){
        $this->layout = 'empty';
        return $this->render('create-payment', ['id'=>$id]);
    }

    //创建付款单
    public function actionInsertPayment($id){
      $Payment = new Payment();
      $Payment->load(Yii::$app->request->post());

      if (!$Payment->fee || $Payment->fee <= 0) {
        Message::result_json(2,'请输入收款金额');
      }

      if (!$Payment->order_id) {
        Message::result_json(2,'采购单不能为空');
      }

      $Payment->remain_time = $Payment->remain_time?strtotime($Payment->remain_time):time();

      $Purchase = Purchase::findone($Payment->order_id);
      if (!$Purchase) {
        Message::result_json(2,'采购单不存在');
      }else{
        $Payment->supplier_id = $Purchase->supplier_id;
        $Payment->supplier_name = $Purchase->supplier_name;
        $Payment->order_total = $Purchase->total;
        $Payment->relate_order_sn = $Purchase->order_sn;
        $Payment->model = 'Purchase';
      }

      //计算收款单总金额 收款单总金额不能大于销售单金额
      $sum_total = Payment::find()->where(['order_id'=>$Payment->order_id,'model'=>'Purchase'])->sum('fee');
      $sum_total += $Payment->fee;
      if ($sum_total > $Purchase->total) {
        Message::result_json(2,'收款单总金额不能大于销售单金额');
      }

      $Payment->order_sn = Common_fun::create_sn('app\common\models\Payment',5);

      $Payment->save(false);
      Message::result_json(1,'添加成功');
    }

    //编辑付款单
    public function actionEditPayment($id){
        $this->layout = 'empty';
        $Payment = Payment::findone($id);
        return $this->render('create-payment', ['id'=>$id,'payment'=>$Payment]);
    }

    //编辑付款单
    public function actionUpdatePayment($id){
      $Payment = Payment::findone($id);
      $Payment->load(Yii::$app->request->post());

      if (!$Payment->fee || $Payment->fee <= 0) {
        Message::result_json(2,'请输入收款金额');
      }

      unset($Payment->order_id);

      $Payment->remain_time = $Payment->remain_time?strtotime($Payment->remain_time):time();

      //计算收款单总金额 收款单总金额不能大于销售单金额
      $sum_total = Payment::find()->where(['order_id'=>$Payment->order_id,'model'=>'SellOrder'])->andwhere(['<>','id',$id])->sum('fee');
      $sum_total += $Payment->fee;
      if ($sum_total > $sell_order->total) {
        Message::result_json(2,'收款单总金额不能大于销售单金额');
      }

      $Payment->save(false);
      Message::result_json(1,'编辑成功');
    }

    //删除付款单
    public function actionDeletePayment($id){
      $Payment = Payment::findone($id);
      $Payment->delete();
      Message::result_json(1,'删除成功');
    }


    //得到相关发票
    public function actionInvoice($id){
      $this->layout = 'empty';
      $AcceptInvoice = AcceptInvoice::find()->where(['order_id'=>$id])->all();
      return $this->render('invoice', ['accept_invoice'=>$AcceptInvoice,'id'=>$id]);
    }

    //创建待开发票
    public function actionCreateInvoice($id){
        $this->layout = 'empty';
        return $this->render('create-invoice', ['id'=>$id]);
    }

    //创建发票
    public function actionInsertInvoice($id){
      $AcceptInvoice = new AcceptInvoice();
      $AcceptInvoice->load(Yii::$app->request->post());

      if (!$AcceptInvoice->fee || $AcceptInvoice->fee <= 0) {
        Message::result_json(2,'请输入发票金额');
      }

      //设置order_id 为接受参数id 预防用户添加其他单据的收款发票
      $AcceptInvoice->order_id = $id;
      if (!$AcceptInvoice->order_id) {
        Message::result_json(2,'采购单不能为空');
      }

      $AcceptInvoice->remain_time = $AcceptInvoice->remain_time?strtotime($AcceptInvoice->remain_time):time();



      $Purchase = Purchase::findone($AcceptInvoice->order_id);
      if (!$Purchase) {
        Message::result_json(2,'采购单不存在');
      }else{
        $AcceptInvoice->supplier_id = $Purchase->supplier_id;
        $AcceptInvoice->supplier_name = $Purchase->supplier_name;
        $AcceptInvoice->relate_order_sn = $Purchase->order_sn;
        $AcceptInvoice->order_total = $Purchase->total;

        $AcceptInvoice->model = 'Purchase';
      }
      $AcceptInvoice->accept_invoice_status = 0;
      //计算收款单总金额 收款单总金额不能大于销售单金额
      $sum_total = AcceptInvoice::find()->where(['order_id'=>$AcceptInvoice->order_id,'model'=>'Purchase'])->sum('fee');
      $sum_total += $AcceptInvoice->fee;
      if ($sum_total > $Purchase->total) {
        Message::result_json(2,'发票总金额不能大于销售单金额');
      }

      $AcceptInvoice->order_sn = Common_fun::create_sn('app\common\models\AcceptInvoice',5);

      $AcceptInvoice->save(false);
      Message::result_json(1,'添加成功');
    }

    //编辑发票
    public function actionEditInvoice($invoice_id){
        $this->layout = 'empty';
        $AcceptInvoice = AcceptInvoice::findone($invoice_id);

        return $this->render('create-invoice', ['id'=>$id,'accept_invoice'=>$AcceptInvoice]);
    }

    //编辑发票
    public function actionUpdateInvoice($id,$invoice_id){
      $AcceptInvoice = AcceptInvoice::findone($invoice_id);
      $AcceptInvoice->load(Yii::$app->request->post());

      if (!$AcceptInvoice->fee || $AcceptInvoice->fee <= 0) {
        Message::result_json(2,'请输入发票金额');
      }

      //设置order_id 为接受参数id 预防用户添加其他单据的收款发票
      $AcceptInvoice->order_id = $id;
      if (!$AcceptInvoice->order_id) {
        Message::result_json(2,'采购单不能为空');
      }

      $AcceptInvoice->remain_time = $AcceptInvoice->remain_time?strtotime($AcceptInvoice->remain_time):time();

      $Purchase = Purchase::findone($AcceptInvoice->order_id);
      if (!$Purchase) {
        Message::result_json(2,'采购单不存在');
      }else{
        $AcceptInvoice->supplier_id = $Purchase->supplier_id;
        $AcceptInvoice->supplier_name = $Purchase->supplier_name;
        $AcceptInvoice->relate_order_sn = $Purchase->order_sn;
        $AcceptInvoice->order_total = $Purchase->total;
        $AcceptInvoice->model = 'Purchase';
      }

      //计算收款单总金额 收款单总金额不能大于销售单金额
      $sum_total = AcceptInvoice::find()->where(['order_id'=>$AcceptInvoice->order_id,'model'=>'Purchase'])->andwhere(['<>','id',$invoice_id])->sum('fee');
      $sum_total += $AcceptInvoice->fee;
      if ($sum_total > $Purchase->total) {
        Message::result_json(2,'发票总金额不能大于销售单金额');
      }

      $AcceptInvoice->order_sn = Common_fun::create_sn('app\common\models\AcceptInvoice',5);

      $AcceptInvoice->save(false);
      Message::result_json(1,'编辑成功');
    }

    //删除发票
    public function actionDeleteInvoice($invoice_id){
      $AcceptInvoice = AcceptInvoice::findone($invoice_id);
      $AcceptInvoice->delete();
      Message::result_json(1,'删除成功');
    }



}