<?php 
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
?>
<!-- content -->
<div class="content user">     
    <div class="col-md-12">
        <div class="title_up"><h3>Datastore list</h3></div>

       <?php echo Html::beginForm(Yii::$app->urlManager->createUrl('/admin/datastore/delete'));?>
        
        <?php 
        Pjax::begin(['id' => 'pjax', 'enablePushState' => false]);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'label' => 'Select',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<label class="checkbox"><input type="checkbox" name="data[]" value="' . $model->id . '"><span></span></label>';
                        }
                    ],
                    'id',
                    [
                        'label' => 'server name',
                        'value' => 'server.name'
                    ],
                    'value',
                    'space',
                    [
                        'label' => 'edit',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/datastore/edit', 'id' => $model->id]) . '"><i class="fa fa-edit"></i></a>';
                        }
                    ],
                ],
            ]);
        Pjax::end();
        ?>
        
         
        <a href="<?php echo Yii::$app->urlManager->createUrl('/admin/server/index');?>" class="btn btn-primary waves-effect waves-light"><i class="fa fa-plus"></i>Create</a>
        <button type="submit" class="btn btn-danger"><i class="fa fa-remove"></i>Delete</button>
        <br><br><hr>
        <?php echo Html::endForm();?>
    </div>
</div>
<!-- END content -->
<?php

$js = <<<EOT

jQuery('form').submit(function() {

    return confirm('All of the virtual servers belongs to selected datastores will be deleted')
});

EOT;

$this->registerJs($js);