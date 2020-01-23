<?php 
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
?>
<!-- content -->
<div class="content">     
    <div class="col-md-12">
        <?php 
        Pjax::begin(['id' => 'pjax', 'enablePushState' => false]);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    [
                        'label' => 'Server',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/server/edit', 'id' => $model->server->id]) . '">' . Html::encode($model->server->name) . '</a>';
                        }
                    ],
                    [
                        'label' => 'Ip Address',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ($model->ip ? Html::encode($model->ip->ip) : ' No IP');
                        }
                    ],
                    [
                        'label' => 'Operation System',
                        'value' => 'os.name',
                    ],
                    [
                        'label' => 'plan',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/plan/edit', 'id' => isset($model->plan->id)?$model->plan->id:'']) . '">' . Html::encode(isset($model->plan->name)?$model->plan->name:'') . '</a>';
                        }
                    ],
                    [
                        'label' => 'status',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ($model->getIsActive() ? '<b class="text-success"> Active</b>' : '<b class="text-danger"> Inactive</b>');
                        }
                    ],
                    [
                        'label' => 'edit',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/vps/edit', 'id' => $model->id]) . '"><i class="fa fa-edit"></i></a>';
                        }
                    ],
                    [
                        'label' => 'view',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/vps/view', 'id' => $model->id]) . '"><i class="fa fa-search"></i></a>';
                        }
                    ],
                ],
            ]);
        Pjax::end();
        ?>
    </div>
</div>
<!-- END content -->