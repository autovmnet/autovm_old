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
<style>
.content{background:none!important;}
</style>
            <?php $this->beginBody();?>
                <?php echo $content;?>
            <?php $this->endBody();?>
    </body>
</html>
<?php $this->endPage();?>
