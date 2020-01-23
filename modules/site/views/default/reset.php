<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

Yii::$app->setting->title .= ' - reset password';

$template = '{input}{error}';

?>
<div class="content">
    <div class="container">
        <div class="col-md-4 col-md-offset-4">
            <div class="title">
                <h3>Reset Password <p>Please enter your new password</p></h3>
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