<?php use yii\helpers\Html;?>
<!-- content -->
<div class="content">     
    <div class="col-md-12">
        <a href="<?php echo Yii::$app->urlManager->createUrl('/admin/api/index');?>" class="btn btn-info"><i class="fa fa-plus"></i>List of apis</a>
        <table class="table table-bordered">
            <thead>
                <th>ID</th>
                <th>Api</th>
                <th>Action</th>
                <th>Description</th>
                <th>Created at</th>
            </thead>
            <tbody>
            <?php foreach($logs as $log) {?>
                <tr>
                    <td><?php echo $log->id;?></td>
                    <td><?php echo Html::encode($api->key);?></td>
                    <td>
                    <?php if($log->getIsCreateVps()) {?>
                    Create Vps
                    <?php } elseif ($log->getIsRestartVps()) {?>
                    Restart Vps
                    <?php } elseif ($log->getIsStopVps()) {?>
                    Stop Vps
                    <?php } else if($log->getIsStartVps()) {?>
                    Start Vps
                    <?php } else {?>
                    None
                    <?php }?>
                    </td>
                    <td><?php echo ($log->description ? Html::encode($log->description) : 'None');?></td>
                    <td><?php echo date('d M Y - H:i', $log->created_at);?></td>
                </tr>
            <?php }?>
            </body>
        </table>
        
        <?php echo \yii\widgets\LinkPager::widget(['pagination' => $pages]);?>
    </div>
</div>
<!-- END content -->