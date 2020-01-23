<?php use yii\helpers\Html;?>
<!-- content -->
<div class="content user">     
    <div class="col-md-12">
<div class="title_up"><h3>Login history</h3></div>

        <table class="table table-bordered">
            <thead>
                <th>ID</th>
                <th>User</th>
                <th>Ip Address</th>
                <th>Operation System</th>
                <th>Browser</th>
                <th>Created at</th>
                <th>Status</th>
            </thead>
            <tbody>
            <?php foreach($logins as $login) {?>
                <tr>
                    <td><?php echo $login->id;?></td>
                    <td><a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/user/edit', 'id' => $login->user->id]);?>"><?php echo Html::encode($login->user->getFullName());?></a></td>
                    <td><?php echo Html::encode($login->ip);?></td>
                    <td><?php echo Html::encode($login->os_name); ?></td>
                    <td><?php echo Html::encode($login->browser_name); ?></td>
                    <td><?php echo date('d M Y - H:i', $login->created_at);?></td>
                    <td><?php echo ($login->getIsSuccessful() ? ' Successful' : ' Unsuccessful');?></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
        
        <?php echo \yii\widgets\LinkPager::widget(['pagination' => $pages]);?>
    </div>
</div>
<!-- END content -->