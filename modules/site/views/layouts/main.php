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
        <meta name="author" content="nikivm">
        <title><?php echo Html::encode(Yii::$app->setting->title);?></title>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script type="text/javascript" src="<?= \yii\helpers\Url::base() ?>/js/pwstrength.js"></script>
        <link rel="shortcut icon" href="<?php echo $bundle->baseUrl;?>/img/favicon.png">
        <?php echo Html::csrfMetaTags();?>
        <?php $this->head();?>
    </head>
    <body<?php echo (Yii::$app->lang->getIsRtl() ? ' class="rtl"' : '');?>>
    <?php $this->beginBody();?>
        <?php if(!Yii::$app->user->isGuest) {echo \app\modules\site\widgets\UserVpsList::widget();}?>

        <!-- TopNav !-->
        <nav class="light-blue darken-1">
            <div class="nav-wrapper">
                <a href="<?php echo Yii::$app->urlManager->createUrl('/site');?>" class="brand-logo"><img src="<?php echo $bundle->baseUrl;?>/img/logo.png" style="margin-top:10px;" width="150"></a>
                <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
				<ul id="lang" class="dropdown-content" style="margin-top:80px;border-radius:10px;">
					<?php foreach(Yii::$app->lang->langs as $id => $name) {?>
				  		<li><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/default/lang', 'id' => $id, 'now' => time()]);?>"><?php echo $name;?></a></li>
					<?php }?>
				</ul>
				<ul class="right hide-on-med-and-down">
                    <?php $controller = Yii::$app->controller->id; $action = Yii::$app->controller->action->id;?>
                    <?php if(Yii::$app->user->isGuest) {?>
      					<li style="position:relative;">
							<a class="dropdown-button" href="#" data-activates="lang"><i class="material-icons">language</i><?php echo Yii::t('app', 'Language');?><i class="material-icons right">arrow_drop_down</i></a>
						</li>
						<li><a href="<?php echo Yii::$app->urlManager->createUrl('/site');?>"><i class="material-icons">home</i><?php echo Yii::t('app', 'Home');?></a></li>
                        <li<?php echo $controller == 'site' && $action == 'login' ? ' class="active"' : '';?>><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/default/login']);?>"><i class="material-icons">person</i><?php echo Yii::t('app', 'Login');?></a></li>
                        <li<?php echo $controller == 'site' && $action == 'lost-password' ? ' class="active"' : '';?>><a href="<?php echo Yii::$app->urlManager->createUrl(['site/default/lost-password']);?>"><i class="material-icons">lock</i><?php echo Yii::t('app', 'Lost Password');?></a></li>
                    <?php } else {?>
                        <li<?php echo $controller == 'user' && $action == 'index' ? ' class="active"' : '';?>><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/user/index']);?>"><i class="material-icons">dashboard</i><?php echo Yii::t('app', 'Dashboard');?></a></li>
                        <li<?php echo $controller == 'user' && $action == 'profile' ? ' class="active"' : '';?>><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/setting/index']);?>"><i class="material-icons">person</i><?php echo Yii::t('app', 'Profile');?></a></li>
                        <li<?php echo $controller == 'user' && $action == 'login' ? ' class="active"' : '';?>><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/user/login']);?>"><i class="material-icons">history</i><?php echo Yii::t('app', 'Login History');?></a></li>
                        <?php if(Yii::$app->user->identity->getIsAdmin()) {?>
                            <li><a href="<?php echo Yii::$app->urlManager->createUrl('/admin');?>"><i class="material-icons">star</i><?php echo Yii::t('app', 'Admin');?></a></li>
                        <?php }?>
                        <li><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/default/logout']);?>"><i class="material-icons">exit_to_app</i><?php echo Yii::t('app', 'Logout');?></a></li>
                    <?php }?>
                </ul>
                <ul class="side-nav" id="mobile-demo">
                    <?php $controller = Yii::$app->controller->id; $action = Yii::$app->controller->action->id;?>
                    <?php if(Yii::$app->user->isGuest) {?>
                        <li><a href="<?php echo Yii::$app->urlManager->createUrl('/site');?>"><i class="material-icons">home</i>Home</a></li>
                        <li<?php echo $controller == 'site' && $action == 'login' ? ' class="active"' : '';?>><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/default/login']);?>"><i class="material-icons">person</i>Login</a></li>
                        <li<?php echo $controller == 'site' && $action == 'lost-password' ? ' class="active"' : '';?>><a href="<?php echo Yii::$app->urlManager->createUrl(['site/default/lost-password']);?>"><i class="material-icons">lock</i>Lost Password</a></li>
                    <?php } else {?>
                        <li<?php echo $controller == 'user' && $action == 'index' ? ' class="active"' : '';?>><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/user/index']);?>"><i class="material-icons">dashboard</i>Dashboard</a></li>
                        <li<?php echo $controller == 'user' && $action == 'profile' ? ' class="active"' : '';?>><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/setting/index']);?>"><i class="material-icons">person</i>Profile</a></li>
                        <li<?php echo $controller == 'user' && $action == 'login' ? ' class="active"' : '';?>><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/user/login']);?>"><i class="material-icons">history</i>Login History</a></li>
                        <?php if(Yii::$app->user->identity->getIsAdmin()) {?>
                            <li><a href="<?php echo Yii::$app->urlManager->createUrl('/admin');?>"><i class="material-icons">star</i>Admin</a></li>
                        <?php }?>
                        <li><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/default/logout']);?>"><i class="material-icons">exit_to_app</i>Logout</a></li>
                    <?php }?>
                </ul>
            </div>
        </nav>
        <script>
            $(document).ready(function (e) {
				$('.dropdown-button').dropdown();
                $(".button-collapse").sideNav();
            });
        </script>

        <?php echo $content;?>

        <!-- FixedButton !-->
        <div class="fixed-action-btn">
            <a class="btn-floating btn-large amber hoverable" onclick="reloadPage()">
                <i class="large material-icons">refresh</i>
            </a>
        </div>

        <!-- Footer !-->
        <div class="footer z-depth-3">
            <footer class="page-footer light-blue darken-1">
                <div class="container">
                    <div class="row">
                        <div class="col l6 s12">
                            <h5 class="white-text"><?php echo Yii::t('app', 'AutoVM System');?></h5>
                            <p class="grey-text text-lighten-4">AutoVM is an open source platform to manage virtual machines(VM) on the VMware ESXI virtualization which allows the VPS providers to manage full automation of support and sales process.</p></p>
                        </div>
                        <div class="col l4 offset-l2 s12">
                            <h5 class="white-text"><?php echo Yii::t('app', 'Useful Links');?></h5>
                            <ul>
                                <li><a class="grey-text text-lighten-3" href="http://autovm.net/en/lab.php" target="_blank"><?php echo Yii::t('app', 'Lab');?></a></li>
                                <li><a class="grey-text text-lighten-3" href="http://wiki.autovm.net" target="_blank"><?php echo Yii::t('app', 'Wiki');?></a></li>
                                <li><a class="grey-text text-lighten-3" href="http://autovm.net/en/about.html" target="_blank"><?php echo Yii::t('app', 'About');?></a></li>
                                <li><a class="grey-text text-lighten-3" href="http://autovm.net/en/contact.html" target="_blank"><?php echo Yii::t('app', 'Contact');?></a></li>
<li><a class="grey-text text-lighten-3" href="http://stack.autovm.net" target="_blank"><?php echo Yii::t('app', 'Stack');?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="footer-copyright">
                    <div class="container">
                        <?php echo Yii::t('app', '© 2018 Created and designed by AutoVM, all rights reserved.');?>
                        <a class="grey-text text-lighten-4 right" href="http://autovm.net/en" target="_blank">AutoVM</a>
                    </div>
                </div>
            </footer>
        </div>
    <?php $this->endBody();?>
    </body>
</html>
<?php $this->endPage();?>


<?php echo Yii::t('app', '
');?>
