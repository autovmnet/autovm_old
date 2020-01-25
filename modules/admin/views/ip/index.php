<?php 
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;
?>

<!-- content -->
<div class="content user">     
    <div class="col-md-12">
<div class="title_up">
<h3>
IP list
</h3>
</div>
        
        <?php echo Html::beginForm(Yii::$app->urlManager->createUrl('/admin/ip/delete'), 'post', ['class' => 'delete']);?>
        
        <?php 

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax'=>true,
                'columns' => [
                    [
                        'label' => 'Select',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<label class="checkbox"><input type="checkbox" name="data[]" value="' . $model->id . '"><span></span></label>';
                        }
                    ],
                    [
                        'attribute' => 'server_id',
                        'label' => 'Server',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return $model->server->name;
                        }
                    ],
                    [
                        'attribute' => 'ip', 'filterInputOptions' => ['class' => 'form-control', 'placeholder' => 'Search'],
                    ],
                    'gateway',
                    'netmask',
                    [
                        'label' => 'mac',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return "<input type='text' class='mac' value='".$model->mac_address."' data=".$model->id." >";
                        }
                    ],
                    [
                        'attribute' => 'is_public',
                        'label' => 'Is Public',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ($model->getIsPublic() ? '<b class="text-success">Yes</b>' : '<b class="text-danger">No</b>');
                        }
                    ],
                    [
                        'label' => 'edit',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/ip/edit', 'id' => $model->id]) . '"><i class="fa fa-edit"></i></a>';
                        }
                    ],
                ],
                'export'=>false
            ]);
        ?>
        
        <a href="<?php echo Yii::$app->urlManager->createUrl('/admin/server/index');?>" class="btn btn-primary waves-effect waves-light"><i class="fa fa-plus"></i>Create</a>
        <button type="button" class="btn btn-danger btn-delete"><i class="fa fa-remove"></i>Delete</button>
        <br><br><hr>
        
        <?php echo Html::endForm();?>
    </div>
</div>
<?php

$url = \yii\helpers\Url::to(['ip/index']);

$js = <<<EOT

jQuery('.btn-delete').click(function() {

    confirm = confirm('All of the virtual servers belongs to selected ips will be deleted');
    
    if (confirm) {
        jQuery('.delete').submit();
    }
});


$( ".mac" ).change(function() {

  self = $(this);

    var value=$(this).val();
    var id=$(this).attr('data');

    $.post( "$url", { id: id, value: value })
        .done(function( data ) {
            if(data==1)
            {
                self.parent().html("Data saved");
            }
        });

});
EOT;

$this->registerJs($js);
