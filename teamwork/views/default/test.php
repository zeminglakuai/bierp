<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use app\common\config\sys_config;


?>
<?= Html::jsFile('@web/js/vue.min.js') ?>
<link href="/css/plugins/summernote/summernote.css" rel="stylesheet">
<link href="/css/plugins/summernote/summernote-bs3.css" rel="stylesheet">

<script src="/js/plugins/summernote/summernote.min.js"></script>
<script src="/js/plugins/summernote/summernote-zh-CN.js"></script>
 
<div class="summernote"></div>


<div class="summernote2"></div>
 
<script type="text/javascript">
    $(".summernote").summernote({
        lang:"zh-CN",
        focus: true,
        height: 150,
        maxHeight: null,
    })


    $(".summernote2").summernote({
        lang:"zh-CN",
        focus: true,
        height: 150,
        maxHeight: null,
    })    
</script>