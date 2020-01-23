<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

Yii::$app->setting->title .= ' - login';

$template = '{input}{error}';

?>
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <div class="title">
                    <h3> <?php echo Yii::t('app', 'Login Form');?> <p><?php echo Yii::t('app', 'Please login to your account');?></p></h3>
                </div>
                <?php echo \app\widgets\Alert::widget();?>
                <?php $form = ActiveForm::begin(['fieldConfig' => ['template' => $template]]);?>
                    <?php echo $form->field($model, 'email')->textInput(['placeholder' => Yii::t('app', 'Email')]);?>
                    <?php echo $form->field($model, 'password')->passwordInput(['placeholder' => Yii::t('app', 'Password')]);?>
                    <div class="form-group">
                        <label class="checkbox"><input type="checkbox" name="LoginForm[rememberMe]" value="1"> <span></span> <?php echo Yii::t('app', 'Remember me next time');?></label>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success"><?php echo Yii::t('app', 'Login');?></button>
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
        jQuery('.content .container .row .s12').html('<div class="title"><h3><?php echo Yii::t('app', 'Login Form');?> <p><?php echo Yii::t('app', 'Please login to your account');?></p></h3></div><form id="'+formID+'" action="'+formAction+'" method="post" role="form"><input type="hidden" name="'+inputTypeHiddenName+'" value="'+inputTypeHiddenValue+'"><div class="input-field form-group field-loginform-email required has-error"><input type="text" id="loginform-email" class="form-control" name="LoginForm[email]"><label for="loginform-email"><?php echo Yii::t('app', 'Email');?></label><p class="help-block help-block-error"><?php echo Yii::t('app', 'Email cannot be blank.');?></p></div><div class="input-field form-group field-loginform-password required has-error"><input type="password" id="loginform-password" class="form-control" name="LoginForm[password]"><label for="loginform-password"><?php echo Yii::t('app', 'Password');?></label><p class="help-block help-block-error"><?php echo Yii::t('app', 'Password cannot be blank.');?></p></div><div class="form-group" style="margin-top: 3%;"><label class="checkbox"><input type="checkbox" name="LoginForm[rememberMe]" value="1"><span></span> <?php echo Yii::t('app', 'Remember me next time');?></label><div class="form-group"><button type="submit" class="btn waves-light waves-effect amber"><?php echo Yii::t('app', 'Login');?></button></div></form>');

        jQuery('.fixed-action-btn a').attr('onClick','saveForm()');
        jQuery('.fixed-action-btn a i').html('forward');
        jQuery('.fixed-action-btn a i').css('font-size','2.2rem');
    });
    function saveForm(){
        jQuery('form').submit();
    }
</script>
