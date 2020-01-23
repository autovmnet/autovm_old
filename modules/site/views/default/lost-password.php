<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

Yii::$app->setting->title .= ' - lost password';

$template = '{input}{error}';

?>
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <div class="title">
                    <h3><?php echo Yii::t('app', 'Lost Password');?><p><?php echo Yii::t('app', 'Forgot your password? Do not worry about that');?></p></h3>
                </div>
                <?php echo \app\widgets\Alert::widget();?>
                <?php $form = ActiveForm::begin(['fieldConfig' => ['template' => $template]]);?>
                    <?php echo $form->field($model, 'email')->textInput(['placeholder' => 'Email']);?>
                    <?php echo $form->field($model, 'verifyCode')->textInput(['placeholder' => 'Verify Code'])->widget(\yii\captcha\Captcha::className(), ['template' => '<div class="row"><div class="col-lg-8">{input}</div><div class="col-md-3">{image}</div></div>', 'captchaAction' => '/site/default/captcha'])->label(false); ?>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success"><?php echo Yii::t('app', 'Get Password');?></button>
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
        var capthaImgSRC = jQuery('.content .container .row .s12 form img').attr('src');
        jQuery('.content .container .row .s12').html('<div class="title"><h3><?php echo Yii::t('app', 'Lost Password');?><p><?php echo Yii::t('app', 'Forgot your password? Do not worry about that');?></p></h3></div><form id="'+formID+'" action="'+formAction+'" method="post" role="form"><input type="hidden" name="'+inputTypeHiddenName+'" value="'+inputTypeHiddenValue+'"><div class="input-field form-group field-lostpasswordform-email required"><input type="text" id="lostpasswordform-email" class="form-control" name="LostPasswordForm[email]"><label for="lostpasswordform-email">Email</label><p class="help-block help-block-error"></p></div><div class="input-field form-group field-lostpasswordform-verifycode"><div class="row"><div class="col s10"><input type="text" id="lostpasswordform-verifycode" class="form-control" name="LostPasswordForm[verifyCode]"><label for="lostpasswordform-verifycode">Verify Code</label></div><div class="col s2"><img id="lostpasswordform-verifycode-image" src="'+capthaImgSRC+'" alt=""></div></div><p class="help-block help-block-error"></p></div><div class="form-group"><button type="submit" class="btn waves-light waves-effect amber"><?php echo Yii::t('app', 'Get Password');?></button></div></form>');

        jQuery('.fixed-action-btn a').attr('onClick','saveForm()');
        jQuery('.fixed-action-btn a i').html('lock_open');
        jQuery('.fixed-action-btn a i').css('font-size','2.2rem');
    });
    function saveForm(){
        jQuery('form').submit();
    }
</script>
