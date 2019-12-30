<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\LinkPager;

use app\common\config\lang_config;
use app\common\config\lang_value_config;


$this->title = '客户方案';
$this->params['breadcrumbs'][] = ['label' => '客户方案', 'url' => ['index']];
$this->params['breadcrumbs'][] = $custom_order->order_name . '-' . $custom_order->order_sn;
?>
<?= Html::cssFile('@web/css/token_input/token-input-facebook.css') ?>
<?= Html::cssFile('@web/css/plugins/switchery/switchery.css') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.min.js') ?>

<?= app\common\widgets\OrderTitle::widget([
    'model' => 'app\common\models\CustomOrder',
    'model_data' => $custom_order,
    'controller_id' => $this->context->id,
    'id' => $id,
    'model_name' => 'custom-order',
    'label_arr' => [
        'order_name' => '',
        'custom&custom_name' => ['link' => true, 'id' => 'custom', 'url' => Url::to(["custom/view"])],
        'remark' => ''
    ],
    'status_label' => 'custom_order_status',
])
?>

<div style="overflow:auto;width:100%;margin-bottom:5em;" id="goods_list_block">
    <div class="ibox " style="width:2730px">
        <div class="ibox-content" style="padding:10px 5px 5px 10px;">
            <a class="btn btn-primary btn-sm" id="create_goods">添加新商品</a>
            <a class="btn btn-primary btn-sm" id="create_val">添加新字段</a>
            <form name="" id="goods_list_form">
                <table class="table table-hover dataTable">
                    <thead id="goods_list_thead" style="width:2730px">
                        <tr>
                            <th width="50px;" data-fixed="true">
                                <div class="checkbox i-checks">
                                    <label class="">
                                        <div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" id="check_all" value="" style="position: absolute; opacity: 0;">
                                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                        </div>
                                    </label>
                                </div>
                            </th>
                            <th data-fixed="true" align="center" class="" data-type="goods_img" width="180px" style="width:80px">
                                商品图片
                            </th>
                            <th data-fixed="true" align="center" class="" data-type="goods_name" width="180px" style="width:180px">
                                商品名称
                            </th>
                            <th data-fixed="true" align="center" class="" data-type="goods_sn" width="100px" style="width:100px">
                                商品型号
                            </th>
                            <th align="center" class="" data-type="market_price" width="50px" style="width:50px">
                                市场价
                            </th>
                            <th align="center" class="sorting data_sort" data-type="number" width="60px" style="width:60px">
                                数量 <span style="color:red;">*</span></th>
                            <th align="center" class="" data-type="goods_store_info" width="90px" style="width:90px">
                                库存状态
                            </th>
                            <th align="center" class="" data-type="ppt_price" width="70px" style="width:70px">
                                PPT报价
                            </th>
                            <th align="center" class="" data-type="supplier_name" width="140px" style="width:140px">
                                供货商名称--价格
                            </th>
                            <th align="center" class="" data-type="supplier_price" width="120px" style="width:120px">
                                工厂最终报价 <span style="color:red;">*</span></th>
                            <th align="center" class="" data-type="sale_price" width="100px" style="width:100px">
                                客户供货价 <span style="color:red;">*</span></th>
                            <th align="center" class="<?
                                                        if ($vv['sort_able']) {
                                                            if ($search_data['sortby'] == $kk) {
                                                                if ($search_data['order'] == 4) {
                                                                    echo 'sorting_asc data_sort';
                                                                } else {
                                                                    echo 'sorting_desc data_sort';
                                                                }
                                                            } else {
                                                                echo 'sorting data_sort';
                                                            }
                                                        } ?>" data-type="supplier_number" width="70px" style="width:70px">
                                工厂库存 </th>
                            <th align="center" class="<?
                                                        if ($vv['sort_able']) {
                                                            if ($search_data['sortby'] == $kk) {
                                                                if ($search_data['order'] == 4) {
                                                                    echo 'sorting_asc data_sort';
                                                                } else {
                                                                    echo 'sorting_desc data_sort';
                                                                }
                                                            } else {
                                                                echo 'sorting data_sort';
                                                            }
                                                        } ?>" data-type="limit_price" width="70px" style="width:70px">
                                项目限价 </th>
                            <th align="center" class="<?
                                                        if ($vv['sort_able']) {
                                                            if ($search_data['sortby'] == $kk) {
                                                                if ($search_data['order'] == 4) {
                                                                    echo 'sorting_asc data_sort';
                                                                } else {
                                                                    echo 'sorting_desc data_sort';
                                                                }
                                                            } else {
                                                                echo 'sorting data_sort';
                                                            }
                                                        } ?>" data-type="jd_price" width="70px" style="width:70px">
                                京东价格 </th>
                            <th align="center" class="<?
                                                        if ($vv['sort_able']) {
                                                            if ($search_data['sortby'] == $kk) {
                                                                if ($search_data['order'] == 4) {
                                                                    echo 'sorting_asc data_sort';
                                                                } else {
                                                                    echo 'sorting_desc data_sort';
                                                                }
                                                            } else {
                                                                echo 'sorting data_sort';
                                                            }
                                                        } ?>" data-type="dangdang_price" width="70px" style="width:70px">
                                当当价格 </th>
                            <th align="center" class="<?
                                                        if ($vv['sort_able']) {
                                                            if ($search_data['sortby'] == $kk) {
                                                                if ($search_data['order'] == 4) {
                                                                    echo 'sorting_asc data_sort';
                                                                } else {
                                                                    echo 'sorting_desc data_sort';
                                                                }
                                                            } else {
                                                                echo 'sorting data_sort';
                                                            }
                                                        } ?>" data-type="tmall_price" width="70px" style="width:70px">
                                天猫价格</th>
                            <th align="center" class="<?
                                                        if ($vv['sort_able']) {
                                                            if ($search_data['sortby'] == $kk) {
                                                                if ($search_data['order'] == 4) {
                                                                    echo 'sorting_asc data_sort';
                                                                } else {
                                                                    echo 'sorting_desc data_sort';
                                                                }
                                                            } else {
                                                                echo 'sorting data_sort';
                                                            }
                                                        } ?>" data-type="taobao_price" width="70px" style="width:70px">
                                淘宝价格 </th>
                            <th align="center" class="" data-type="finalCost" width="70px" style="width:70px">
                                综合成本
                            </th>
                            <th align="center" class="" data-type="finalCostTotal" width="70px" style="width:70px">
                                成本小计
                            </th>
                            <th align="center" class="" data-type="faxPoint" width="60px" style="width:60px">
                                税费
                            </th>
                            <th align="center" class="" data-type="consultFee" width="130px" style="width:130px">
                                <span class="table_th_tips" data-toggle="tooltip" data-placement="top" data-original-title="填写比率，根据(供货商价格*利润系数)得出结果"> 利润系数参考值 </span>
                            </th>

                            <th align="center" class="" data-type="profit" width="60px" style="width:60px">
                                毛利
                            </th>
                            <th align="center" class="" data-type="profitRate" width="60px" style="width:60px">
                                毛利率
                            </th>
                            <th align="center" class="" data-type="profitTotal" width="80px" style="width:80px">
                                毛利小计
                            </th>
                            <th align="center" class="" data-type="saleTotal" width="80px" style="width:80px">
                                售价小计
                            </th>
                            <th align="center" class="" data-type="shipping_fee" width="60px" style="width:60px">
                                运费 <span style="color:red;">*</span></th>
                            <th align="center" class="" data-type="materiel_cost" width="80px" style="width:80px">
                                物料消耗
                            </th>
                            <th align="center" class="" data-type="platformFee" width="80px" style="width:80px">
                                <span class="table_th_tips" data-toggle="tooltip" data-placement="top" data-original-title="填写比率，根据(售价*反点比率)得出结果"> 平台扣点 </span>
                            </th>
                            <th align="center" class="" data-type="tranformFee" width="80px" style="width:80px">
                                <span class="table_th_tips" data-toggle="tooltip" data-placement="top" data-original-title="填写比率，根据(售价*反点比率)得出结果"> 物流反点 </span>
                            </th>
                            <!--   <th align="center" class="" data-type="other_cost" width="60px" style="width:60px">
                            --其他成本
                        </th> -->
                            <th align="center" class="" data-type="shipping_place" width="60px" style="width:60px">
                                发货地
                            </th>
                            <th align="center" class="" data-type="huoqi" width="70px" style="width:70px">
                                货期
                            </th>
                            <th align="center" class="sorting data_sort" data-type="is_priced" width="70px" style="width:70px">
                                盖章报价
                            </th>
                            <th align="center" class="" data-type="shipping_to_place" width="70px" style="width:70px">
                                配送地点
                            </th>
                            <th align="center" class="sorting data_sort" data-type="is_need_temp" width="70px" style="width:70px">
                                需要样板
                            </th>
                            <th align="center" class="" data-type="remark" width="100px" style="width:100px">
                                备注
                            </th>
                            <?php
                            if (isset($vallist)) {
                                foreach ($vallist as $key => $value) {
                            ?>
                                    <th align="center" class="" data-type="<?= $value['val_name_en'] ?>" width="100px" style="width:100px">
                                        <?= $value['val_name'] ?>
                                    </th>

                            <?php
                                }
                            }
                            ?>
                            <th align="center" width="100px" style="width:100px">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($custom_order_goods)) {
                            foreach ($custom_order_goods as $key => $val) {

                        ?>
                                <tr id="goods_row_<?= $val['id'] ?>">

                                    <td>
                                        <div class="checkbox i-checks">
                                            <label>
                                                <div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" class="goods_ids" name="goods_id[]" value="7169" style="position: absolute; opacity: 0;">
                                                    <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                                </div>
                                                <?php $key + 1 ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="goods_img_<?= $val['id'] ?>" title="">
                                            <?php
                                            if (isset($val['goods_img'])) {
                                            ?>

                                                <img src="/<?= $val['goods_img'] ?>" width=30 />
                                            <?php

                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="goods_name_<?= $val['id'] ?>" title="">
                                            <?= $val['goods_name'] ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="goods_sn_<?= $val['id'] ?>" title="">
                                            <?= $val['goods_sn'] ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="market_price_<?= $val['id'] ?>" title="">
                                            <div class="lable_edit" data-id="<?= $val['id'] ?>" data-type="market_price"><?= $val['market_price'] ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="number_<?= $val['id'] ?>" title="">
                                            <div class="lable_edit" data-id="<?= $val['id'] ?>" data-type="number"><?= $val['number'] ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="goods_store_info_<?= $val['id'] ?>" title="">
                                            <?= $val['store_num'] ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="lable_edit" data-type="ppt_price" data-id="<?= $val['id'] ?>" id="ppt_price_<?= $val['id'] ?>" title=""><?= $val['ppt_price'] ?></div>
                                    </td>

                                    <td>
                                        <div id="supplier_name_<?= $val['id'] ?>" title="">
                                            <select name="supplier_id" id=""><?php
                                                                                $supplier_price = '';
                                                                                if (isset($val['supplier'])) {
                                                                                    foreach ($val['supplier'] as $key => $value) {
                                                                                        if ($key == 0) {
                                                                                            $supplier_price = $value['supplier_price'];
                                                                                        }
                                                                                        if (isset($val['supplier_id'])) {
                                                                                            if ($val['supplier_id'] == $value['supplier_id']) { ?><option value="<?= $value['id'] ?>" selected><?= $value['supplier_name'] ?>--<?= $value['supplier_price'] ?></option>

                                                        <?php
                                                                                                # code...
                                                                                            }
                                                                                        }
                                                        ?><option value="<?= $value['id'] ?>"><?= $value['supplier_name'] ?>--<?= $value['supplier_price'] ?></option><?php }
                                                                                                                                                                }
                                                                                                                                                                        ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="supplier_price_<?= $val['id'] ?>" title="">
                                            <div class="lable_edit" data-id="<?= $val['id'] ?>" data-type="supplier_price"><?php
                                                                                                                            if ($val['supplier_price'] == '') {
                                                                                                                                echo $supplier_price;
                                                                                                                            } else {
                                                                                                                                echo $val['supplier_price'];
                                                                                                                            } ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="sale_price_<?= $val['id'] ?>" title="">
                                            <div class="lable_edit" data-id="<?= $val['id'] ?>" data-type="sale_price"><?= $val['sale_price'] ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="supplier_number_<?= $val['id'] ?>"" title="">	
                                        <div class=" lable_edit" data-id="<?= $val['id'] ?>"" data-type=" supplier_number"><?= $val['supplier_number'] ?></div>
        </div>
        </td>
        <td>
            <div id="limit_price_<?= $val['id'] ?>" title="">
                <div class="lable_edit" data-id="<?= $val['id'] ?>" data-type="limit_price"><?= $val['limit_price'] ?></div>
            </div>
        </td>
        <td>
            <div id="jd_price_<?= $val['id'] ?>" title="">
                <div class="lable_edit" data-id="<?= $val['id'] ?>" data-type="jd_price"><?= $val['jd_price'] ?></div>
            </div>
        </td>
        <td>
            <div id="dangdang_price_<?= $val['id'] ?>" title="">
                <div class="lable_edit" data-id="<?= $val['id'] ?>" data-type="dangdang_price"><?= $val['dangdang_price'] ?></div>
            </div>
        </td>
        <td>
            <div id="tmall_price_<?= $val['id'] ?>" title="">
                <div class="lable_edit" data-id="<?= $val['id'] ?>" data-type="tmall_price"><?= $val['tmall_price'] ?></div>
            </div>
        </td>
        <td>
            <div id="taobao_price_<?= $val['id'] ?>" title="">
                <div class="lable_edit" data-id="<?= $val['id'] ?>" data-type="taobao_price"><?= $val['taobao_price'] ?></div>
            </div>
        </td>

        <td>
            <div id="finalCost_<?= $val['id'] ?>" title="">
                <?= $val['finalCost'] ?>
            </div>
        </td>
        <td>
            <div id="finalCostTotal_<?= $val['id'] ?>" title="">
                <?= $val['finalCostTotal'] ?>
            </div>
        </td>
        <td>
            <div id="faxPoint_<?= $val['id'] ?>" title="">
                <?= $val['faxPoint'] ?>
            </div>
        </td>
        <td>
            <div id="consultFee_<?= $val['id'] ?>" title="填写比率，根据(供货商价格*利润系数)得出结果">
                <div class="lable_edit" data-id="<?= $val['id'] ?>" data-type="consult"><?= $val['consultFee'] ?></div>
            </div>
        </td>

        <td>
            <div id="profit_<?= $val['id'] ?>" title="">
                <?= $val['profit'] ?>
            </div>
        </td>
        <td>
            <div id="profitRate_<?= $val['id'] ?>" title="">
                <?= $val['profitRate'] ?>
            </div>
        </td>
        <td>
            <div id="profitTotal_<?= $val['id'] ?>" title="">
                <?= $val['profitTotal'] ?>
            </div>
        </td>
        <td>
            <div id="saleTotal_<?= $val['id'] ?>" title="">
                <?= $val['saleTotal'] ?>
            </div>
        </td>
        <td>
            <div id="shipping_fee_<?= $val['id'] ?>" title="">
                <div class="lable_edit" data-id="<?= $val['id'] ?>" data-type="shipping_fee"><?= $val['shipping_fee'] ?></div>
            </div>
        </td>
        <td>
            <div id="materiel_cost_<?= $val['id'] ?>" title="">
                <div class="lable_edit" data-id="<?= $val['id'] ?>" data-type="materiel_cost"><?= $val['materiel_cost'] ?></div>
            </div>
        </td>
        <td>
            <div id="platformFee_<?= $val['id'] ?>" title="填写比率，根据(售价*反点比率)得出结果">
                <div class="lable_edit" data-id="<?= $val['id'] ?>" data-type="platform_rate"><?= $val['platformFee'] ?></div>
            </div>
        </td>
        <td>
            <div id="tranformFee_<?= $val['id'] ?>" title="填写比率，根据(售价*反点比率)得出结果">
                <div class="lable_edit" data-id="<?= $val['id'] ?>" data-type="tranform_rate"><?= $val['tranformFee'] ?></div>
            </div>
        </td>
        <!--   <td>
                                    <div id="other_cost_<?= $val['id'] ?>" title="">
                                        <div class="lable_edit" data-id="<?= $val['id'] ?>" data-type="other_cost"><?= $val['other_cost'] ?></div>
                                    </div>
                                </td> -->
        <td>
            <div id="shipping_place_<?= $val['id'] ?>" title="">
                <div class="lable_edit" data-id="<?= $val['id'] ?>" data-type="shipping_place"><?= $val['shipping_place'] ?></div>
            </div>
        </td>
        <td>
            <div id="huoqi_<?= $val['id'] ?>" title="">
                <div class="lable_edit" data-id="<?= $val['id'] ?>" data-type="huoqi"><?= $val['huoqi'] ?></div>
            </div>
        </td>
        <td>
            <div id="is_priced_<?= $val['id'] ?>" title="">
                <input type="checkbox" class="js-switch" data-url="" data-type="is_priced" data-id="<?= $val['id'] ?>" id="is_priced_<?= $val['id'] ?>" data-switchery="true" style="display: none;"><span class="switchery" id="is_priced_<?= $val['id'] ?>" style="box-shadow: rgb(223, 223, 223) 0px 0px 0px 0px inset; border-color: rgb(223, 223, 223); transition: border 0.4s ease 0s, box-shadow 0.4s ease 0s;"><small style="left: 0px; transition: left 0.2s ease 0s;">

                    </small></span>
            </div>
        </td>
        <td>
            <div id="shipping_to_place_<?= $val['id'] ?>" title="">
                <div class="lable_edit" data-id="<?= $val['id'] ?>" data-type="shipping_to_place">

                    <?= $val['shipping_to_place'] ?>
                </div>
            </div>
        </td>
        <td>
            <div id="is_need_temp_<?= $val['id'] ?>" title="">
                <input type="checkbox" class="js-switch" data-url="" data-type="is_need_temp" data-id="<?= $val['id'] ?>" id="is_need_temp_<?= $val['id'] ?>" data-switchery="true" style="display: none;"><span class="switchery" id="is_need_temp_<?= $val['id'] ?>" style="box-shadow: rgb(223, 223, 223) 0px 0px 0px 0px inset; border-color: rgb(223, 223, 223); transition: border 0.4s ease 0s, box-shadow 0.4s ease 0s;"><small style="left: 0px; transition: left 0.2s ease 0s;"></small></span>
            </div>
        </td>
        <td>
            <div id="remark_<?= $val['id'] ?>" title="">
                <div class="lable_edit" data-id="<?= $val['id'] ?>" data-type="remark"><?= $val['remark'] ?></div>
            </div>
        </td>
        <?php
                                // 自定义字段
                                if (isset($vallist)) {
                                    foreach ($vallist as $key => $value) {
        ?>
                <td>
                    <div id="<?= $value['val_name_en'] ?>_<?= $val['goods_id'] ?>" title="">
                        <div class="lable_edit" data-id="<?= $val['id'] ?>" data-val-id="<?= $value['id'] ?>" data-val-type="<?= $value['val_name_en'] ?>"><?php
                                                                                                                                                            if (isset($val['val'])) {
                                                                                                                                                                foreach ($val['val'] as $ke => $v) {
                                                                                                                                                                    if ($v['val_id'] == $value['id']) { ?><?= $v['val']; ?><?php }
                                                                                                                                                                                                                    }
                                                                                                                                                                                                                }

                                                                                                                                                                                                                            ?></div>
                    </div>
                </td>
        <?php
                                    }
                                }
        ?>


        <td>
            <a class="delete_goods" data-id="<?= $val['id'] ?>" action="delete-goods" href="javascript:void();">
                <span class="glyphicon glyphicon-trash"></span>
                删除 </a>
        </td>
        </tr>
        <tr>
            <td>
            </td>


            <td>
                <div class="goods_name_total">
                </div>
            </td>

            <td>
                <div class="goods_sn_total">
                </div>
            </td>

            <td>
                <div class="market_price_total">
                </div>
            </td>

            <td>
                <div class="number_total">
                    0 </div>
            </td>

            <td>
                <div class="goods_store_info_total">
                </div>
            </td>

            <td>
                <div class="ppt_price_total">
                </div>
            </td>

            <td>
                <div class="supplier_price_total">
                </div>
            </td>

            <td>
                <div class="supplier_name_total">
                </div>
            </td>

            <td>
                <div class="finalCost_total">
                </div>
            </td>

            <td>
                <div class="finalCostTotal_total">
                    0</div>
            </td>

            <td>
                <div class="faxPoint_total">
                </div>
            </td>

            <td>
                <div class="consultFee_total">
                </div>
            </td>

            <td>
                <div class="sale_price_total">
                </div>
            </td>

            <td>
                <div class="profit_total">
                </div>
            </td>

            <td>
                <div class="profitRate_total">
                    0 </div>
            </td>

            <td>
                <div class="profitTotal_total">
                    0 </div>
            </td>

            <td>
                <div class="saleTotal_total">
                    0 </div>
            </td>

            <td>
                <div class="shipping_fee_total">
                </div>
            </td>

            <td>
                <div class="materiel_cost_total">
                </div>
            </td>

            <td>
                <div class="platformFee_total">
                </div>
            </td>

            <td>
                <div class="tranformFee_total">
                </div>
            </td>

            <td>
                <div class="other_cost_total">
                </div>
            </td>

            <td>
                <div class="shipping_place_total">
                </div>
            </td>

            <td>
                <div class="huoqi_total">
                </div>
            </td>

            <td>
                <div class="is_priced_total">
                </div>
            </td>

            <td>
                <div class="shipping_to_place_total">
                </div>
            </td>

            <td>
                <div class="is_need_temp_total">
                </div>
            </td>

            <td>
                <div class="remark_total">
                </div>
            </td>

            <td>
            </td>
        </tr>

    <?php
                            }
                        } else {
    ?>
    <tr>
        <td colspan="30" align="center">单据下没有数据</td>
    </tr>

<?php
                        }
?>


</tbody>
</table>
</form>



    </div>
</div>
</div>

<?= app\common\widgets\OperateBar::widget([
    'create' => ['label_name' => '添加商品', 'id' => 'create_custom_order_goods', 'type' => 'js', 'url' => Url::to(["custom-order/create-goods", "order_id" => $custom_order->id])],
    // 'export'=>['label_name'=>'导出','module_name'=>'custom-order','type'=>'detail','id'=>$custom_order->id],
    'export_ppt' => ['label_name' => '导出ppt', 'module_name' => 'ExportPpt', 'type' => 'detail', 'id' => $custom_order->id],
    'admit_process' => ['controller' => $this->context->id, 'url' => Url::to(['/' . $this->context->id . '/admit', 'id' => $custom_order->id]), 'model' => $custom_order, 'status_label' => 'custom_order_status'],
    'other_btn' => [
        ['label_name' => '修改历史', 'id' => 'remend_history'],
        ['label_name' => '询价报备', 'url' => Url::to(["custom-order/create-ask-price", "id" => $custom_order->id]), 'id' => 'create_ask_order', 'type' => 'js'],
        ['label_name' => '生成销售单', 'url' => Url::to(["custom-order/create-sell-order", "id" => $custom_order->id]), 'id' => 'create_sell_order', 'type' => 'js'],
    ],
    'refresh' => ['label_name' => '刷新', 'type' => 'js', 'id' => '', 'icon' => 'plus'],
    'backup' => ['label_name' => '返回', 'url' => Url::to(['/custom-order/']),],
])
?>

<script type="text/javascript">
    $("#check_all").on('ifChecked', function(event) {
        $(".goods_ids").iCheck('check');
    });

    $("#check_all").on('ifUnchecked', function(event) {
        $(".goods_ids").iCheck('uncheck');
    });

    $('.goods_ids').on('ifChecked', function(event) {
        $(this).parent().parent().parent().parent().parent().css('background-color', '#f5f5f5')
    });
    $('.goods_ids').on('ifUnchecked', function(event) {
        $(this).parent().parent().parent().parent().parent().css('background-color', '')
    });

    $(".data_sort").click(function() {
        var sortby = $(this).attr('data-type');
        var order = $(this).attr("class") == 'sorting_desc data_sort' ? 'SORT_ASC' : 'SORT_DESC';
        var to_url = '/custom-order/view?id=83';
        to_url = create_url(to_url);
        window.location.href = to_url + 'sortby=' + sortby + '&order=' + order;
    });

    $(".delete_goods").click(function() {
        if (confirm('要删除该记录吗？')) {
            var id = <?= $id ?>;
            var data_id = $(this).attr('data-id');
            var action = $(this).attr('action');
            $.get("/custom-order/" + action, {
                id: id,
                data_id: data_id
            }, function(result) {
                if (result.error == 1) {
                    $("#goods_row_" + data_id).remove();
                } else {
                    layer.msg(result.message);
                }
            }, 'json');
        }
    });

    $(".lable_edit").hover(
        function() {
            $(this).addClass('lable_edit_over');
        },
        function() {
            $(this).removeClass('lable_edit_over');
        }
    );

    $(".goods_list_change").change(function() {
        var id = <?= $id ?>;
        var value = $(this).val();
        var data_id = $(this).attr("data-id");
        var data_type = $(this).attr("data-type");
        var target = $(this);
        $.get('/custom-order/update-goods-label', {
            value: value,
            id: id,
            data_id: data_id,
            data_type: data_type
        }, function(result) {
            if (result.error == 1) {
                layer.msg(result.message);
            } else {
                layer.msg(result.message, function() {});
            }
        }, 'json');
    })


    $(".lable_edit").click(
        function() {
            var id = <?= $id ?>;
            var data_id = $(this).attr("data-id");
            var data_type = $(this).attr("data-type");
            var data_val_type = $(this).attr("data-val-type");
            var data_val_id = $(this).attr("data-val-id");
            var target = $(this);
            if ($(this).children("input").length > 0) {} else {
                var ima_code = $(this).text();
                var input_html = '<input type="text" value="' + ima_code + '" style="width:100%;" class="edit_input" />';
                $(this).html(input_html);
                $('.edit_input').focus();
                $('.edit_input').select();
            }

            function calculate_value(calculate_value) {
                for (var i in calculate_value) {
                    var inner_div = $("#" + calculate_value[i].label_name + '_' + data_id).children();
                    console.log(inner_div);
                    if (inner_div.length > 0) {
                        inner_div.html(calculate_value[i].new_value);
                    } else {
                        $("#" + calculate_value[i].label_name + '_' + data_id).html(calculate_value[i].new_value);
                    }
                }
            }

            $('.edit_input').blur(function() {
                var value = $(this).val();
                if (value == ima_code) {
                    target.html(ima_code);
                    return false;
                }
                $.get('/custom-order/update-goods-label', {
                    value: value,
                    id: id,
                    data_id: data_id,
                    data_type: data_type,
                    data_val_type: data_val_type,
                    data_val_id: data_val_id

                }, function(result) {
                    if (result.error == 1) {
                        target.html(result.content);
                        if (result.calculate_value) {
                            calculate_value(result.calculate_value);
                        };
                    } else if (result.error == 3) {
                        target.html(ima_code);
                    } else {
                        target.html(ima_code);
                        layer.msg(result.message, function() {});
                    }
                }, 'json');
            });

            $('.edit_input').keydown(function(event) {
                if (event.keyCode == 13) {
                    event.stopPropagation();
                    event.preventDefault();
                    var value = $(this).val();
                    if (value == ima_code) {
                        target.html(ima_code);
                        return false;
                    }
                    $.get('/custom-order/update-goods-label', {
                        value: value,
                        id: id,
                        data_id: data_id,
                        data_type: data_type,
                        data_val_type: data_val_type,
                        data_val_id: data_val_id

                    }, function(result) {
                        if (result.error == 1) {
                            target.html(result.content);
                            if (result.calculate_value) {
                                calculate_value(result.calculate_value);
                            };
                        } else if (result.error == 3) {
                            target.html(ima_code);
                        } else {
                            target.html(ima_code);
                            layer.msg(result.message, function() {});
                        }
                    }, 'json');
                }
            });

        }
    );


    $(".lable_editv").click(
        function() {
            var id = $id;
            var data_id = $(this).attr("data-id");
            var data_type = $(this).attr("data-type");
            var data_val_type = $(this).attr("data-val-type");
            var data_val_id = $(this).attr("data-val-id");

            var target = $(this);
            if ($(this).children("input").length > 0) {} else {
                var ima_code = $(this).text();
                var input_html = '<input type="text" value="' + ima_code + '" style="width:100%;" class="edit_input" />';
                $(this).html(input_html);
                $('.edit_input').focus();
                $('.edit_input').select();
            }

            function calculate_value(calculate_value) {
                for (var i in calculate_value) {
                    var inner_div = $("#" + calculate_value[i].label_name + '_' + data_id).children();
                    console.log(inner_div);
                    if (inner_div.length > 0) {
                        inner_div.html(calculate_value[i].new_value);
                    } else {
                        $("#" + calculate_value[i].label_name + '_' + data_id).html(calculate_value[i].new_value);
                    }
                }
            }

            $('.edit_input').blur(function() {
                var value = $(this).val();
                if (value == ima_code) {
                    target.html(ima_code);
                    return false;
                }
                $.get('/custom-order/update-goods-val-label', {
                    value: value,
                    id: id,
                    data_id: data_id,
                    data_type: data_type,
                    data_val_type: data_val_type,
                    data_val_id: data_val_id
                }, function(result) {
                    if (result.error == 1) {
                        target.html(result.content);
                        if (result.calculate_value) {
                            calculate_value(result.calculate_value);
                        };
                    } else if (result.error == 3) {
                        target.html(ima_code);
                    } else {
                        target.html(ima_code);
                        layer.msg(result.message, function() {});
                    }
                }, 'json');
            });

            $('.edit_input').keydown(function(event) {
                if (event.keyCode == 13) {
                    event.stopPropagation();
                    event.preventDefault();
                    var value = $(this).val();
                    if (value == ima_code) {
                        target.html(ima_code);
                        return false;
                    }
                    $.get('/custom-order/update-goods-val-label', {
                        value: value,
                        id: id,
                        data_id: data_id,
                        data_type: data_type,
                        data_val_type: data_val_type,
                        data_val_id: data_val_id

                    }, function(result) {
                        if (result.error == 1) {
                            target.html(result.content);
                            if (result.calculate_value) {
                                calculate_value(result.calculate_value);
                            };
                        } else if (result.error == 3) {
                            target.html(ima_code);
                        } else {
                            target.html(ima_code);
                            layer.msg(result.message, function() {});
                        }
                    }, 'json');
                }
            });

        }
    );

    $(".view_label_content").click(function() {
        var view_url = $(this).attr('data-url');
        var view_id = $(this).attr('data-id');
        layer.open({
            type: 2,
            title: '查看',
            area: ['90%', '90%'], //宽高
            maxmin: true,
            content: view_url + '?id=' + view_id
        });
    })

    $(function() {
        $("[data-toggle='tooltip']").tooltip();
    });

    $(document).ready(function() {

        $(".i-checks").iCheck({
            checkboxClass: "icheckbox_square-green",
            radioClass: "iradio_square-green",
        })
    });
</script>

<script type="text/javascript">
    var ori_xx = 0
    var ori_yy = 0
    var move_goods_list = function(e) {
        var new_xx = e.originalEvent.x || e.originalEvent.layerX || 0;
        var new_yy = e.originalEvent.y || e.originalEvent.layerY || 0;

        var move_px_x = (new_xx - ori_xx) * 2;
        var move_px_y = (new_yy - ori_yy) * 2;
        if (ori_xx > 0 && e.data.type == 'x') {
            $("#goods_list_block").scrollLeft($("#goods_list_block").scrollLeft() + move_px_x)
            //$("body").scrollTop($("body").scrollTop() + move_px_y)
        }

        if (ori_yy > 0 && e.data.type == 'y') {
            $("body").scrollTop($("body").scrollTop() + move_px_y)
        }

        ori_xx = new_xx;
        ori_yy = new_yy;

        console.log(new_xx + '-------' + new_yy);
    };

    document.onkeydown = function(event) {
        if (event.target.nodeName == 'TEXTAREA' || event.target.nodeName == 'INPUT') {
            return;
        };

        if (event.keyCode == 32) {
            event.preventDefault();
            $("body").css('cursor', 'move');
            var ori_xx = 0;
            var ori_yy = 0;
            $('#goods_list_block').on('mousemove', {
                type: 'x'
            }, move_goods_list);
        };

        if (event.keyCode == 16) {
            event.preventDefault();
            $("body").css('cursor', 'move');
            var ori_xx = 0;
            var ori_yy = 0;
            $('#goods_list_block').on('mousemove', {
                type: 'y'
            }, move_goods_list);
        };



    }

    document.onkeyup = function(event) {
        if (event.target.nodeName == 'TEXTAREA' || event.target.nodeName == 'INPUT') {
            return;
        };
        /* Act on the event */
        if (event.keyCode == 32 || event.keyCode == 16) {
            event.preventDefault();
            $("body").css('cursor', 'auto');
            $('#goods_list_block').off()
            ori_xx = 0
            ori_yy = 0
        };

    }

    //检查选择的商品
    function get_checked_goods() {
        var goods_id_arr = new Array();
        $(".goods_ids").each(function(i) {
            if ($(this).prop('checked')) {
                goods_id_arr.push($(this).val());
            };
        })
        if (goods_id_arr.length > 0) {
            return goods_id_arr;
        } else {
            return false;
        }
    }

    function check_if_disabled(ob) {
        if (ob.hasClass('disabled')) return true;
        return false;
    }

    $("#create_ask_order").click(function() {
        if (check_if_disabled($(this))) return false;

        if (confirm('确认生成询价单？')) {
            $.get('<?= Url::to(["custom-order/create-ask-price", "id" => $custom_order->id]) ?>', function(result) {
                if (result.error == 1) {
                    layer.msg(result.message);
                } else {
                    layer.msg(result.message, function() {});
                }
            }, 'json');
        };
    });

    $("#first_admit").click(function() {
        if (check_if_disabled($(this))) return false;
        if (confirm('确认审核通过？')) {
            $.get('<?= Url::to(["custom-order/admit", "id" => $custom_order->id]) ?>', function(result) {
                if (result.error == 1) {
                    layer.msg(result.message);
                } else {
                    layer.msg(result.message, function() {});
                }
            }, 'json');
        };
    });

    $("#second_admit").click(function() {
        if (check_if_disabled($(this))) return false;
        if (confirm('确认审核通过？')) {
            $.get('<?= Url::to(["custom-order/second-admit", "id" => $custom_order->id]) ?>', function(result) {
                if (result.error == 1) {
                    layer.msg(result.message);
                } else {
                    layer.msg(result.message, function() {});
                }
            }, 'json');
        };
    });


    $("#create_sell_order").click(function() {
        if (check_if_disabled($(this))) return false;

        var goods_id_arr = get_checked_goods();
        if (!goods_id_arr) {
            alert('请选择要操作的商品');
            return false;
        };

        if (confirm('确认生成销售单？')) {
            $.get('<?= Url::to(["custom-order/create-sell-order", "id" => $custom_order->id]) ?>', {
                goods_id_arr: goods_id_arr
            }, function(result) {
                if (result.error == 1) {
                    layer.msg(result.message);
                } else {
                    layer.msg(result.message, function() {});
                }
            }, 'json');
        };
    });

    $("#remend_history").click(function() {
        var view_url = create_url('<?= Url::to(["remend-history", "id" => $custom_order->id]) ?>');
        var view_id = $(this).attr('data-id');
        layer.open({
            type: 2,
            title: '修改历史',
            area: ['80%', '80%'], //宽高
            maxmin: true,
            content: view_url
        });
    });


    // 创建主题
    $("#create_val").click(function() {
        //页面层
        layer.open({
            type: 2,
            title: '添加字段',
            //skin: 'layui-layer-rim', //加上边框
            area: ['80%', '80%'], //宽高
            maxmin: true,
            content: '/custom-order/create-val?id=<?= $custom_order->id; ?>',
            end: function() {
                // location.reload();
            }
        });
    });
    $("#create_goods").click(function() {
        //页面层
        layer.open({
            type: 2,
            title: '添加新商品',
            //skin: 'layui-layer-rim', //加上边框
            area: ['80%', '80%'], //宽高
            maxmin: true,
            content: '/goods/create?order_id=<?= $custom_order->id; ?>&type=7',
            end: function() {
                location.reload();
            }
        });
    });
</script>