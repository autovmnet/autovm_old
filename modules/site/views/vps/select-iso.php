<?php
use yii\helpers\Url;
use yii\helpers\Html;

$this->registerJs('
    $("#iso").click(function(e){
    
        self = $(this);
        
        iso = $("input[name=iso]:checked").val();
        vpsId = ' . $vpsId . ';

        $.ajax({
            type:"POST",
            dataType:"JSON",
            url:"' . Yii::$app->urlManager->createUrl('/site/vps/iso') . '",
            data:{id:vpsId, iso:iso},

            beforeSend:function() {
            	new simpleAlert({title:"Mounting", content:"Please wait a moment..."});
            },
            
            success:function(data) {
            	if(data.ok) {
                    new simpleAlert({title:"Action Status", content:"Done successfuly"});
                } else {
                    new simpleAlert({title:"Action Status", content:"There is an error, please try again"});
                }
            }
        });
    });
    
    $("#isou").click(function(e){
    
        self = $(this);
        
        vpsId = ' . $vpsId . ';

        $.ajax({
            type:"POST",
            dataType:"JSON",
            url:"' . Yii::$app->urlManager->createUrl('/site/vps/isou') . '",
            data:{id:vpsId},

            beforeSend:function() {
            	new simpleAlert({title:"Unmounting", content:"Please wait a moment..."});
            },
            
            success:function(data) {
                if(data.ok) {
                    new simpleAlert({title:"Action Status", content:"Done successfuly"});
                } else {
                    new simpleAlert({title:"Action Status", content:"There is an error, please try again"});
                }
            }
        });
    });
');

?>

<style type="text/css">
    .select-os-table {
        box-shadow: none !important;
        border: 0 !important;
    }

    .select-os-table td {
        border: 0 !important;
    }
</style>



<table class="table select-os-table">
    <tbody>
    <tr>
        <td width="100%" colspan="2">
            <div class="row">
                <?php foreach ($items as $item) { ?>
                    <div class="col s6">
                        <label class="checkbox"><input type="radio" name="iso" value="<?php echo $item->id; ?>">
                            <span></span> <?php echo Html::encode($item->name); ?></label>
                    </div>
                <?php } ?>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <button class="label btn-red btn teal waves-effect waves-light" id="iso" type="button">Mount now</button>
            <button class="btn waves-effect waves-light amber" id="isou" type="button" style="margin-left:10px;">Unmount</button>
        </td>
    </tr>
    </tbody>
</table>