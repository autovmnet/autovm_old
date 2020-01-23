<?php 
use yii\helpers\Html;
?>
<!DOCTYPE HTML>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo Html::encode(Yii::$app->setting->title);?> - Error</title>
</head>

<body>
    <style type="text/css">
        * {text-align:center; font:14px calibri,tahoma;}
        .content{width:200px; margin:auto; margin-top:100px; padding:20px; border:1px solid #dedede;box-shadow:0 0 3px #eee;}
        h3{margin:0;}
        a{color:#fff; background:green;padding:4px 10px;text-decoration:none;}
    </style>
    
    <div class="content">
        <h3><?php echo $exception->getMessage();?></h3>
        <br>
        <a href="<?php echo Yii::$app->urlManager->createUrl('/site');?>" class="btn btn-primary">Home</a>
        <?php if($ref = Yii::$app->request->referrer) {?>
        <a href="<?php echo Html::encode($ref);?>" class="btn btn-primary">Back</a>
        <?php }?>
    </div>
</body>

</html>