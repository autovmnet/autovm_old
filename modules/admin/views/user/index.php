<?php 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
?>
<!-- content -->
<div class="content user">     
    <div class="col-md-12">
<div class="title_up">
<h3>
Users list
</h3>
</div>
        
        <?php echo Html::beginForm(Yii::$app->urlManager->createUrl('/admin/user/delete'));?>

        <?php 
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
                    'last_name',
                    [
                        'attribute' => 'email',
                        'value' => 'email.email'
                    ],
                    [
                        'attribute' => 'is_admin',
                        'label' => 'Type',
                        'format' => 'raw',
                        'value' => function($model) {
                            return ($model->getIsAdmin() ? 'admin' : 'user');
                        }
                    ],
                    [
                        'label' => 'VM',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' .Yii::$app->urlManager->createUrl(['/admin/vps/create', 'id' => $model->id]) . '">Create</a>';
                        }
                    ],
                    [
                        'label' => 'VMs',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/user/vps', 'id' => $model->id]) . '"><i class="fa fa-tv"></i></a>';
                        }
                    ],
                    [
                        'label' => 'edit',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/user/edit', 'id' => $model->id]) . '"><i class="fa fa-edit"></i></a>';
                        }
                    ],
                    [
                        'label' => 'password',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/user/password', 'id' => $model->id]) . '"><i class="fa fa-edit"></i></a>';
                        }
                    ],
                    [
                        'label' => 'login',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/user/login', 'id' => $model->id]) . '"><i class="fa fa-sign-in"></i></a>';
                        }
                    ],
                    [
                        'label' => 'Security token',
                        'format' => 'raw',
                        'value' => function($model) {
                            return '<a class="reset" href="' . Yii::$app->urlManager->createUrl(['/admin/user/auth', 'id' => $model->id]) . '">Reset</a>';
                        },
                    ],
                ],
            ]);
        ?>
        
        <a href="<?php echo Yii::$app->urlManager->createUrl('/admin/user/create');?>" class="btn btn-primary waves-effect waves-light"><i class="fa fa-plus"></i>Create</a>
        <button type="submit" class="btn btn-danger"><i class="fa fa-remove"></i>Delete</button>
        <br><br><hr>

        <?php echo Html::endForm();?>
    </div>
</div>
<!-- END content -->

<?php

$js = <<<EOT

jQuery(".reset").click(function(e) {

    e.preventDefault();

    self = jQuery(this);

    jQuery.getJSON(self.attr("href"), function(data) {
        
        if (data.fine) {
            self.text("Done");
        }
    });
});

EOT;

$this->registerJs($js);
