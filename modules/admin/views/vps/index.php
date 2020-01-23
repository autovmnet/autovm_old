<?php 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
?>
<!-- content -->
<div class="content user">     
    <div class="col-md-12">
        <div class="title_up"><h3>Virtual machines</h3></div>

        <?php echo Html::beginForm(Yii::$app->urlManager->createUrl('/admin/vps/delete'));?>
        
        <?php 
        //Pjax::begin(['id' => 'pjax', 'enablePushState' => false]);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    [
                        'label' => 'Select',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<label class="checkbox"><input type="checkbox" name="data[]" value="' . $model->id . '"><span></span></label>';
                        }
                    ],
                    
                    [
                        'label' => 'Server',
                        'attribute' => 'server_id',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/server/edit', 'id' => isset($model->server->id)?$model->server->id:'']) . '">' . Html::encode(isset($model->server->name)?$model->server->name:'') . '</a>';
                        }
                    ],
                    [
                        'attribute' => 'ip',
                        'label' => 'Ip Address',
                        'format' => 'raw',
                        'filterInputOptions' => ['class' => 'form-control', 'placeholder' => 'Search'],
                        'value' => function ($model) {
                            return ($model->ip ? Html::encode($model->ip->ip) : ' No IP');
                        }
                    ],
                    [
                        'attribute' => 'email',
                        'label' => 'User Email',
                        'value' => 'user.email.email',
                        'filterInputOptions' => ['class' => 'form-control', 'placeholder' => 'Search'],
                    ],
                    [
                        'label' => 'OS',
                        'value' => 'os.name',
                    ],
                    [
                        'label' => 'Plan',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if(isset($model->plan->id))
                                return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/plan/edit', 'id' => $model->plan->id]) . '">' . Html::encode($model->plan->name) . '</a>';
                            else
                                return '';
                        }
                    ],
                    [
                        'label' => 'Access',
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ($model->getIsActive() ? '<b class="text-success"> Enable</b>' : '<b class="text-danger"> Disable</b>');
                        }
                    ],
                    [
                        'label' => 'Terminate',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a class="terminate" href="' . Yii::$app->urlManager->createUrl(['/admin/vps/terminate', 'id' => $model->id]) . '" style="color:red;"><i class="fa fa-close"></i></a>';   
                        }
                    ],
                    [
                        'label' => 'Edit',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/vps/edit', 'id' => $model->id]) . '"><i class="fa fa-edit"></i></a>';
                        }
                    ],
                    [
                        'label' => 'View',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/vps/view', 'id' => $model->id]) . '"><i class="fa fa-search"></i></a>';
                        }
                    ],
                    [
                        'label' => 'Login',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['admin/user/login', 'id' => $model->user->id]) . '"><i class="fa fa-sign-in"></i></a>';
                        }
                    ],                    
                ],
            ]);
        //Pjax::end();
        ?>
        
        <a href="<?php echo Yii::$app->urlManager->createUrl('/admin/user/index');?>" class="btn btn-primary waves-effect waves-light"><i class="fa fa-plus"></i>Create</a>
        <button type="submit" class="btn btn-danger"><i class="fa fa-remove"></i>Delete</button>
        <br><br><hr>
        <?php echo Html::endForm();?>
    </div>
</div>
<!-- END content -->

<?php

$js = <<<EOT

$(".terminate").click(function(e) {
    
    e.preventDefault();

    self = $(this);
        
    ok = confirm('The virtual server will be terminated');
    
    if (ok) {

        self.text("Terminating");

        $.getJSON(self.attr("href"), function(data) {
            if (data.ok) {
                window.location.href = window.location.href
            } else {
                alert("There is an error");
            }

            self.text("Terminate");
        });
    }
});

EOT;

$this->registerJs($js);
