<?php
use yii\helpers\Html;
?>
<div class="ibox">
  <div class="ibox-content" style="padding-bottom:10px;">
    <form id="<?= $form_id?$form_id:'order_form'?>" class="form-horizontal m-t"  enctype="multipart/form-data">
    	<?php 
    		foreach ($form_data as $key => $value) {
    	?>
			<?php if($value['type'] == 'text'){?>
			<div class="form-group">
			    <label class="col-sm-2 control-label"><?= $value['inneed']?'<span class="red">*</span>':''?> <?= $value['label_name'] ?></label>
			    <div class="col-sm-9">
			        <input type="text" name="<?= $value['name'] ?>" class="form-control" value="<?= $value['value'] ?>" id="<?= $value['id'] ?>"/>
		            <?php 
		            if($value['tips']){
		            ?>
						<span class="help-block m-b-none"><i class="fa fa-info-circle"></i> <?= $value['tips']?></span>
				    <?php
					}?>
			    </div>
			</div>
			<?php 
			}elseif($value['type'] == 'select'){
			?>
			<div class="form-group">
			    <label class="col-sm-2 control-label"><?= $value['inneed']?'<span class="red">*</span>':''?> <?= $value['label_name'] ?></label>
			    <div class="col-sm-9">
			        <?= Html::dropDownList($value['name'], $value['value'], $value['init_value'],['prompt' => $value['prompt']?$value['prompt']:'请选择'.$label_name,'class' => 'form-control','id'=>$value['id']]) ?>
		            <?php 
		            if($value['tips']){
		            ?>
						<span class="help-block m-b-none"><i class="fa fa-info-circle"></i> <?= $value['tips']?></span>
				    <?php
					}?>
			    </div>
			</div>


			<?php
	          }elseif ($value['type'] == 'token_input') {
	         ?>
	        <div class="form-group">
			    <label class="col-sm-2 control-label"><?= $value['inneed']?'<span class="red">*</span>':''?> <?= $value['label_name'] ?></label>
			    <div class="col-sm-9">
	          	<input type="text" name="<?= $value['name']?>" id="<?= $value['id']?>" class="form-control" value="<?= $value['value']?>" placeholder="<?= $value['label_name']?>"/>
	          	<script type="text/javascript">
	            	$(document).ready(function() {
		                <?php
		                  if($value['value'] >= 1){
		                    $query = new \yii\db\Query();
		                    $token_input_data = $query->select('id,'.$value['name_name'].' as name')
		                            ->from($value['table_name'])
		                            ->where(['id'=>$value['value']])
		                            ->one();
		                  }
		                ?>
		                $("#<?= $value['id']?>").tokenInput("<?= $value['token_url']?>",
		                  {
		                    theme:'facebook', 
		                    hintText:'请输入要搜索的关键字',
		                    tokenLimit:1,
		                    <?php if($value['value']){  ?>prePopulate:[{id:'<?= $token_input_data['id'] ?>',name:'<?= $token_input_data['name'] ?>'}],<?php }?>
		                  }
		                );
	            	});
	          	</script>
			    </div>
			</div>

			<?php
			}elseif($value['type'] == 'radio'){
			?>

			<div class="form-group">
			    <label class="col-sm-2 control-label"><?= $value['inneed']?'<span class="red">*</span>':''?> <?= $value['label_name'] ?></label>
			    <div class="col-sm-9">
					<div class="row">
						<?php if($value['init_value']){
						foreach ($value['init_value'] as $key => $vv) {
						?>
					        <div class="col-sm-1">
					          <div class="radio i-checks">
					              <label><input type="radio" value="<?= $vv['value']?>" name="<?= $value['name'] ?>" <?= $value['value'] == $vv['value']?'checked="checked"':''?>> <i></i><?= $vv['label_name']?></label>
					          </div>
					        </div>
						<?php
						}
						}?>
			            <?php 
			            if($value['tips']){
			            ?>
							<span class="help-block m-b-none"><i class="fa fa-info-circle"></i> <?= $value['tips']?></span>
					    <?php
						}?>
					</div>
			    </div>
			</div>

			<?php
			}elseif($value['type'] == 'data_picker'){
			?>
			<?= Html::jsFile('@web/js/bootstrap-datetimepicker.min.js') ?>
			<?= Html::jsFile('@web/js/bootstrap-datetimepicker.zh-CN.js') ?>
			<?= Html::cssFile('@web/css/bootstrap-datetimepicker.min.css') ?>

			<div class="form-group">
			    <label class="col-sm-2 control-label"><?= $value['inneed']?'<span class="red">*</span>':''?> <?= $value['label_name'] ?></label>
			    <div class="col-sm-9">
		            <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd hh:ii:00" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd HH:ii:ss">
		              	<input class="form-control" name="<?= $value['name'] ?>" size="16" value="<?= $value['value']?>" type="text"  readonly="" placeholder="<?= $value['label_name'] ?>">
		              	<span class="input-group-addon">
		            		<span class="glyphicon glyphicon-calendar"></span>
		              	</span>
		            </div>
		            <?php 
		            if($value['tips']){
		            ?>
						<span class="help-block m-b-none"><i class="fa fa-info-circle"></i> <?= $value['tips']?></span>
				    <?php
					}?>
			    </div>
			</div>
		    <script>
		    $('.form_date').datetimepicker({
		        language:  'zh-CN',
		        weekStart: 1,
		        todayBtn:  1,
		        autoclose: 1,
		        todayHighlight: 1,
		        startView: 2,
		        minView: 0,
		        forceParse: 0
		      });
		    </script>

			<?php
			}elseif($value['type'] == 'hidden'){
			?>
			
			<input name="<?= $value['name'] ?>" size="16" type="hidden" value="<?= $value['value']?>">

			<?php
			}elseif($value['type'] == 'image'){
			?>

			<div class="form-group">
		        <label class="col-sm-2 control-label"><?= $value['inneed']?'<span class="red">*</span>':''?> <?= $value['label_name'] ?></label>
		        <div class="col-sm-7">
		            <div class="profile-picture" id="imgdiv_<?= $value['id']?>">
		              <img id="imgShow_<?= $value['id']?>" class="goods_thumb" style="max-width:450px;" src="<?php if($value['value'] <> ''){echo Yii::getalias('@web/').$value['value'];}else{echo Yii::getalias('@web/img/profile_small.jpg');}?>" />
		            </div>
		            <input type="file" name="<?= $value['name'] ?>" id="up_img_<?= $value['id']?>" style="display:none;"/>
		        </div>
		        <div class="col-sm-2 control-label" style="text-align:left;">点击图片上传</div>
		     </div>
			<script type="text/javascript">
				$(document).ready(function(){
				    new uploadPreview({ UpBtn: "up_img_<?= $value['id']?>", DivShow: "imgdiv_<?= $value['id']?>", ImgShow: "imgShow_<?= $value['id']?>" });
				    $("#imgShow_<?= $value['id']?>").click(function(){
				       $("#up_img_<?= $value['id']?>").trigger('click');
				    });
				});
			</script>
			<?php
			}?>
    	<?php
    		}
    	?>
    </form>
  </div>
</div>