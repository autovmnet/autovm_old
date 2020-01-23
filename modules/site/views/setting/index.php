<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

Yii::$app->setting->title .= ' - settings';

?>
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <?php echo \app\widgets\Alert::widget();?>
            </div>
            <div class="col s12">
                <div class="title">
                    <h3><?php echo Yii::t('app', 'Profile');?> <p><?php echo Yii::t('app', 'You can change your profile informations');?></p></h3>
                </div>
                <?php $form = ActiveForm::begin(['action' => Url::toRoute(['setting/profile'])]);?>
                <?php echo $form->field($user, 'first_name');?>
                <?php echo $form->field($user, 'last_name');?>
                <div class="form-group">
                    <button type="submit" class="btn btn-success"><?php echo Yii::t('app', 'Update');?></button>
                </div>
                <?php ActiveForm::end();?>
            </div>
            
            <div class="col s12">
                <div class="title">
                    <h3><?php echo Yii::t('app', 'Password');?> <p><?php echo Yii::t('app', 'You can change your account password');?></p></h3>
                </div>
                <?php $form = ActiveForm::begin(['action' => Url::toRoute(['setting/password'])]);?>
                <?php echo $form->field($password, 'password')->passwordInput();?>
                <?php echo $form->field($password, 'repeatPassword')->passwordInput();?>
                <div class="form-group">
                    <button type="submit" class="btn btn-success"><?php echo Yii::t('app', 'Update');?></button>
                </div>
                <?php ActiveForm::end();?>
            </div>
            
            <div class="col s12">
                <div class="title">
                    <h3><?php echo Yii::t('app', 'Email');?> <p><?php echo Yii::t('app', 'You can change your account email');?></p></h3>
                </div>
                <?php $form = ActiveForm::begin(['action' => Url::toRoute(['setting/email'])]);?>
                <?php echo $form->field($email, 'email');?>
                <div class="form-group">
                    <button type="submit" class="btn btn-success"><?php echo Yii::t('app', 'Update');?></button>
                </div>
                <?php ActiveForm::end();?>
            </div>
        </div>
    </div>
</div>
