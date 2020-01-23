<?php
use yii\helpers\Url;
use yii\helpers\Html;

$js = <<<EOT

$("form").submit(function(e) {
	e.preventDefault();
	
	self = $(this);
	
	new simpleAlert({title:"Processing", content:"Please wait a moment..."});
	
	$.post(self.attr("action"), self.serialize(), function(data) {
	
		if (data.ok) {
			new simpleAlert({title:"Success", content:"Your host name has been changed."});
		} else {
			new simpleAlert({title:"Error", content:"Please try again later"});
		}
	});
});

EOT;

$this->registerJs($js);
?>

<style type="text/css">
    .extend-table {
        box-shadow: none !important;
        border: 0 !important;
    }

    .extend-table td {
        border: 0 !important;
    }
</style>
<?php echo Html::beginForm(Url::toRoute(['change-host']));?>
<table class="table extend-table">
    <tbody>
    <input type="hidden" name="id" value="<?php echo $vps->id;?>">
    <tr>
        <td>
            <input type="text" name="host" class="form-control" value="<?php echo Html::encode($vps->hostname);?>">
        </td>
    </tr>
    <tr>
        <td>
            <button type="submit" class="btn btn-success">Change</button>
        </td>
    </tr>
    </tbody>
</table>
<?php echo Html::endForm();?>