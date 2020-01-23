<?php
use yii\helpers\Html;

$bundle = \app\modules\admin\assets\Asset::register($this);

$this->beginPage();
?>
<!DOCTYPE HTML>
<html lang="fa">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php echo Html::csrfMetaTags();?>
        
        <title><?php echo Html::encode(Yii::$app->setting->title);?> - Administrator</title>
                
        <link rel="shortcut icon" href="<?php echo $bundle->baseUrl;?>/img/favicon.png">
        <link href="<?=Yii::getAlias('@web')?>/strength-meter/css/strength-meter.min.css" media="all" rel="stylesheet" type="text/css" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
        <script src="<?=Yii::getAlias('@web')?>/strength-meter/js/strength-meter.min.js" type="text/javascript"></script>
        <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
        <script type="text/javascript">var baseUrl = "<?php echo Yii::$app->request->baseUrl;?>/";</script>
        
        <?php $this->head();?>

<script>

jQuery.noConflict();


(function($) {

$(function() 
{
// more code using $ as alias to jQuery


$(document).ready(function() {

$("selects.form-control").each(function() {
	  var classes = 'custom-select',
      id      = $(this).attr("id"),
      name    = $(this).attr("name");
	  var template =  '<div class="' + classes + '">';
      template += '<span class="custom-select-trigger">Select</span>';
      template += '<div class="custom-options">';
      $(this).find("option").each(function() {
        template += '<span class="custom-option ' + $(this).attr("class") + '" data-value="' + $(this).attr("value") + '">' + $(this).html() + '</span>';
      });
  template += '</div></div>';
  
  $(this).wrap('<div class="custom-select-wrapper"></div>');
  $(this).hide();
  $(this).after(template);
});
$(".custom-option:first-of-type").hover(function() {
  $(this).parents(".custom-options").addClass("option-hover");
}, function() {
  $(this).parents(".custom-options").removeClass("option-hover");
});
$(".custom-select-trigger").on("click", function() {
  $('html').one('click',function() {
    $(".custom-select").removeClass("opened");
  });
  $(this).parents(".custom-select").toggleClass("opened");
  event.stopPropagation();
});
$(".custom-option").on("click", function() {
  $(this).parents(".custom-select-wrapper").find("select").val($(this).data("value"));
  $(this).parents(".custom-options").find(".custom-option").removeClass("selection");
  $(this).addClass("selection");
  $(this).parents(".custom-select").removeClass("opened");
  $(this).parents(".custom-select").find(".custom-select-trigger").text($(this).text());
});


});


    $('[data-toggle="tooltip"]').tooltip();   


})
})(jQuery)

</script>
 
    </head>
    <body>


            <!-- header -->
            <div class="navbar navbar-default navbar-fixed-top">
                <div class="navbar-header">
                    <a href="#" class="navbar-brand"> <img src="<?php echo $bundle->baseUrl;?>/img/logo2.png"></a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
.
						<li><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/default/setting');?>" data-toggle="tooltip" data-placement="bottom" title="Settings"><i class="fa fa-cog"> </i></a></li>
						<li><a href="<?php echo Yii::$app->urlManager->createUrl('/admin');?>" data-toggle="tooltip" data-placement="bottom" title="Dashboard"  ><i class="fa fa-home"> </i></a></li>
                        <li><a href="<?php echo Yii::$app->urlManager->createUrl('/site');?>" data-toggle="tooltip" data-placement="bottom" title="Client" ><i class="fa fa-user"> </i></a></li>
                        <li><a href="<?php echo Yii::$app->urlManager->createUrl('/site/default/logout');?>" data-toggle="tooltip" data-placement="bottom" title="LogOut"  ><i class="fa fa-sign-out"> </i></a></li>
                    </ul>
                </div>
            </div>
            <!-- END header -->

        <!-- navigation -->
<div class="row row-margin">
        <div class="col-md-2 no-padding  navright2">
<div class="navright">
<div class="scroll-sidebar">

            <div class="navigation">
                <h3></h3>
                <div class="resMenuIcon hidden-md hidden-lg"><i class="fa fa-bars"></i></div>
                <ul class="nav hidden-sm hidden-xs">
                    <?php $c = Yii::$app->controller->id; $a = Yii::$app->controller->action->id;?>
                     <li<?php echo ($c == 'default' && $a == 'index' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin');?>"><i class="fa fa-home"></i>Dashboard</a></li>
                    <li<?php echo ($c == 'user' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/user/index');?>"><i class="fa fa-users"></i>Users</a></li>
                    <li<?php echo ($c == 'plan' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/plan/index');?>"><i class="fa fa-plus-square"></i>Plans</a></li>
                    <li<?php echo ($c == 'ip' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/ip/index');?>"><i class="fa fa-bars"></i>Ip addresses</a></li>
                    <li<?php echo ($c == 'server' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/server/index');?>"><i class="fa fa-server"></i>Dedicated servers</a></li>
                    <li<?php echo ($c == 'datastore' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/datastore/index');?>"><i class="fa fa-database"></i>Datastores</a></li>
                    <li<?php echo ($c == 'os' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/os/index');?>"><i class="fa fa-circle"></i>Operating systems</a></li>
                    <li<?php echo ($c == 'iso' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/iso/index');?>"><i class="fa fa-cube"></i>Iso files</a></li>
                    <li<?php echo ($c == 'vps' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/vps/index');?>"><i class="fa fa-cubes"></i>Virtual servers</a></li>
                    <li<?php echo ($c == 'default' && $a == 'login' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/default/login');?>"><i class="fa fa-sign-in"></i>Login histories</a></li>
                    <li<?php echo ($c == 'api' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/api/index');?>"><i class="fa fa-list"></i>Apis</a></li>
                    <li<?php echo ($c == 'default' && $a == 'setting' ? ' class="active"' : '');?>><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/default/setting');?>"><i class="fa fa-cogs"></i>Settings</a></li>
                </ul>
            </div>


<div class="botmenu">
 
       
                <div class=" navbar-collapse">
                    <ul class="nav navbar-nav">
<li><a href="<?php echo Yii::$app->urlManager->createUrl('/admin/default/setting');?>" data-toggle="tooltip" data-placement="top" title="Settings"  ><i class="fa fa-cog"> </i></a></li>
                        <li><a href="<?php echo Yii::$app->urlManager->createUrl('/site');?>" data-toggle="tooltip" data-placement="top" title="Client" ><i class="fa fa-user"> </i></a></li>
                        <li><a href="<?php echo Yii::$app->urlManager->createUrl('/site/default/logout');?>"  data-toggle="tooltip" data-placement="top" title="LogOut" ><i class="fa fa-sign-out"> </i></a></li>
                    </ul>
                </div>
</div>  <!-- END botmenu -->
</div>  <!-- END scroll-sidebar -->
</div>  <!-- END navright -->
        </div>
        <!-- END navigation -->
           
        <div class="col-md-10 col-xs-12 col-md-offset-2">

    
            <?php $this->beginBody();?>
            <div class="col-md-12">
                <?php echo $content;?>
            </div>
            <?php $this->endBody();?>
            <!-- footer -->


        </div>

 </div>   <!-- END row -->      

            <div class="footer">
                <div class="col-md-12">
                    <p>Created and designed by AutoVM, all rights reserved.</p>
                </div>
            </div>
            <!-- END footer -->

    <script>
        jQuery(document).ready(function(e){
            jQuery('.resMenuIcon').on('click',function(e){
                jQuery('.navigation ul.nav').toggleClass('hidden-sm');
                jQuery('.navigation ul.nav').toggleClass('hidden-xs');
            });
        });
    </script>
    </body>
</html>
<?php $this->endPage();?>
