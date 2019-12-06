$(document).ready(function(){
 
    //var items = [{title:"asdasdasd",content:"fdsafas",status:1,add_time:'2015年12月25日'},{title:"asdasdasd",content:"fdsafsssssssssssssssssssssssssssssas",status:2,add_time:'2015年12月25日'}]
    var main_list = new Vue({
        el: '#main_app',
        data: {
            items: {},
            search_data:{page:1,status:0},
            status: {1:{class_name:'label-danger',text_name:'未处理'},2:{class_name:'label-success',text_name:'处理中'},3:{class_name:'label-primary',text_name:'已处理'}},
            filter_status:1,
            reply_id:0,
            reply_key_id:0,
            img_data:null,
            nav_list:{},
            update_key:0,
            is_loading:0,
        },
        created: function () {
            var that = this;
            $.get('/teamwork/default/get-item-list',{status:this.filter_status},function(result){
                if(result.error == 1){
                    that.items = result.content;
                }else{
                 
                } 
            },'json')

            $.get('/teamwork/default/get-nav',function(result){
                if(result.error == 1){
                    that.nav_list = result.content;
                }
            },'json')
        },
        methods:{
            delete_item:function(key,id){
                var that = this;
                if (confirm('delete confirm?')) {
                    $.get('/teamwork/default/delete',{id:id},function(result){
                        if(result.error == 1){
                            Vue.delete( that.items, key )
                        }else{
                            alert(result.message);
                        }
                    },'json')
                };
            },
            refresh:function(){
                var that = this;
                $.get('/teamwork/default/get-item-list',{status:this.filter_status},function(result){
                    if(result.error == 1){
                        that.items = result.content;
                    }
                },'json')
            },
            filter_statu:function(status){
                this.filter_status = status;
                var that = this;
                //加载中
                this.is_loading = 1;

                $.get('/teamwork/default/get-item-list',{status:this.filter_status},function(result){
                    if(result.error == 1){
                        //加载结束
                        that.is_loading = 0;
                        that.items = result.content;
                    }
                },'json')
            },
            reply_item:function(index,id){
                this.reply_id = id;
                this.reply_key_id = index;
                reply_item.item_content = this.items[index].content;

                $("#reply_item_content").code(this.items[index].question)
                $('#reply_item').modal('show');
            },
            edit_item:function(index,id){
                $("#update_item_content").code(this.items[index].content)
                this.update_key = index;

                update_item.item_id = id;

                $('#update_item').modal('show');
            },
            show_img:function(index,ind){
                this.img_data = this.items[index].attach[ind];
                return false;
            },
            hide_img:function(){
                this.img_data = null;
            },
            show_loading:function(){

            },
            change_status:function(index,id,status){
                var that = this;
                $.get('/teamwork/default/change-status',{id:id,status:status},function(result){
                    if(result.error == 1){

                        if (status == '99') {
                            Vue.delete( that.items, index )
                        }else{
                            that.items[index].status = status;
                        }
                        return true;
                    }
                },'json')
            },
        }
    })


    var reply_item = new Vue({
        el: '#reply_item',
        data: {
            item_content:'',
        },
        created: function () {
        },
        methods:{
            submit_form: function(){
                var question = encodeURIComponent($('#reply_item_content').code());
                var item_id = main_list.reply_id;
                var reply_key_id = main_list.reply_key_id;
                var ano = this;
     
                $.post('/teamwork/default/reply',{question:question,id:item_id},function(result){
                    if (result.error == '1') {
                        //加入items
                        //main_list.items.unshift(result.content);
                        main_list.items[reply_key_id].question = result.content;
                        main_list.reply_id = 0;
                        main_list.reply_key_id = 0;

                        $('#reply_item_content').code('');
                        //关闭model
                        $('#reply_item').modal('hide');
                    }else{
                        alert(result.message);
                    }
                },'json')
            }
        }
    })


     
    var create_item = new Vue({
        el: '#create_item',
        data: {
            main_nav: {},
            selected_main_nav: 0,
            sub_nav: {},
            selected_sub_nav: 0,
        },
        created: function () {
            var ano = this;
            //得到mian_nav并赋值
            $.get('/teamwork/default/get-main-nav',function(result){
                ano.main_nav = result.content;
            },'json')
        },
        methods:{
            get_sub_nav: function(main_nav){
                var ano = this;
                //得到mian_nav并赋值
                $.get('/teamwork/default/get-sub-nav',{label:this.selected_main_nav},function(result){
                    ano.sub_nav = result.content;
                },'json')
            },
            submit_form: function(){
                var content = encodeURIComponent($('#create_item_content').code());
                if (this.selected_sub_nav == undefined || this.selected_sub_nav == '0') {
                    alert('请选择子导航');
                    return false;
                };

                if (this.selected_main_nav == undefined || this.selected_main_nav == '0') {
                    alert('请选择主导航');
                    return false;
                };
                var ano = this;
                var Teamwork = {main_nav:this.selected_main_nav,sub_nav:this.selected_sub_nav,content:content};
                $.post('/teamwork/default/insert',{Teamwork},function(result){
                    if (result.error == '1') {
                        //加入items
                        main_list.items.unshift(result.content);
                        //设置表单为空
                        ano.selected_main_nav = 0;
                        ano.selected_sub_nav = 0;
                        $('#create_item_content').code('');
                        //关闭model
                        $('#create_item').modal('hide');
                    }else{
                        alert(result.message);
                    }
                },'json')
            }
        }
    })



    var update_item = new Vue({
        el: '#update_item',
        data: {
            item_id:0,
        },
        created:function(){
        },
        methods:{
            submit_form: function(){
                var content = encodeURIComponent($('#update_item_content').code());
                var ano = this;
 
                $.post('/teamwork/default/update',{content:content,id:this.item_id},function(result){
                    if (result.error == '1') {

                        main_list.items[main_list.update_key].content = result.content.content;
                        main_list.items[main_list.update_key].attach = result.content.attach;
                        $('#update_item_content').code('');
                        //关闭model
                        $('#update_item').modal('hide');
                    }else{
                        alert(result.message);
                    }
                },'json')
            }
        }
    })


    $("#create_item_content").summernote({
        lang:"zh-CN",
        focus: true,
        height: 150,
        maxHeight: null,
    })

    $("#reply_item_content").summernote({
        lang:"zh-CN",
        focus: true,
        height: 150,
        maxHeight: null,
    })


    $("#update_item_content").summernote({
        lang:"zh-CN",
        focus: true,
        height: 150,
        maxHeight: null,
    })


});