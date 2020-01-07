<?php

namespace app\common\models;

use Yii;
use app\common\models\BaseModel;

class Platform extends BaseModel
{
    public $ppt_file = '';
    public $goods_platform_id = '';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'platform';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_user_id', 'depart_id', 'plat_status', 'is_delete', 'custom_id', 'status_done'], 'integer'],
            [['plat_info'], 'string'],
            [['plat_name', 'add_time', 'add_user_name', 'depart_name', 'custom_name', 'remark', 'status_name', 'website_front', 'website_backend', 'login_user_name', 'log.in_pass', 'web_cate', 'contract_start_time', 'contract_end_time', 'contract_contact', 'contract_tel', 'theme_block', 'theme_contact', 'theme_contact_tel', 'theme_content', 'brand_open', 'brand_contact', 'brand_contact_tel', 'brand_remark', 'period', 'yongjin', 'period_contact', 'period_contact_tel', 'period_desc', 'period_gongdan', 'gongdan_contact', 'gongdan_contact_tel', 'hezuoxingshi', 'address', 'startdate', 'enddate'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'plat_name' => '项目名称',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'plat_info' => 'Plat Info',
            'plat_status' => 'Plat Status',
            'is_delete' => 'Is Delete',
            'custom_id' => 'Custom ID',
            'custom_name' => '相关客户名称',
            'remark' => 'Remark',
            'status_done' => '审核流程结束',
            'status_name' => 'Status Name',
            'website_front' => '前端网站',
            'website_backend' => '网站后台地址',
            'login_user_name' => '登录账号',
            'login_pass' => '登录密码',
            'web_cate' => '品类',
            'contract_start_time' => '合同开始时间',
            'contract_end_time' => '合同结束时间',
            'contract_contact' => '合同联系人',
            'contract_tel' => '合同联系人手机',
            'theme_block' => '主题活动提报',
            'theme_contact' => '主题对接人',
            'theme_contact_tel' => '主题对接人手机',
            'theme_content' => '具体工作内容',
            'brand_open' => '品牌开通',
            'brand_contact' => '品牌开通对接人',
            'brand_contact_tel' => '联系方式',
            'brand_remark' => '备注',
            'period' => '结算周期',
            'yongjin' => '平台佣金',
            'period_contact' => '结算对接人',
            'period_contact_tel' => '联系方式',
            'period_desc' => '结算说明',
            'period_gongdan' => '工单处理',
            'gongdan_contact' => '对接人',
            'gongdan_contact_tel' => '联系方式',
            'hezuoxingshi' => '合作形式',
            'address' => '地址',
        ];
    }
    /**
     * @inheritdoc
     */
    public function exportLabels()
    {
        return [
            'id' => 'ID',
            'plat_name' => '项目名称',
            'add_time' => 'Add Time',
            'add_user_id' => 'Add User ID',
            'add_user_name' => 'Add User Name',
            'depart_id' => 'Depart ID',
            'depart_name' => 'Depart Name',
            'plat_info' => 'Plat Info',
            'plat_status' => 'Plat Status',
            'is_delete' => 'Is Delete',
            'custom_id' => 'Custom ID',
            'custom_name' => 'Custom Name',
            'remark' => 'Remark',
            'status_done' => '审核流程结束',
            'status_name' => 'Status Name',
            'website_front' => '前端网站',
            'website_backend' => '网站后台地址',
            'login_user_name' => '登录账号',
            'login_pass' => '登录密码',
            'web_cate' => '品类',
            'contract_start_time' => '合同开始时间',
            'contract_end_time' => '合同结束时间',
            'contract_contact' => '合同联系人',
            'contract_tel' => '合同联系人手机',
            'theme_block' => '主题活动提报',
            'theme_contact' => '主题对接人',
            'theme_contact_tel' => '主题对接人手机',
            'theme_content' => '具体工作内容',
            'brand_open' => '品牌开通',
            'brand_contact' => '品牌开通对接人',
            'brand_contact_tel' => '联系方式',
            'brand_remark' => '备注',
            'period' => '结算周期',
            'yongjin' => '平台佣金',
            'period_contact' => '结算对接人',
            'period_contact_tel' => '联系方式',
            'period_desc' => '结算说明',
            'period_gongdan' => '工单处理',
            'gongdan_contact' => '对接人',
            'gongdan_contact_tel' => '联系方式',
            'hezuoxingshi' => '合作形式',
            'address' => '地址',
        ];
    }
    public function getContactList()
    {
        return $this->hasMany(Contact::className(), ['belong_id' => 'id'])->where(['model' => 'platform']);
    }
}
