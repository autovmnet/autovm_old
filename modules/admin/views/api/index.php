<?php use yii\helpers\Html;?>
<!-- content -->
<div class="content">     
    <div class="col-md-12">
        <?php echo Html::beginForm(Yii::$app->urlManager->createUrl('/admin/api/delete'));?>
            <a href="<?php echo Yii::$app->urlManager->createUrl('/admin/api/create');?>" class="btn btn-primary waves-effect waves-light"><i class="fa fa-plus"></i>Create</a>
            <button type="submit" class="btn btn-danger"><i class="fa fa-remove"></i>Delete</button>
            <table class="table table-bordered">
                <thead>
                    <th>ID</th>
                    <th>Select</th>
                    <th>Key</th>
                    <th>Created at</th>
                    <th>Updated at</th>
                    <th>Logs</th>
                </thead>
                <tbody>
                <?php foreach($apis as $api) {?>
                    <tr>
                        <td><?php echo $api->id;?></td>
                        <td><label class="checkbox"><input type="checkbox" name="data[]" value="<?php echo $api->id;?>"><span></span></label></td>
                        <td><?php echo Html::encode($api->key);?></td>
                        <td><?php echo date('d M Y - H:i', $api->created_at);?></td>
                        <td><?php echo date('d M Y - H:i', $api->updated_at);?></td>
                        <td><a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/api/log', 'id' => $api->id]);?>"><i class="fa fa-search"></i></a></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        <?php echo Html::endForm();?>
        
        <?php echo \yii\widgets\LinkPager::widget(['pagination' => $pages]);?>
    </div>
</div>
<!-- END content -->