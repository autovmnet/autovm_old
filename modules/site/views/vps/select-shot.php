<?php
use yii\helpers\Url;
use yii\helpers\Html;

$this->registerJs('
    $("#screate").click(function(e){
    
        self = $(this);
        
        vpsId = ' . $id . ';

        $.ajax({
            type:"POST",
            dataType:"JSON",
            url:"' . Yii::$app->urlManager->createUrl('/site/vps/create-shot') . '",
            data:{id:vpsId},

            beforeSend:function() {
            	new simpleAlert({title:"Creating", content:"Please wait a moment..."});
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
    
    $("#sreverse").click(function(e){
    
        self = $(this);
        
        vpsId = ' . $id . ';

        $.ajax({
            type:"POST",
            dataType:"JSON",
            url:"' . Yii::$app->urlManager->createUrl('/site/vps/reverse-shot') . '",
            data:{id:vpsId},

            beforeSend:function() {
            	new simpleAlert({title:"Reversing", content:"Please wait a moment..."});
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


<?php if($vps->getCanSnapshot()) {?>
<table class="table select-os-table">
    <tbody>
    <tr>
        <td width="100%" colspan="2">
            <p>You can create or reverse snapshot here</p>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <button class="label btn-red btn teal waves-effect waves-light" id="screate" type="button">Create</button>
            <button class="btn waves-effect waves-light amber" id="sreverse" type="button" style="margin-left:10px;">Or reverse</button>
        </td>
    </tr>
    </tbody>
</table>
<?php } else {?>
<table class="table select-os-table">
    <tbody>
    <tr>
        <td width="100%" colspan="2">
            <p>Sorry, but you are not allowed to use this feature</p>
        </td>
    </tr>
    </tbody>
</table>
<?php }?>