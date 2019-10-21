<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use app\common\config\sys_config;


?>
<? $this->title = 'BUG跟踪反馈';?>

<?= Html::jsFile('@web/js/vue.min.js') ?>
<link href="/css/plugins/summernote/summernote.css" rel="stylesheet">
<link href="/css/plugins/summernote/summernote-bs3.css" rel="stylesheet">

<script src="/js/plugins/summernote/summernote.min.js"></script>
<script src="/js/plugins/summernote/summernote-zh-CN.js"></script>
 

<style type="text/css">
.project-title p img{
    display: none;
    visibility: hidden;
}
</style>
 
<div style="width:90%;min-width:1100px;margin:0 auto 10em auto;" id="main_app">
    <div class="ibox">
        <div class="ibox-content">
            <div class="row m-b-sm m-t-sm">
                <div class="col-md-1">
                    <button type="button" id="loading-example-btn" class="btn btn-white btn-sm" @click="refresh()"><i class="fa fa-refresh"></i> 刷新</button>
                </div>
                <div class="col-md-3">
                    当前记录总数 {{items.length}} 条
                </div> 
                <div class="col-md-3">
                     
                </div>
                <div class="col-md-5" style="text-align:right;">
                     <div class="note-font btn-group">
                        <button type="button" class="btn btn-sm" v-bind:class="[filter_status == 0?'btn-primary':'btn-white']" @click="filter_statu(0)">全部数据</button>
                        <button type="button" class="btn btn-sm" v-bind:class="[filter_status == 1?'btn-primary':'btn-white']" @click="filter_statu(1)">未处理</button>
                        <button type="button" class="btn btn-sm" v-bind:class="[filter_status == 2?'btn-primary':'btn-white']" @click="filter_statu(2)">处理中</button>
                        <button type="button" class="btn btn-sm" v-bind:class="[filter_status == 3?'btn-primary':'btn-white']" @click="filter_statu(3)">已处理</button>
                        <button type="button" class="btn btn-sm" v-bind:class="[filter_status == 99?'btn-primary':'btn-white']" @click="filter_statu(99)">已归档</button>
                    </div>
                </div>
            </div>

            <div class="project-list">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%" style="text-align:center;">状态</th>
                            <th width="7%">导航</th>
                            <th width="35%">问题</th>
                            <th width="10%" style="text-align:center;">附件</th>
                            <th width="30%">处理</th>
                            <th style="text-align:center;">操作</th>
                        </tr>
                    </thead>
                    <tbody v-if="items.length > 0">
                        <tr v-for="(item, index) in items">
                            <td class="project-status">
                                <span class="label" v-bind:class="[status[item.status].class_name]">{{status[item.status].text_name}}</span>
                            </td>
                            <td class="project-status">
                                {{nav_list[item.main_nav].name}}<br>
                                {{nav_list[item.main_nav].sub_list[item.sub_nav].name}}
                            </td>
                            <td class="project-title">
                                <p><b v-html="item.content" style="word-wrap:break-word;"></b></p>
                                <small>{{item.add_time}} | {{item.add_user_name}}</small>
                            </td>

                            <td class="project-fujian" style="text-align:center;">
                                <a href="javascript:void();" v-for="(img, ind) in item.attach" @click="show_img(index,ind)" style="display:block;">
                                    查看图片
                                </a>
                            </td>
                            <td class="project-question">
                                <span v-html="item.question"></span></b>
                            </td>
                            <td class="project-actions" style="text-align:center;">
                                <button  class="btn btn-primary btn-sm" @click="edit_item(index,item.id)"><i class="fa fa-edit"></i></button>
                                <button  class="btn btn-primary btn-sm" @click="reply_item(index,item.id)"><i class="fa fa-mail-reply"></i></button>
                                <div class="btn-group">
                                    <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle">操作 <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="javascript:void();" @click="change_status(index,item.id,1)">设置未处理</a></li>
                                        <li><a href="javascript:void();" @click="change_status(index,item.id,2)">设置处理中</a></li>
                                        <li><a href="javascript:void();" @click="change_status(index,item.id,3)">设置已处理</a></li>
                                        <li class="divider"></li>
                                        <li><a href="javascript:void();" @click="change_status(index,item.id,99)">设置已归档</a></li>
                                    </ul>
                                </div>
                                <button  class="btn btn-danger btn-sm" @click="delete_item(index,item.id)"><i class="fa fa-trash"></i></button>

                            </td>
                        </tr>
                    </tbody>

                    <tbody v-else>
                        <tr>
                            <td colspan="6" style="text-align:center;">
                                没有数据
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div style="position:fixed;width:100%;height:100%;background-color: #000;top:0px;opacity:0.3;z-index:99999;left:0px;" v-show="is_loading" id="loading"></div>
    <div style="position:fixed;margin:auto;left:0;right:0;top:0;bottom:0;z-index:999999;width:200px;height:2em;color:#fff;font-size:3em;" v-show="is_loading">加载中。。</div>
    <div style="position:fixed;width:100%;height:100%;background-color: #000;top:0px;opacity:0.3;z-index:99999;left:0px;" v-show="img_data" id="bg_cover" @click="hide_img"></div>
    <img v-bind:src="img_data" alt="" style="position:fixed;margin:auto;left:0;right:0;top:0;bottom:0;z-index:999999;box-shadow:5px 5px 15px #ed5565;">
</div>

<!-- status[item.status].class_name -->
<div style="position:fixed;width:100%;padding:1em;;bottom:0px;background-color: #fff;box-shadow: 0px -2px 5px #aaa;">
    <div style="width:80%;max-width:1200px;margin:0 auto;">
        <a class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#create_item"><i class="fa fa-plus-circle"></i> 添加新数据</a>
    </div>
</div>



<div class="modal inmodal" id="create_item" tabindex="1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width:950px;">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                <b>添加新项目</b>
            </div>
            <div class="modal-body" style="padding:0px;">
                <div class="ibox">
                    <div class="ibox-content no-padding">
                        <form id="custom_order_form" class="form-horizontal m-t" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">导航分类</label>
                                <div class="col-sm-3">
                                    <select name="mian_nav" id="mian_nav" v-model="selected_main_nav" class="form-control" @change="get_sub_nav()">
                                        <option value="0" selected="selected">请选择主导航</option>
                                        <option v-for="(nav, index) in main_nav" v-bind:value="index">
                                            {{nav.name}}
                                        </option>
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <select name="sub_nav" id="sub_nav" class="form-control" v-model="selected_sub_nav">
                                        <option value="0" selected="selected">请选择子导航</option>
                                        <option  v-for="nav in sub_nav" v-bind:value="nav.label">
                                            {{nav.name}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="summernote" id="create_item_content"></div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="create_new_item" @click="submit_form"><i class="fa fa-plus-circle"></i> 提交添加</button>
                <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-close"></i> 关闭</button>
            </div>
        </div>
    </div>
</div>


<div class="modal inmodal" id="update_item" tabindex="1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width:950px;">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                <b>更新新项目</b>
            </div>
            <div class="modal-body" style="padding:0px;">
                <div class="ibox">
                    <div class="ibox-content no-padding">
                        <div class="update_item_content" id="update_item_content"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="create_new_item" @click="submit_form"><i class="fa fa-plus-circle"></i> 提交更新</button>
                <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-close"></i> 关闭</button>
            </div>
        </div>
    </div>
</div>


<div class="modal inmodal" id="reply_item" tabindex="2" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width:950px;">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                <b>回复</b>
            </div>
            <div class="modal-body" style="padding:0px;">
                <div class="ibox">
                    <div class="ibox-content no-padding">
                        <div style="padding:15px 20px;"><p v-html="item_content" style="word-wrap:break-word;"></p></div>
                        <div class="summernote2" id="reply_item_content"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="create_new_item" @click="submit_form"><i class="fa fa-plus-circle"></i> 提交回复</button>
                <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-close"></i> 关闭</button>
            </div>
        </div>
    </div>
</div>

<?= Html::jsFile('@web/js/teamwork.js') ?>
