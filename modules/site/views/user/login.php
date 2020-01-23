<?php
use yii\helpers\Html;

Yii::$app->setting->title .= ' - login history';
?>

<div class="container">
    <div class="content">
        <div class="row">
            <div class="col s12">
                <h3 class="title"><?php echo Yii::t('app', 'Login History');?> <p><?php echo Yii::t('app', 'They are your account informations');?></p></h3>
                <?php echo \app\widgets\Alert::widget();?>
                <table class="bordered striped highlight centered responsive-table">
                    <thead>
                        <th><?php echo Yii::t('app', 'ID');?></th>
                        <th><?php echo Yii::t('app', 'Ip');?></th>
                        <th><?php echo Yii::t('app', 'Operation System');?></th>
                        <th><?php echo Yii::t('app', 'Browser Name');?></th>
                        <th><?php echo Yii::t('app', 'Created At');?></th>
                        <th><?php echo Yii::t('app', 'Status');?></th>
                    </thead>
                    <tbody>
                        <?php foreach($logins as $login) {?>
                            <tr>
                                <td><?php echo $login->id;?></td>
                                <td><?php echo Html::encode($login->ip);?></td>
                                <td><?php echo Html::encode($login->os_name);?></td>
                                <td><?php echo Html::encode($login->browser_name);?></td>
                                <td><?php echo date('d M Y - H:i', $login->created_at);?></td>
                                <td><?php echo ($login->getIsSuccessful() ? 'Successful' : 'Unsuccessful');?></td>
                            </tr>
                        <?php }?>
                    </tbody>
                </table>
                <?php echo \yii\widgets\LinkPager::widget(['pagination' => $pages]);?>
            </div>
        </div>
    </div>
</div>
