<?php
use yii\helpers\Html;

$this->registerJs('
    $(function(){
        var vpsId = ' . $vpsId . ';
        $(".pagination a").click(function(e){
            e.preventDefault();

            url = $(this).attr("href");
            $.ajax({
                url:url,
                type:"POST",
                dataType:"HTML",
                data:{vpsId:vpsId},
                success:function(data){
                    $("#data").html(data);
                }
            });
        });
    });
');
?>

<div id="data">

<table class="bordered vpss striped centered responsive-table">
    <thead><th>Name</th> <th>Description</th> <th>Created At</th></thead>
    <tbody>
        <?php foreach($actions as $action) {?>
            <tr>
                <td>
                <?php if($action->getIsStart()) {?>
                    Start
                <?php } elseif ($action->getIsStop()) {?>
                    Stop
                <?php } elseif ($action->getIsRestart()) {?>
                    Restart
                <?php } elseif ($action->getIsInstall()) {?>
                    Install
                <?php }?>
                </td>
                <td><?php echo Html::encode($action->description ? $action->description : 'NONE');?></td>
                <td><?php echo date('d M Y - H:i', $action->created_at);?></td>
            </tr>
        <?php }?>
    </tbody>
</table>

<?php echo \yii\widgets\LinkPager::widget(['pagination' => $pages]);?>

</div>