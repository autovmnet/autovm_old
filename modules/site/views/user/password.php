<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

Yii::$app->setting->title .= ' - account password';

$template = '{input}{error}';

?>
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <div class="title">
                    <h3>Password <p>Please change your password only for security reasons</p></h3>
                </div>
                <?php echo \app\widgets\Alert::widget();?>
                <?php $form = ActiveForm::begin(['fieldConfig' => ['template' => $template]]);?>
                <?php echo $form->field($model, 'password')->passwordInput(['placeholder' => 'Password']);?>
                <?php echo $form->field($model, 'repeatPassword')->passwordInput(['placeholder' => 'Repeat Password']);?>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Change</button>
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
        jQuery('.content .container .row .s12').html('<div class="title"><h3>Password<p>Please change your password only for security reasons</p></h3></div><form id="'+formID+'" action="'+formAction+'" method="post" role="form"><input type="hidden" name="'+inputTypeHiddenName+'" value="'+inputTypeHiddenValue+'"><div class="input-field form-group field-userpassword-password required has-success"><input type="password" id="userpassword-password" class="form-control" name="UserPassword[password]"><label for="userpassword-password">New Password</label><p class="help-block help-block-error">Password cannot be blank.</p></div><div class="input-field form-group field-userpassword-repeatpassword required"><input type="password" id="userpassword-repeatpassword" class="form-control" name="UserPassword[repeatPassword]"><label for="userpassword-repeatpassword">Repeat Password</label><p class="help-block help-block-error"></p></div><div class="form-group"><button type="submit" class="btn waves-light waves-effect amber">Change</button></div></form>');

        jQuery('.fixed-action-btn a').attr('onClick','saveForm()');
        jQuery('.fixed-action-btn a i').html('save');
        jQuery('.fixed-action-btn a i').css('font-size','2.2rem');
    });
    function saveForm(){
        jQuery('form').submit();
    }
</script>