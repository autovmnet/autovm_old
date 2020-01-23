<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

Yii::$app->setting->title .= ' - create a new email';

$template = '{input}{error}';

?>
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <div class="title">
                    <h3>Create Email <p>You need to confirm your email address</p></h3>
                </div>
                <?php echo \app\widgets\Alert::widget();?>
                <?php $form = ActiveForm::begin(['fieldConfig' => ['template' => $template]]);?>
                    <?php echo $form->field($model, 'email')->textInput(['placeholder' => 'Email']);?>
                    <div class="form-group">
                        <button type="submit" class="btn waves-light waves-effect amber">Create</button>
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
        jQuery('.content .container .row .s12').html('<div class="title"><h3>Create Email<p>You need to confirm your email address</p></h3></div><form id="'+formID+'" action="'+formAction+'" method="post" role="form"><input type="hidden" name="'+inputTypeHiddenName+'" value="'+inputTypeHiddenValue+'"><div class="input-field form-group field-useremail-email required has-error"><input type="text" id="useremail-email" class="form-control" name="UserEmail[email]"><label for="useremail-email">New Email</label><p class="help-block help-block-error">Email cannot be blank.</p></div><div class="form-group"><button type="submit" class="btn waves-light waves-effect amber">Create</button></div></form>');

        jQuery('.fixed-action-btn a').attr('onClick','saveForm()');
        jQuery('.fixed-action-btn a i').html('save');
        jQuery('.fixed-action-btn a i').css('font-size','2.2rem');
    });
    function saveForm(){
        jQuery('form').submit();
    }
</script>