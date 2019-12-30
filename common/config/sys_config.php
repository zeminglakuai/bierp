<?php

namespace app\common\config;

class sys_config
{
    //网站导航
    static $nav_list = [
        'out-manager'  => [
            'label' => 'shopping-cart', 'name' => '销售管理', 'sub_list' => [
                'platform' => ['label' => 'sort-by-attributes', 'name' => '项目档案', 'is_approval' => true],
                'custom-order' => ['label' => 'cog', 'name' => '客户方案', 'title' => 'CustomOrder', 'detail_list' => 'CustomOrderGoods', 'is_approval' => true,  'export_ppt' => 'CustomOrderGoods'],

                //                        'b2c-order'=>['label'=>'cog','name'=>'B2C方案','title'=>'B2cOrder','detail_list'=>'B2cOrderGoods','is_approval'=>true,'export_ppt'=>'B2cOrderGoods'],
                'ask-price' => ['label' => 'cog', 'name' => '询价报备', 'title' => 'AskPriceOrder', 'detail_list' => 'AskPriceOrderGoods', 'is_approval' => true],
                'sell-order' => ['label' => 'star-empty', 'name' => '销售单据', 'title' => 'SellOrder', 'detail_list' => 'SellOrderGoods', 'is_approval' => true],
                'sell-contract' => ['label' => 'volume-up', 'name' => '合同管理', 'is_approval' => true],
                //                        'sell-order-return'=>['label'=>'volume-up','name'=>'销售退换','title'=>'SellOrderReturn','detail_list'=>'SellOrderReturnGoods','is_approval'=>true],
                //                        'mession'=>['label'=>'volume-up','name'=>'任务下达','is_approval'=>true],
            ]
        ],
        'in-manage' => [
            'label' => 'download', 'name' => '采购管理', 'sub_list' => [
                'purchase' => ['label' => 'cog', 'name' => '项目采购', 'title' => 'Purchase', 'detail_list' => 'PurchaseGoods', 'is_approval' => true],
                'purchase-return' => ['label' => 'volume-up', 'name' => '采购退货单', 'title' => 'PurchaseReturn', 'detail_list' => 'PurchaseReturnGoods', 'is_approval' => true],
                // 'purchase-contract' => ['label' => 'volume-up', 'name' => '采购合同', 'is_approval' => true],
                // 'specimen-purchase'=>['label'=>'volume-up','name'=>'样板采购单','title'=>'SpecimenPurchase','detail_list'=>'SpecimenPurchaseGoods','is_approval'=>true],
                /*    'materiel-purchase'=>['label'=>'volume-up','name'=>'物料采购','title'=>'MaterielPurchase','detail_list'=>'MaterielPurchaseGoods','is_approval'=>true],
              'materiel-request'=>['label'=>'volume-up','name'=>'物料申请','title'=>'MaterielRequest','detail_list'=>'MaterielRequestGoods','is_approval'=>true],*/
            ]
        ],
        'stor'  => [
            'label' => 'align-justify', 'name' => '库存管理', 'sub_list' => [
                'storage' => ['label' => 'qrcode', 'name' => '库存查询', 'title' => 'stock', 'is_approval' => true],
                'storage?type=2' => ['label' => 'qrcode', 'name' => '滞销商品', 'title' => 'stock', 'is_approval' => true],
                'import-order' => ['label' => 'sort-by-attributes', 'name' => '入库单据', 'title' => 'ImportOrder', 'detail_list' => 'ImportOrderGoods', 'is_approval' => true],
                'export-order' => ['label' => 'sort-by-attributes', 'name' => '出库单据', 'title' => 'ExportOrder', 'detail_list' => 'ExportOrderGoods', 'is_approval' => true],
                'daifa-order' => ['label' => 'sort-by-attributes', 'name' => '代发单据', 'title' => 'DaifaOrder', 'detail_list' => 'DaifaOrderGoods', 'is_approval' => true],
                //                    'check-stor'=>['label'=>'credit-card','name'=>'盘点'],
                //'adjust-stor'=>['label'=>'','name'=>'调仓单','is_approval'=>true], //调整商品库位
                'request-adjust' => ['label' => '', 'name' => '申调单', 'is_approval' => true], //仓库之间库存挑拨
                //                    'stock-lock'=>['label'=>'','name'=>'库存锁货','is_approval'=>true], //预先做出库存 防止销售
                //                                'other-export-order'=>['label'=>'sort-by-attributes','name'=>'其他出库','title'=>'OtherExportOrder','detail_list'=>'OtherExportOrderGoods','is_approval'=>true],
                //                                'other-import-order'=>['label'=>'sort-by-attributes','name'=>'其他入库','title'=>'OtherImportOrder','detail_list'=>'OtherImportOrderGoods','is_approval'=>true],
                //       'goods-implode'=>['label'=>'sort-by-attributes','name'=>'滞销产品管理','is_approval'=>true],
                //                    'specimen-stock'=>['label'=>'sort-by-attributes','name'=>'样板库存'],
            ]
        ],
        'base' => [
            'label' => 'barcode', 'name' => '商品管理', 'sub_list' => [
                'goods' => ['label' => 'qrcode', 'name' => '商品分布', 'title' => 'Goods', 'is_approval' => true],
                'goods?type=5' => ['label' => 'qrcode', 'name' => '商品列表', 'title' => 'Goods', 'is_approval' => true],
                'goods?type=2' => ['label' => 'qrcode', 'name' => '样品管理', 'title' => 'Goods', 'is_approval' => true],
                'goods?type=3' => ['label' => 'qrcode', 'name' => '商品整合', 'title' => 'Goods', 'is_approval' => true],
                'supplier' => ['label' => 'sort-by-attributes', 'name' => '供货商管理', 'title' => 'Supplier', 'is_approval' => true],
                'shipping-method' => ['label' => 'asd', 'name' => '配送方式', 'title' => 'ShippingMethod'],
                'category' => ['label' => 'category', 'name' => '分类管理'],
                'brand' => ['label' => 'brand', 'name' => '品牌管理', 'title' => 'Brand'],
                //                                'goods-auth'=>['label'=>'brand','name'=>'产品授权','title'=>'GoodsAuth','detail_list'=>'GoodsAuthGoods','is_approval'=>true],
                // 'datum' => ['label' => 'brand', 'name' => '基础合同', 'is_approval' => true],
            ]
        ],
        'e-business' => [
            'label' => 'shopping-cart', 'name' => '电商平台', 'sub_list' => [

                // 'theme'=>['label'=>'sort-by-attributes','name'=>'主题活动','is_approval'=>true],
                'web-order' => ['label' => 'qrcode', 'name' => '订单列表', 'is_approval' => true],
                'big-data' => ['label' => 'sort-by-attributes', 'name' => '大数据挖掘'],
                'web-user' => ['label' => 'category', 'name' => '全网会员', 'title' => 'WebUser'],
                'express' => ['label' => 'brand', 'name' => '物流数据管理'],
                'promote' => ['label' => 'volume-up', 'name' => '促销管理', 'is_approval' => true],

            ]
        ],

        'custom' => [
            'label' => 'user-md', 'name' => '客户管理', 'sub_list' => [
                'custom-item' => ['label' => 'qrcode', 'name' => '客户项目', 'is_approval' => true],
                'custom' => ['label' => 'qrcode', 'name' => '客户列表', 'title' => 'Custom', 'is_approval' => true],
                'process' => ['label' => 'sort-by-attributes', 'name' => '销售过程'],
                'service' => ['label' => 'category', 'name' => '服务管理'],
                'plan' => ['label' => 'brand', 'name' => '工作计划'],
            ]
        ],
        'finance' => [
            'label' => 'credit-card', 'name' => '财务管理', 'sub_list' => [
                //'cashier'=>['label'=>'credit-card','name'=>'出纳管理'],
                'payment' => ['label' => 'sort-by-attributes', 'name' => '应付账款', 'is_approval' => true],
                'receipt' => ['label' => 'sort-by-attributes', 'name' => '应收账款', 'is_approval' => true],
                'sell-invoice' => ['label' => 'sort-by-attributes', 'name' => '销售发票', 'is_approval' => true],
                'accept-invoice' => ['label' => 'sort-by-attributes', 'name' => '进项发票', 'is_approval' => true],
                'other-fee' => ['label' => 'sort-by-attributes', 'name' => '费用管理', 'is_approval' => true],
                'wages' => ['label' => 'sort-by-attributes', 'name' => '工资管理'],
                'pre-deposit' => ['label' => 'sort-by-attributes', 'name' => '客户预存款'],
            ]
        ],
        'report' => [
            'label' => 'file', 'name' => '报表管理', 'sub_list' => [
                'sell-report' => ['label' => 'cog', 'name' => '销售报表'],
                'purchase-report' => ['label' => 'star-empty', 'name' => '采购报表'],
                'custom-busniss' => ['label' => 'volume-up', 'name' => '商户合作报表'],
                'unmarketable' => ['label' => 'volume-up', 'name' => '滞销产品报表'], //查询一段时间没有销售 和 入库时间比较久的商品
                'expire-goods' => ['label' => '', 'name' => '有效期产品'], //
                'warn-stor' => ['label' => 'sort-by-attributes', 'name' => '库存预警'],
                'goods-flow' => ['label' => 'sort-by-attributes', 'name' => '商品流水账'],
            ]
        ],
        'assets' => [
            'label' => 'yen', 'name' => '固定资产', 'sub_list' => [
                'asset-purchase-order' => ['label' => 'star-empty', 'name' => '非经营性采购'],
                'asset' => ['label' => 'credit-card', 'name' => '固定资产列表'],
                'asset-out' => ['label' => 'sort-by-attributes', 'name' => '固定资产销售'],
                'inventory' => ['label' => 'sort-by-attributes', 'name' => '固定资产盘点'],
            ]
        ],
        'users' => [
            'label' => 'user', 'name' => '员工管理', 'sub_list' => [
                'manager' => ['label' => 'cog', 'name' => '员工列表'],
                'accession' => ['label' => 'star-empty', 'name' => '入职管理'],
                'trip' => ['label' => 'volume-up', 'name' => '差旅管理'],
                'leave' => ['label' => 'volume-up', 'name' => '请假申请'],
                'position-request' => ['label' => 'volume-up', 'name' => '职位申请'],
                'dimission' => ['label' => 'volume-up', 'name' => '离职交接'],
            ]
        ],
        'config' => [
            'label' => 'cog', 'name' => '系统设置', 'sub_list' => [
                'store' => ['label' => 'cog', 'name' => '仓库管理'],
                'depart' => ['label' => 'cog', 'name' => '部门管理'],
                'approval-process' => ['label' => 'cog', 'name' => '审批流程'],
                'dictionary' => ['label' => 'dictionary', 'name' => '数据词典'],
                'qy-wechat' => ['label' => 'volume-up', 'name' => '企业微信'],
                'subcribe-wechat' => ['label' => 'volume-up', 'name' => '微信公众号'],
                'sys-config' => ['label' => 'volume-up', 'name' => '系统配置'],
                'export-template' => ['label' => 'volume-up', 'name' => '导出模板'], //导出格式设置
                'export-ppt-template' => ['label' => 'volume-up', 'name' => '导出PPT模板'], //导出格式设置
                'print-template' => ['label' => 'volume-up', 'name' => '打印模板'], //打印格式设置
                'help' => ['label' => 'volume-up', 'name' => '帮助文档'], //打印格式设置
            ]
        ],
    ];
    //权限分配
    static $privilege_desc = [
        'custom-order'  => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view', 'export', 'export-ppt']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'insert', 'edit', 'update', 'token-custom-search', 'create-goods', 'create-goods', 'search-supplier', 'insert-goods', 'delete-goods', 'update-goods-label', 'remend-history', 'create-ask-price', 'create-sell-order']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'b2c-order'  => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view', 'export', 'export-ppt']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'insert', 'edit', 'update', 'token-custom-search', 'create-goods', 'create-goods', 'search-supplier', 'insert-goods', 'delete-goods', 'update-goods-label', 'remend-history', 'create-ask-price', 'create-sell-order']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'ask-price' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view', 'token-custom-order']],
            'add'     => ['name' => '添加/编辑', 'clude_action' => ['create', 'insert', 'edit', 'create-goods', 'insert-goods', 'search-supplier', 'delete-goods', 'update-goods-label', 'view-check-url',]],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'sell-contract' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'delete', 'insert', 'edit', 'view', 'edit-contract', 'update-contract', 'update', 'admit', 'contract-file', 'update-label', 'keep-contract', 'act-keep-contract', 'create', 'update', 'insert', 'edit', 'token-sell-order', 'contract-search']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'sell-order' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view', 'token-custom']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'create-goods', 'insert', 'edit', 'update', 'insert-goods', 'delete-goods', 'update-goods-label', 'receipt', 'create-receipt', 'insert-receipt', 'edit-receipt', 'update-receipt', 'delete-receipt', 'invoice', 'create-invoice', 'insert-invoice', 'edit-invoice', 'update-invoice', 'delete-invoice']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'oprate' => ['name' => '生成其他单据', 'clude_action' => ['create-sell-order-return', 'create-purchase-order']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'sell-order-return' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'insert', 'token-sell-order', 'edit', 'update', 'create-goods', 'delete-goods', 'update-goods-label']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'mession' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view',]],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
        ],
        'promote' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view', 'searchuser']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'purchase' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view', 'searchuser']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'insert', 'view', 'edit', 'update', 'create-goods', 'insert-goods', 'delete-goods', 'update-goods-label', 'create-purchase-return', 'payment', 'create-payment', 'insert-payment', 'edit-payment', 'update-payment', 'delete-payment', 'invoice', 'create-invoice', 'insert-invoice', 'edit-invoice', 'update-invoice', 'delete-invoice',]],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'purchase-contract' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'delete', 'insert', 'edit', 'view', 'edit-contract', 'update-contract', 'update', 'admit', 'contract-file', 'update-label', 'keep-contract', 'act-keep-contract', 'create', 'update', 'insert', 'edit', 'token-sell-order', 'contract-search']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'purchase-return' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'insert', 'view', 'edit', 'update', 'insert-goods', 'delete-goods', 'update-goods-label',]],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'specimen-purchase' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'insert', 'view', 'edit', 'update', 'insert-goods', 'delete-goods', 'update-goods-label',]],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'materiel-purchase' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'insert', 'view', 'edit', 'update', 'insert-materiel', 'delete-materiel', 'update-materiel-label']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'materiel-request' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'insert', 'view', 'update', 'insert-materiel', 'delete-materiel', 'update-materiel-label',]],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'other-import-order' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'delete', 'insert', 'edit', 'update', 'delete-goods', 'update-goods-label',]],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
        ],
        'other-export-order' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'insert', 'edit', 'update', 'insert-goods', 'delete-goods', 'update-goods-label',]],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
        ],
        'storage' => [
            'view' => ['name' => '查看', 'clude_action' => ['index']],
        ],
        'import-order' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['insert', 'edit', 'update', 'insert-goods', 'delete-goods', 'update-goods-label',]],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'export-order' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'insert', 'edit', 'update', 'insert-goods', 'delete-goods', 'update-goods-label', 'reload-store-code',]],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'daifa-order' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'insert', 'edit', 'update', 'insert-goods', 'delete-goods', 'update-goods-label',]],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'accession' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'delete', 'insert', 'edit', 'view', 'edit-contract', 'update-contract', 'update', 'admit', 'contract-file', 'update-label', 'keep-contract', 'act-keep-contract', 'create', 'update', 'insert', 'edit', 'token-sell-order', 'contract-search']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'trip' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'insert', 'view', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'leave' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'insert', 'view', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'position-request' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'insert', 'view', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'dimission' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'insert', 'view', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'templat' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view', 'searchuser']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['confirm']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'adjust-stor' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view', 'searchuser']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['confirm']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ], //调整商品库位
        'request-adjust' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view', 'searchuser']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['confirm']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ], //仓库之间库存挑拨
        'sold-low' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view', 'searchuser']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['confirm']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'empire' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view', 'searchuser']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['confirm']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'payment' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'insert', 'edit', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'receipt' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'insert', 'edit', 'update',]],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'store-lock' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['confirm']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'sell-invoice' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['confirm']],
        ],
        'approval-process' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'process-list', 'create', 'insert', 'edit', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
        ],
        'goods-implode' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['confirm']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'specimen' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['confirm']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'specimen-store' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['confirm']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'goods' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view', 'edit']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'update', 'insert', 'check-goods-sn']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['confirm'], 'widget' => ['confirm-list']],
        ],
        'goods?type=2' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view', 'edit']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'update', 'insert', 'check-goods-sn']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['confirm'], 'widget' => ['confirm-list']],
        ],
        'goods?type=3' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view', 'edit']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'update', 'insert', 'check-goods-sn']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['confirm'], 'widget' => ['confirm-list']],
        ],
        'supplier' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view', 'searchuser']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['edit', 'create', 'insert', 'update', 'update-supplier-info', 'admit', 'create-consignee', 'insert-consignee', 'edit-consignee', 'update-consignee', 'delete-consignee', 'create-contact', 'insert-contact', 'edit-contact', 'update-contact', 'delete-contact',]],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'shipping-method' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view', 'searchuser']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'insert', 'edit', 'update',]],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
        ],
        'manager' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'add-admin', 'edit-admin', 'update-admin', 'get-role-list', 'change-pass', 'update-pass', 'admin-log',]],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
        ],
        'category' => [
            'view' => ['name' => '查看', 'clude_action' => ['index']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'add', 'update-catename',]],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete-cate']],
        ],
        'brand' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view', 'searchuser']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['confirm']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'datum' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'edit', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
        ],
        'cashier' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view', 'searchuser']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'goods-auth' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'insert', 'edit', 'update', 'insert-goods', 'delete-goods', 'update-goods-label',]],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'dictionary' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view',]],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create-value', 'insert-value', 'view-value', 'update-value']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete-value']],
        ],
        'accept-invoice' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view', 'searchuser']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['admit']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'other-fee' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view', 'searchuser']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['confirm']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'wages' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view', 'searchuser']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['confirm']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'pre-deposit' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view', 'searchuser']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'update']],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'confirm' => ['name' => '审核', 'clude_action' => ['confirm']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'store' => [
            'view' => ['name' => '查看', 'clude_action' => ['index']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['index', 'create', 'add-store', 'view', 'edit-store', 'delete-store', 'update-store-info',]],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
        ],
        'depart' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'role-list']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'add-depart', 'update-depart', 'delete-depart', 'create-role', 'insert-role', 'edit-role', 'update-role', 'delete-role', 'edit-privi', 'act-privilege',]],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
        ],
        'custom' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'insert', 'edit', 'update', 'delete-img', 'create-consignee', 'insert-consignee', 'edit-consignee', 'update-consignee', 'delete-consignee', 'create-contact', 'insert-contact', 'edit-contact', 'update-contact', 'delete-contact',]],
            'delete'  => ['name' => '删除', 'clude_action' => ['delete']],
            'scope' => ['name' => '权限范围', 'clude_action' => [0, 1, 2]],
        ],
        'help' => [
            'view' => ['name' => '查看', 'clude_action' => ['index', 'view']],
            'add'  => ['name' => '添加/编辑', 'clude_action' => ['create', 'content-frame', 'content', 'update']],
        ],
    ];
    static $YON = [0 => '否', 1 => '是'];

    //不需要记录管理员日志的action_id 数组
    static $admin_log_arr = ['login', 'create', 'edit', 'view'];

    static $depart_type = [1 => '总部', 2 => '财务部门', 3 => '仓储部门', 4 => '业务部门', 5 => '电商部门', 6 => '技术部门'];
    static $approval_depart_type = [0 => '当前建表部门', 1 => '总部', 2 => '财务部门', 3 => '仓储部门', 4 => '业务部门', 5 => '技术部门'];
    static $role_type = [1 => '总经理', 2 => '部门主管', 3 => '员工'];

    static $scope = [1 => '全部', 2 => '部门', 3 => '自己'];
}
