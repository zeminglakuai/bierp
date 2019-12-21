<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Platform;
use app\common\models\Custom;
use app\common\models\WebOrder;
use app\common\models\Goods;
use yii\helpers\Url;
use app\includes\Message;
use app\common\models\Consignee;
use app\controllers\BaseController;
use app\common\models\UserExtendInfo;
use app\common\models\Contact;
use app\common\models\Contract;
use app\common\models\Theme;
use app\common\models\FileInfo;
use app\common\models\UploadForm;
use app\includes\Common_fun;

class PlatformController extends BaseController
{
    public $page_title = '平台管理';
    public $title_model = 'app\common\models\Platnform';
    public $status_label = 'plat_status';

    public function beforeAction($action)
    {
        parent::beforeAction($action);
        //检查当前单据用户是不是有操作权限
        $admit_allow_arr = ['edit','update','delete','insert','create-contact','create-contract','insert-contact','insert-contract','insert-theme','insert-goods','create-theme','edit-contract','edit-contact','update-goods-label','edit-theme'];
        $need_privi_arr=['edit','update','delete','insert','create-contact'];
        $scope_model = 'app\common\models\Platform';
        $status_label = 'plat_status';

        if ($this->user_scope($action,$need_privi_arr,$admit_allow_arr,$scope_model,$status_label)) {
            return true;
        }else{
            return false;
        }
    }

    //删除
    public function actionDelete($id){
        $Platform = Platform::findone($id);
        if ($Platform) {
            //检查平台是不是已经使用中
            $web_order = WebOrder::find()->where(['plat_id'=>$id])->one();
            if ($web_order) {
                message::result_json(2,'平台使用中，不允许删除');
            }
            $Platform->delete();
            message::result_json(1,'删除成功');
        }else{
            message::result_json(2,'数据不存在');
        }
    }


    public function actionInsert(){
        $Platform = new Platform();
        $Platform->load(Yii::$app->request->post());

        if (!$Platform->plat_name) {
            Message::result_json(2,'平台名称不能为空');
        }

        $Platform->plat_status = 0;

        $Platform->save(false);
        Message::result_json(1,'添加成功');
    }
// 商品展示页
    public function actionEdit($id){

        $hezuoxingshi=array(
            array(
                'id'=>1,
                'val'=>'积分',
            ),
            array(
                'id'=>2,
                'val'=>'现金',
            ),
            array(
                'id'=>3,
                'val'=>'积分+现金',
            ),
            array(
                'id'=>4,
                'val'=>'集采',
            ),
        );
        $this->layout = 'empty';
        $Platform = Platform::findone($id);
        $consignee_list = Consignee::findAll(['belong_id'=>$id,'model'=>'PLATFORM']);
        // 平台商品列表
        $pg_list = Yii::$app->db->createCommand("SELECT g.*,gp.* FROM goods_platform as gp left join goods as g on gp.goods_id=g.goods_id where gp.platform_id=".$id)->queryAll();
        // 平台合同列表
        $contract_list=Yii::$app->db->createCommand("SELECT * FROM contract where order_id=".$id ." and type=5")->queryAll();

        /* foreach ($$ht_list as $key => $value) {
           $ht_list[$key]['file']=Yii::$app->db->createCommand("SELECT * FROM file_info  where model='platform' and belong_id=".$value['id'] )->queryAll();

         }*/
        // 主题列表
        $theme_list=Yii::$app->db->createCommand("SELECT * FROM theme where belong_id=".$id )->queryAll();
        return $this->render('create', ['platform'=>$Platform,'id'=>$id,'consignee_list' => $consignee_list,'pg_list' => $pg_list,'contract_list' => $contract_list,'theme_list' => $theme_list,'hezuoxingshi' => $hezuoxingshi]);
    }

    public function actionUpdate($id){
        $Platform = Platform::findone($id);
        $Platform->load(Yii::$app->request->post());

        if (!$Platform->plat_name) {
            Message::result_json(2,'平台名称不能为空');
        }



        $Platform->plat_status = 1;

        $Platform->save(false);
        Message::result_json(1,'编辑成功');
    }

// 添加商品
    public function actionInsertThemeGoods(){
        $goods_id = Yii::$app->request->get('goods_id',0);

        $platform_id = Yii::$app->request->get('order_id',0);
        $search_data = Yii::$app->request->get('search_data',0);

        if (!$platform_id) {
            message::result_json(2,'数据错误');
        }else{
            $platform = Platform::findone($platform_id);
        }
        $add_goods_error = [];
        if ($goods_id) {
            if (is_array($goods_id)) {

                foreach ($goods_id as $key => $value) {

                    $goods = Goods::findone($value);
                    if ($goods) {

                        if (Yii::$app->db->createCommand("SELECT * FROM goods_platform where goods_id=".$value['goods_id']." and platform_id=".$platform_id)->queryOne()) {
                            $add_goods_error[] = $goods->goods_name.'已经存在';
                        }else {
                            //插入数据
                            Yii::$app->db->createCommand("insert into goods_platform(goods_id,platform_id) value(".$value['goods_id'].",".$platform_id.") ")->execute();
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

                if (isset($goods)) {

                    if (Yii::$app->db->createCommand("SELECT * FROM goods_platform where goods_id=".$goods_id." and platform_id=".$platform_id)->queryOne()) {
                        $add_goods_error = $goods->goods_name.'已经存在';
                    }else {
                        //插入数据
                        Yii::$app->db->createCommand("insert into goods_platform(goods_id,platform_id) value(".$goods_id.",".$platform_id.") ")->execute();
                    }
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
// 添加主题商品
    public function actionInsertGoods(){
        $goods_id = Yii::$app->request->get('goods_id',0);

        $theme_id = Yii::$app->request->get('order_id',0);
        $search_data = Yii::$app->request->get('search_data',0);

        if (!$theme_id) {
            message::result_json(2,'数据错误');
        }else{
            $theme = Theme::findone($theme_id);
        }
        $add_goods_error = [];
        if ($goods_id) {
            if (is_array($goods_id)) {

                foreach ($goods_id as $key => $value) {

                    $goods = Goods::findone($value);
                    if ($goods) {

                        if (Yii::$app->db->createCommand("SELECT * FROM goods_theme where goods_id=".$value['goods_id']." and theme_id=".$theme_id)->queryOne()) {
                            $add_goods_error[] = $goods->goods_name.'已经存在';
                        }else {
                            //插入数据
                            Yii::$app->db->createCommand("insert into goods_theme(goods_id,theme_id) value(".$value['goods_id'].",".$theme_id.") ")->execute();
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

                if (isset($goods)) {

                    if (Yii::$app->db->createCommand("SELECT * FROM goods_theme where goods_id=".$goods_id." and theme_id=".$theme_id)->queryOne()) {
                        $add_goods_error = $goods->goods_name.'已经存在';
                    }else {
                        //插入数据
                        Yii::$app->db->createCommand("insert into goods_theme(goods_id,theme_id) value(".$goods_id.",".$theme_id.") ")->execute();
                    }
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
    // 添加商品
    // public function actionInsertGoods(){
    //   $goods_id = Yii::$app->request->get('goods_id',0);

    //     $platform_id = Yii::$app->request->get('order_id',0);
    //   $search_data = Yii::$app->request->get('search_data',0);

    //   if (!$platform_id) {
    //     message::result_json(2,'数据错误');
    //   }else{
    //     $platform = Platform::findone($platform_id);
    //   }
    //   $add_goods_error = [];
    //   if ($goods_id) {
    //       if (is_array($goods_id)) {

    //         foreach ($goods_id as $key => $value) {

    //           $goods = Goods::findone($value);
    //           if ($goods) {

    //             if (Yii::$app->db->createCommand("SELECT * FROM goods_platform where goods_id=".$value['goods_id']." and platform_id=".$platform_id)->queryOne()) {
    //               $add_goods_error[] = $goods->goods_name.'已经存在';
    //             }else {
    //               //插入数据
    //               Yii::$app->db->createCommand("insert into goods_platform(goods_id,platform_id) value(".$value['goods_id'].",".$platform_id.") ")->execute();
    //             }
    //           }
    //         }

    //         if (count($add_goods_error) > 0) {
    //           message::result_json(1,$add_goods_error);
    //         }else{
    //           message::result_json(1,'添加成功');
    //         }

    //       }else{
    //         $goods = Goods::findone($goods_id);

    //         if (isset($goods)) {

    //           if (Yii::$app->db->createCommand("SELECT * FROM goods_platform where goods_id=".$goods_id." and platform_id=".$platform_id)->queryOne()) {
    //             $add_goods_error = $goods->goods_name.'已经存在';
    //           }else {
    //             //插入数据
    //             Yii::$app->db->createCommand("insert into goods_platform(goods_id,platform_id) value(".$goods_id.",".$platform_id.") ")->execute();
    //           }
    //         }
    //         if (count($add_goods_error) > 0) {
    //           message::result_json(2,$add_goods_error);
    //         }else{
    //           message::result_json(1,'添加成功');
    //         }
    //       }
    //   }

    //   if ($search_data) {

    //   }

    //   message::result_json(2,'数据错误');
    // }

    // 添加联系人
    public function actionInsertContact($id){
        $contact = new Contact();
        $contact->load(Yii::$app->request->post());



        $contact->belong_id = $id;
        $custom = Custom::findone($contact->belong_id);
        $contact->belong_name = $custom->contract_name;
        $contact->model = 'platform';

        $contact->save(false);

        $ContactExtendInfo = Yii::$app->request->post('ContactExtendInfo');
        if ($ContactExtendInfo['filed_id']) {
            foreach ($ContactExtendInfo['filed_id'] as $key => $value) {
                $contact_extend = new UserExtendInfo();
                $contact_extend->filed_id = $value;
                $contact_extend->filed_value = $ContactExtendInfo['filed_value'][$key];
                $contact_extend->contact_id = $contact->id;
                $contact_extend->save(false);
            }
        }
        Message::result_json(1,'添加成功');
    }

//编辑
    public function actionEditContact($id){
        $this->layout = 'empty';
        $contact_id = Yii::$app->request->get('contact_id',0);
        $contact = Contact::find()->where(['belong_id'=>$id,'id'=>$contact_id])->one();
        return $this->render('create-contact', ['contact'=>$contact,'id'=>$contact->belong_id]);
    }

//
    public function actionUpdateContact($id,$contact_id){
        $contact = Contact::find()->where(['belong_id'=>$id,'id'=>$contact_id])->one();
        $contact->load(Yii::$app->request->post());

        if (strlen($contact->name) <= 2) {
            Message::result_json(2,'请填写联系人名称');
        }

        $contact->belong_id = $id;
        $Custom = Custom::findone($contact->belong_id);
        $contact->belong_name = $Custom->custom_name;
        $contact->model = 'custom';

        $contact->save(false);

        $ContactExtendInfo = Yii::$app->request->post('ContactExtendInfo');
        if ($ContactExtendInfo['filed_id']) {
            foreach ($ContactExtendInfo['filed_id'] as $key => $value) {
                if (isset($ContactExtendInfo['id'][$key]) && $ContactExtendInfo['id'][$key] > 0) {
                    $contact_extend = UserExtendInfo::find()->where(['id'=>$ContactExtendInfo['id'][$key]])->one();
                    $contact_extend = $contact_extend?$contact_extend:new UserExtendInfo();

                    $contact_extend->filed_id = $value;
                    $contact_extend->filed_value = $ContactExtendInfo['filed_value'][$key];
                    $contact_extend->contact_id = $contact->id;
                    $contact_extend->save(false);
                }else{
                    $contact_extend = new UserExtendInfo();
                    $contact_extend->filed_id = $value;
                    $contact_extend->filed_value = $ContactExtendInfo['filed_value'][$key];
                    $contact_extend->contact_id = $contact->id;
                    $contact_extend->save(false);
                }

            }
        }
        Message::result_json(1,'添加成功');
    }

    public function actionDeleteContact($id,$contact_id){
        $Contact = Contact::find()->where(['id'=>$contact_id,'belong_id'=>$id])->one();
        if ($Contact) {
            $Contact->delete();
            message::result_json(1,'删除成功');
        }else{
            message::result_json(2,'数据不存在');
        }
    }
    //添加联系人
    public function actionCreateContact($id){
        $this->layout = 'empty';
        return $this->render('create-contact', ['id'=>$id]);
    }
    // 添加合同
    public function actionCreateContract($id){

        $this->layout = 'empty';
        return $this->render('create-contract', ['id'=>$id]);
    }
    // 添加合同
    public function actionInsertContract($id){

        $contract = new contract();

        $data=Yii::$app->request->post();

        if ($data['id']!='') {
            $contract = contract::findone($data['id']);
        }
        if (empty($data['contract_name'])) {
            message::result_json(2,'合同名称不为空');
        }
        $contract->contract_name=$data['contract_name'];
        $contract->start_time=$data['start_time'];
        $contract->end_time=$data['end_time'];
        $contract->add_user_id=Yii::$app->session['manage_user']['id'];
        $contract->add_user_name=Yii::$app->session['manage_user']['admin_name'];
        $contract->add_time=time();
        $contract->order_id=($id);
        $contract->type=5;
        $contract->save(false);
        // $contract=Yii::$app->request->post();
        // $sql="insert into contract(contract_name,start_time,end_time,add_user_id,add_user_name,add_time) value('".$contract['contract_name']."','".$contract['start_time']."','".$contract['end_time']."',".Yii::$app->session['manage_user']['id'].",'".Yii::$app->session['manage_user']['admin_name']."',".time().") ";

        // $res=Yii::$app->db->createCommand($sql)->execute();

        $upload_result = UploadForm::upload_files();
        if ($upload_result && is_array($upload_result)) {
            if (count($upload_result['file']) > 0) {
                foreach ($upload_result['file'] as $key => $value) {
                    $FileInfo = new FileInfo();
                    $FileInfo->belong_id = $contract->id;
                    $FileInfo->file_path = $value['file_name'];
                    $FileInfo->file_desc = $value['file_desc'];
                    $FileInfo->model = 'contract';
                    $FileInfo->save(false);
                }
            }
        }
        Message::result_json(1,'添加成功');
    }
    //编辑
    public function actionEditContract($id){
        $this->layout = 'empty';
        $contract_id = Yii::$app->request->get('contract_id',0);
        $contract = Contract::find()->where(['order_id'=>$id,'id'=>$contract_id])->one();
        return $this->render('create-contract', ['contract'=>$contract,'id'=>$contract->order_id]);
    }
    // 主题模块
    public function actionCreateTheme($id){

        $this->layout = 'empty';
        return $this->render('create-theme', ['id'=>$id]);
    }
    public function actionInsertTheme($id){

        $theme= new Theme();

        $data=Yii::$app->request->post();
        if ($data['id']!='') {
            $theme = theme::findone($data['id']);
        }
        if (empty($data['theme_name'])) {
            message::result_json(2,'主题名称不为空');
        }
        $theme->theme_name=$data['theme_name'];
        $theme->start_time=$data['start_time'];
        $theme->end_time=$data['end_time'];
        $theme->remark=$data['remark'];
        $theme->add_user_id=Yii::$app->session['manage_user']['id'];
        $theme->add_user_name=Yii::$app->session['manage_user']['admin_name'];
        $theme->add_time=time();
        $theme->belong_id=($id);
        $theme->save(false);
        // $contract=Yii::$app->request->post();
        // $sql="insert into contract(contract_name,start_time,end_time,add_user_id,add_user_name,add_time) value('".$contract['contract_name']."','".$contract['start_time']."','".$contract['end_time']."',".Yii::$app->session['manage_user']['id'].",'".Yii::$app->session['manage_user']['admin_name']."',".time().") ";

        // $res=Yii::$app->db->createCommand($sql)->execute();
        $upload_result = UploadForm::upload_files();
        if ($upload_result && is_array($upload_result)) {
            if (count($upload_result['file']) > 0) {
                foreach ($upload_result['file'] as $key => $value) {
                    $FileInfo = new FileInfo();
                    $FileInfo->belong_id = $theme->id;
                    $FileInfo->file_path = $value['file_name'];
                    $FileInfo->file_desc = $value['file_desc'];
                    $FileInfo->model = 'theme';
                    $FileInfo->save(false);
                }
            }
        }
        Message::result_json(1,'添加成功');
    }
    //编辑
    public function actionEditTheme($id){

        $this->layout = 'empty';
        $theme_id = Yii::$app->request->get('theme_id',0);
        $theme = Theme::find()->where(['belong_id'=>$id,'id'=>$theme_id])->one();
        return $this->render('create-theme', ['theme'=>$theme,'id'=>$id]);
    }
    //添加主题商品
    public function actionAddThemeGoods($id){

        $this->layout = 'empty';
        $theme_id = Yii::$app->request->get('theme_id',0);
        $theme = Theme::find()->where(['belong_id'=>$id,'id'=>$theme_id])->one();
        return $this->render('add-theme-goods', ['theme'=>$theme,'id'=>$id]);
    }
    // 更新商品平台价格
    public function actionUpdateGoodsLabel(){
        $data_type  = trim(Yii::$app->request->get('data_type'));
        $value  = trim(Yii::$app->request->get('value'));
        $id  = Yii::$app->request->get('id');
        $data_id  = Yii::$app->request->get('data_id');

        // $PurchaseGoods = Goods::find()->where(['order_id'=>$id,'id'=>$data_id])->one();
        $PurchaseGoods =Yii::$app->db->createCommand("SELECT * FROM goods_platform where goods_id=".$id." and platform_id=".$data_id)->queryOne();
        if($PurchaseGoods){
            Yii::$app->db->createCommand("UPDATE goods_platform SET platform_price=".$value." where goods_id=".$id." and platform_id=".$data_id)->execute();
            message::result_json(1,'chengg',$value);
        }else{
            message::result_json(2,'没有此记录');
        }
    }
}
