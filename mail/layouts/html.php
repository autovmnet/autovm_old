<?php $this->beginPage();?>
<!DOCTYPE HTML>
<html lang="fa">
<head>
    <meta charset="UTF-8">
</head>

<body dir="ltr" style="margin:0;">
    <?php $this->beginBody();?>
    <div style="background:#f6f6f6;padding:50px;border-radius:2px;">
        <div style="width:550px;margin:auto;">
            <?php echo $content;?>
        </div>
    </div>
    <?php $this->endBody();?>
</body>

</html>
<?php $this->endPage();?>