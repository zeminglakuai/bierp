$("#udload_new_pic").click(function(){
  var length = $("#relate_img_list").children().length;
  length++;
  var new_img = '<div class="relate_img_item" id="file_div_'+length+'">'+
  '<div class="relate_img_div" id="file_img_'+length+'">'+
  '<div class="relate_img_operate_bg">'+
  '</div>'+
  '<div class="relate_img_operate">'+
  '<i class="fa fa-trash" id="delete_img_'+length+'"></i>'+
  '</div>'+
  '</div>'+
  '<div class="upload_img_div">'+
  '<input type="file" name="file[]" class="file_btn" id="file_btn_'+length+'">'+
  '</div>'+
  '</div>';

  $("#relate_img_list").append(new_img);

  $('#file_btn_'+length).trigger('click'); 

  $('#file_btn_'+length).change(function(){
    readAsDataURL('file_btn_'+length,'file_img_'+length);
  });

  $('#delete_img_'+length).click(function(){
    $('#file_div_'+length).remove();
  });

});

function readAsDataURL(file_id,div){  
    //检验是否为图像文件  
    var file = document.getElementById(file_id).files[0];  
    if(!/image\/\w+/.test(file.type)){  
        alert("只允许添加图片");  
        return false;  
    }  
    var reader = new FileReader();  
    //将文件以Data URL形式读入页面  
    reader.readAsDataURL(file);  
    reader.onload=function(e){  
        $("#"+div).append('<img src="' + this.result +'" alt="" />');
    }
}
