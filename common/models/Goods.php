<?php

namespace app\common\models;

use Yii;

use app\common\models\DictionaryValue;
use app\common\models\Brand;
use app\common\models\Supplier;
use app\common\models\Category;
use app\common\models\FileInfo;
use app\common\models\Stock;
use app\common\models\SupplierGoods;

class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $goods_supplier = '';

    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_id', 'brand_id', 'is_delete', 'supplier_id', 'warn_number', 'is_on_sale', 'expire_unit', 'is_clude_tax', 'is_active', 'is_self_sell', 'is_alone_sale', 'sort_order', 'last_update', 'goods_type', 'warning_number', 'add_user_id', 'goods_unit', 'weight_unit', 'depart_id'], 'integer'],
            [['goods_weight', 'market_price', 'shop_price', 'purchase_price', 'supplier_price', 'special_price', 'fixed_price'], 'number'],
            [['goods_desc', 'default_supplier_id', 'default_supplier_name'], 'required'],
            [['goods_desc', 'depart_name'], 'string'],
            [['goods_sn', 'goods_name_style', 'supplier_name'], 'string', 'max' => 60],
            [['goods_name'], 'string', 'max' => 120],
            [['keywords', 'goods_brief', 'carton', 'clude_tax', 'clude_shipping_fee', 'clude_shipping_fee', 'goods_thumb', 'ppt_file', 'goods_img', 'default_supplier_id', 'default_supplier_name', 'add_time', 'isbn', 'expire', 'add_user_name', 'goods_py', 'online_project', 'online_project_effective', 'expire', 'duty_period', 'remarks', 'depart_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => '产品ID',
            'cat_id' => '分类ID',
            'goods_sn' => '型号',
            'goods_name' => '产品名称',
            'goods_name_style' => 'Goods Name Style',
            'brand_id' => '产品品牌',
            'goods_weight' => '产品重量',
            'market_price' => '市场价',
            'shop_price' => '默认售价',
            'warn_number' => '警告库存',
            'keywords' => 'Keywords',
            'goods_brief' => '简述',
            'goods_desc' => '产品描述',
            'goods_thumb' => 'Goods Thumb',
            'goods_img' => '图片',
            'is_on_sale' => 'Is On Sale',
            'add_time' => '添加时间',
            'sort_order' => 'Sort Order',
            'last_update' => '最后更新',
            'goods_type' => '产品类型',
            'warning_number' => '警告库存',
            'isbn' => '条形码',
            'expire' => '有效期',
            'add_user_id' => '添加用户ID',
            'weight_unit' => '重量单位',
            'goods_unit' => '产品单位',
            'goods_py' => '拼音码',
            'expire_unit' => '有效期单位',
            'is_self_sell' => '自营产品',
            'is_delete' => '',
            'supplier_id' => 'supplier_id',
            'supplier_name' => 'supplier_name',
            'supplier_price' => 'supplier_price',
            'ppt_file' => 'ppt_file',
            'carton' => '箱规',
            'is_clude_tax' => '含税',
            'clude_shipping_fee' => '含运费',
            'is_active' => '产品有效',
            'special_price' => '特批价格',
            'contact' => '供应商联系人',
            'tel' => '联系人手机',
            'online_project' => '上线项目',
            'online_project_effective' => '上线项目最长有效期',
            'expire' => '产品有效期',
            'duty_period' => '呆销期',
            'remarks' => '备注',
            'fixed_price' => '固定零售价',

        ];
    }
    //商品导入模板配置
    public function exportLabels()
    {
        return [
            'goods_name' => '商品名称',
            'cat_id' => '分类',
            'goods_sn' => '型号',
            'brand_id' => '品牌',
            'market_price' => '市场价',
            'fixed_price' => '固定零售价',
            'supplier_price' => '采购价',
            'special_price' => '特批价',
            'warn_number' => '库存',
            'supplier_id' => '供应商名称',
            'supplier_name' => '供应商联系人',
            'goods_py' => '联系人手机',
            'online_project' => '上线项目',
            'online_project_effective' => '上线项目最长有效期',
            'expire' => '产品有效期',
            'duty_period' => '呆销期',
            'remarks' => '备注',
            'goods_brief' => '简述',
            'goods_desc' => '产品描述',
            'goods_thumb' => 'Goods Thumb',
        ];
    }

    public function getCate()
    {
        return $this->hasOne(Category::className(), ['id' => 'cat_id']);
    }

    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier_id']);
    }

    public function getGoodsUnitvalue()
    {
        return $this->hasOne(DictionaryValue::className(), ['id' => 'goods_unit']);
    }

    public function getWeightUnitvalue()
    {
        return $this->hasOne(DictionaryValue::className(), ['id' => 'weight_unit']);
    }

    public function getPurchase()
    {
        return $this->hasMany(Supplier::className(), ['id' => 'supplier_id'])
            ->viaTable(SupplierGoods::tableName(), ['goods_id' => 'goods_id']);
    }

    public function getSupplierGoods()
    {
        return $this->hasMany(SupplierGoods::className(), ['goods_id' => 'goods_id']);
    }

    public function getGoodsFile()
    {
        return $this->hasMany(FileInfo::className(), ['belong_id' => 'goods_id'])->where(['model' => $this->tableName()]);
    }

    public function getStockInfo()
    {
        $stock = $this->hasMany(Stock::className(), ['belong_id' => 'goods_id'])->select([
            "sum(number) as number",
        ])->groupBy(['store_id'])->asArray()->all();
        return $stock;
    }
    public function getName($id)
    {
        $query = new \yii\db\Query();
        $s = $query->select('goods_name')->from('goods')->where(['goods_id' => $id])->one();
        return $s['goods_name'];
    }
}
