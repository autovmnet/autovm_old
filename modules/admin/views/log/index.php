<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
?>
<!-- content -->
<div class="content user">
    <div class="col-md-12">
        <div class="title_up"><h3>System logs</h3></div>

        <?php
        Pjax::begin(['id' => 'pjax', 'enablePushState' => false]);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    'description',
                    [
                        'label' => 'created_at',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return date('d M Y - H:i', $model->created_at);
                        }
                    ],
                ],
            ]);
        Pjax::end();
        ?>
    </div>
</div>
<!-- END content -->
