<?php 
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
?>
<!-- content -->
<div class="content user">     
    <div class="col-md-12">
<div class="title_up"><h3>Server list</h3></div>
       <div class="table-responsive"> 
           
            <?php echo Html::beginForm(Yii::$app->urlManager->createUrl('/admin/server/delete'));?>
           
        <?php 
        Pjax::begin(['id' => 'pjax', 'enablePushState' => false]);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'label' => 'Select',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<label class="checkbox"><input type="checkbox" name="data[]" value="' . $model->id . '"><span></span></label>';
                        }
                    ],
                    'id',
                    'name',
                    ['attribute' => 'ip', 'filterInputOptions' => ['class' => 'form-control', 'placeholder' => 'Search']],
                    'port',
                    [
                        'label' => 'Actions',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/ip/create', 'id' => $model->id]) . '">Add Ip</a> <b>/</b> <a href="' .Yii::$app->urlManager->createUrl(['/admin/datastore/create', 'id' => $model->id]) . '">Add Datastore</a>';
                        }
                    ],
                    [
                        'label' => 'edit',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/server/edit', 'id' => $model->id]) . '"><i class="fa fa-edit"></i></a>';
                        }
                    ],
                    [
                        'label' => 'view',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/server/view', 'id' => $model->id]) . '"><i class="fa fa-search"></i></a>';
                        }
                    ],
                ],
            ]);
        Pjax::end();
        ?>
</div>
       
        <a href="<?php echo Yii::$app->urlManager->createUrl('/admin/server/create');?>" class="btn btn-primary waves-effect waves-light"><i class="fa fa-plus"></i>Create </a>
        <button type="submit" class="btn btn-danger"><i class="fa fa-remove"></i>Delete</button>
        <br><br><hr>
        <?php echo Html::endForm();?>
    </div>
</div>
<!-- END content -->
