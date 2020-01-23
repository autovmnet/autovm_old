<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

Yii::$app->setting->title .= ' - profile informations';

$template = '{input}{error}';

?>
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <div class="title">
                    <h3>Profile <p>You can change your profile informations</p></h3>
                </div>
                <?php echo \app\widgets\Alert::widget();?>
                <?php $form = ActiveForm::begin(['fieldConfig' => ['template' => $template]]);?>
                <?php echo $form->field($model, 'first_name')->textInput(['placeholder' => 'First Name']);?>
                <?php echo $form->field($model, 'last_name')->textInput(['placeholder' => 'Last Name']);?>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
                <?php ActiveForm::end();?>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function (e) {
        var formID = jQuery('.content .container .row .s12 form').attr('id');
        var formAction = jQuery('.content .container .row .s12 form').attr('action');
        var inputTypeHiddenName = jQuery('.content .container .row .s12 form input:first-child').attr('name');
        var inputTypeHiddenValue = jQuery('.content .container .row .s12 form input:first-child').attr('value');
        var inputFirstNameValue = jQuery('.content .container .row .s12 form input#user-first_name').attr('value');
        var inputLastNameValue = jQuery('.content .container .row .s12 form input#user-last_name').attr('value');
        jQuery('.content .container .row .s12').html('<div class="title"><h3>Profile<p>You can change your profile informations</p></h3></div><form id="'+formID+'" action="'+formAction+'" method="post" role="form"><input type="hidden" name="'+inputTypeHiddenName+'" value="'+inputTypeHiddenValue+'"><div class="input-field form-group field-user-first_name required has-success"><input type="text" id="user-first_name" class="form-control" name="User[first_name]" value="'+inputFirstNameValue+'"><label for="user-first_name">First Name</label><p class="help-block help-block-error"></p></div><div class="input-field form-group field-user-last_name required"><input type="text" id="user-last_name" class="form-control" name="User[last_name]" value="'+inputLastNameValue+'"><label for="user-first_name">Last Name</label><p class="help-block help-block-error"></p></div><div class="form-group"><button type="submit" class="btn waves-light waves-effect amber">Update</button></div></form>');
        jQuery('.fixed-action-btn a').attr('onClick','saveForm()');
        jQuery('.fixed-action-btn a i').html('save');
        jQuery('.fixed-action-btn a i').css('font-size','2.2rem');
    });
    function saveForm(){
        jQuery('form').submit();
    }
</script>