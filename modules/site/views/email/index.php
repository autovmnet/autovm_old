<?php
use yii\helpers\Html;

Yii::$app->setting->title .= ' - account emails';
?>

<div class="content">
    <div class="container vpsServers">
        <h3 class="title">Account Emails <p>You can create unlimited emails</p></h3>
        <?php echo \app\widgets\Alert::widget();?>
        <table class="bordered striped highlight centered responsive-table">
            <thead>
                <th>ID</th>
                <th>Email</th>
                <th>Primary</th>
                <th>Confirmed</th>
                <th>Created At</th>
                <th>Updated At</th>
            </thead>
            <tbody>
            <?php foreach($emails as $email) {?>
                <tr>
                    <td><?php echo $email->id;?></td>
                    <td><?php echo Html::encode($email->email);?></td>
                    <td><?php echo ($email->isPrimary() ? 'Yes' : '<a href="' . Yii::$app->urlManager->createUrl(['/site/email/primary', 'id' => $email->id]) . '">Set to primary</a>');?></td>
                    <td><?php echo ($email->isConfirmed() ? 'Yes' : '<a href="' . Yii::$app->urlManager->createUrl(['/site/email/send', 'id' => $email->id]) . '">Send confirmation email</a>');?></td>
                    <td><?php echo date('d M Y - H:i', $email->created_at);?></td>
                    <td><?php echo date('d M Y - H:i', $email->updated_at);?></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
        <div class="row">
            <div class="col s12">
                <?php echo \yii\widgets\LinkPager::widget(['pagination' => $pages]);?>
            </div>
            <div class="col l4 m6 s6">
                <a href="<?php echo Yii::$app->urlManager->createUrl('site/email/create');?>" class="btn waves-light waves-effect amber" style="margin-top: 3%;">Create a new email</a>
            </div>
        </div>
    </div>
</div>