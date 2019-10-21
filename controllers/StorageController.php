<?php

namespace app\controllers;

use Yii;
use yii\helpers\Arrayhelper;

use yii\web\Controller;
use yii\data\Pagination;
use yii\db\Query;
use app\common\models\Stock;
use app\common\models\Config;
use yii\helpers\Url;
use app\includes\Message;
use app\controllers\BaseController;
use app\includes\Common_fun;

class StorageController extends BaseController
{
    public $page_title = '库存查询';
    public $scope = true;
    public $search_allowed = '';

    public function actionIndex()
    {
        if (isset(Yii::$app->session['manage_user']['store_id']) && Yii::$app->session['manage_user']['store_id'] > 0) {
            $init_condition = [['store_id' => Yii::$app->session['manage_user']['store_id']]];
        }
        $goods_name  = trim(Yii::$app->request->get('goods_name'));
        $store_id  = is_numeric(Yii::$app->request->get('store_id'))?Yii::$app->request->get('store_id'):0;
        $type = is_numeric(Yii::$app->request->get('type')) ? Yii::$app->request->get('type') : 0;
        $list=array();
        $query = new \yii\db\Query();
        $sortby = !empty(trim(Yii::$app->request->get('sortby')))?trim(Yii::$app->request->get('sortby')):'goods_id';
        $order = trim(Yii::$app->request->get('order')) == 'SORT_ASC'?SORT_ASC:SORT_DESC;
        if ($type == 2) {
            $s = new Config();
            $settime = $s->getName();
            $stock = new Stock();
            $data = $stock->getName();
            foreach ($data as $key => $val) {
                if ($val['out_time']) {
                    $time=ceil((time() - $val['out_time']) / 86400);
                    if ($time>=$settime){
//                        $data[$key]['time']=$time;
//                        $list[$key]=$data[$key];
                       /* $data = $query->select('s.id,g.goods_name,g.goods_sn,g.isbn,s.number,se.store_name,g.goods_id')
                            ->from('stock s')
                            ->leftjoin('goods as g','g.goods_id=s.goods_id')
                            ->leftjoin('store as se',' se.id=s.store_id')
                            ->where(['s.id='.$val['id']]);*/
                        $data = Yii::$app->db->createCommand("select s.id,g.goods_name,g.goods_sn,g.isbn,s.number,se.store_name,g.goods_id from stock s  left join goods g on g.goods_id=s.goods_id  left join store se on se.id=s.store_id where s.id=". $val['id'] .' GROUP BY s.goods_id')->queryAll();

                        $list[$key]=$data[0];
                    }
                } else {
//                    $data[$key]['time']='';
                   /* $list = $query->select('s.id,g.goods_name,g.goods_sn,g.isbn,s.number,se.store_name,g.goods_id')
                        ->from('stock as s')
                        ->leftjoin('goods as g','g.goods_id=s.goods_id')
                        ->leftjoin('store as se',' se.id=s.store_id')
                        ->where(['s.id='.$val['id']]);*/
                    $time=ceil((time() - $val['add_time']) / 86400);
                    if ($time>=$settime) {
                        $data = Yii::$app->db->createCommand("select s.id,g.goods_name,g.goods_sn,g.isbn,s.number,se.store_name,g.goods_id from stock s  left join goods g on g.goods_id=s.goods_id  left join store se on se.id=s.store_id where s.id=" . $val['id'] . '  GROUP BY s.goods_id')->queryAll();
                        $list[$key] = $data[0];
                    } } }
        }else{
            $list= $query->select('s.id,g.goods_name,g.goods_sn,g.isbn,s.number,se.store_name,g.goods_id')
                ->from('stock s')
                ->leftjoin('goods as g','g.goods_id=s.goods_id')
                ->leftjoin('store as se',' se.id=s.store_id')
                ->groupBy('s.goods_id');
            if($goods_name){
                $list = $query->andwhere(['like','g.goods_name',$goods_name]);
            }
            if($store_id){
                $list = $query->andwhere(['se.id'=>$store_id]);
            }
            $countQuery = clone $query;
            $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>15,'pageSizeLimit'=>1]);
            $list = $query->offset($pages->offset)
                ->orderby([$sortby=>$order])
                ->limit($pages->limit)
                ->all();
        }
        foreach ($list as $key=>$val){
            $data = Yii::$app->db->createCommand("select s.goods_id,s.out_time,s.add_time,s.number,se.store_name from stock s left join store se on se.id=s.store_id where s.number>0 and goods_id=".$val['goods_id'])->queryAll();

           foreach ($data as $k=>$v){
               if ($v['out_time']>0){
                   $data[$k]['time']=ceil((time() - $v['out_time']) / 86400);
               }else{
                   $data[$k]['time']=ceil((time() - $v['add_time'])/86400);
               }
           }
            $list[$key]['data']=$data;

        }

        return $this->render('index', ['init_condition' => $init_condition,'data_list'=>$list,'type'=>$type,'pages' => $pages]);
    }


    }
