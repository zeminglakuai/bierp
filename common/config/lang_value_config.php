<?php
namespace app\common\config;

class lang_value_config
{
    static $custom_order_status = ['0'=>'未处理','1'=>'首次复核','2'=>'二次复核','3'=>'已生成销售单',9=>'作废'];
    static $ask_price_order_status = ['0'=>'未处理','1'=>'供货商回馈','2'=>'已复核','3'=>'已反馈到客户方案',9=>'作废'];
    static $sell_order_status = ['0'=>'未处理','1'=>'已复核',9=>'作废'];
    static $contract_status = ['0'=>'未处理','1'=>'已复核','2'=>'已回收合同',9=>'作废'];
    static $sell_order_return_status = ['0'=>'未处理','1'=>'已复核',9=>'作废'];
    static $shipping_status = ['0'=>'未发货','1'=>'部分发货','2'=>'全部发货',9=>'作废'];
    static $return_status = ['0'=>'无退货','1'=>'部分退货',9=>'作废'];
    static $pay_status = ['0'=>'未付款','1'=>'部分付款','2'=>'全部付款',9=>'作废'];
    static $invoice_status = ['0'=>'未开发票','1'=>'部分开发票','2'=>'全部开发票',9=>'作废'];
    static $goods_auth_status = ['0'=>'未处理','1'=>'已复核'];
    static $goods_status = ['0'=>'未复核','1'=>'已复核','2'=>'已删除'];
    static $purchase_status = ['0'=>'未复核','1'=>'已复核','2'=>'已删除'];
    static $mession_status = ['0'=>'未复核','1'=>'已复核'];
    static $datum_cat = ['1'=>'培训文档','2'=>'合同模板'];
    static $export_order_status = ['0'=>'未复核','1'=>'已复核'];
    static $import_order_status = ['0'=>'未复核','1'=>'已复核'];
    static $materiel_purchase_status = ['0'=>'未复核','1'=>'已复核'];
    static $purchase_return_status = ['0'=>'未复核','1'=>'已复核'];
    static $receipt_status = ['0'=>'未复核','1'=>'已复核'];
    static $sell_invoice_status = ['0'=>'未收取','1'=>'已收取'];
    static $return_type = ['1'=>'退回工厂','2'=>'退回我司','3'=>'我司退回工厂'];
    static $plat_status = ['0'=>'未审核','1'=>'已审核']; 
    static $web_order_status = ['0'=>'未处理','1'=>'已同步','2'=>'已发货','3'=>'已收货'];
    static $promote_status = ['0'=>'未处理','1'=>'已审核'];
    static $supplier_status = ['0'=>'未处理','1'=>'已审核'];
    static $specimen_purchase_status = ['0'=>'未处理','1'=>'已审核'];
    static $other_import_order_type = ['1'=>'退换货入库','2'=>'盘点增溢'];    //其他入库单类型
    static $other_import_order_status = ['0'=>'未复核','1'=>'已复核'];    //其他入库单状
    static $deal_type = ['1'=>'转回库存','2'=>'报损出库'];    //其他入库单处理方式
    static $other_export_order_type = ['1'=>'盘点损失','2'=>'报损出库'];    //其他出库单处理方式 
    static $other_export_order_status = ['0'=>'未复核','1'=>'已复核'];
    static $payment_status = ['0'=>'未复核','1'=>'已复核'];
    static $accept_invoice_status = ['0'=>'未复核','1'=>'已复核'];
    static $request_adjust_status = ['0'=>'未复核','1'=>'已复核'];
    static $stock_lock_status = ['0'=>'未复核','1'=>'已复核'];
    static $b2c_order_status = ['0'=>'未复核','1'=>'已复核'];
    static $depreciation_status = ['1'=>'平均年限法','2'=>'工作量法','3'=>'加速折旧法'];

}