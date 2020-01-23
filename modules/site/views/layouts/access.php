<?php
use yii\helpers\Html;

$this->beginPage();

$bundle = \app\modules\site\assets\Asset::register($this);

// website base url
//$baseUrl = Yii::$app->request->baseUrl . '/';
$baseUrl = rtrim(\yii\helpers\Url::to('/', true),'/') . Yii::$app->request->baseUrl . '/';
$this->registerJs("var baseUrl = \"{$baseUrl}\";", \yii\web\View::POS_END);

?>

<?php $this->beginPage();?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script type="text/javascript" src="<?= \yii\helpers\Url::base() ?>/js/pwstrength.js"></script>
        <?php echo Html::csrfMetaTags();?>
        <?php $this->head();?>
    </head>
    <body>
    <?php $this->beginBody();?>
        <?php echo $content;?>
    <?php $this->endBody();?>
<?php $this->endPage();?>
