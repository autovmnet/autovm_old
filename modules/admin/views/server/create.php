<?php use yii\widgets\ActiveForm;use yii\helpers\Url;?>
<!-- content -->
<div class="content">
    <div class="col-md-6">
        <?php echo \app\widgets\Alert::widget();?>
        <?php $form = ActiveForm::begin(['enableClientValidation' => true]);?>
            <?php echo $form->field($model, 'name');?>
            <?php echo $form->field($model, 'ip');?>
            <?php echo $form->field($model, 'port')->textInput(['value' => '22']);?>
            <?php echo $form->field($model, 'username');?>
            <?php echo $form->field($model, 'password')->passwordInput();?>
            <?php echo $form->field($model, 'network')->label('VM Network Label')->textInput(['value' => 'VM Network']);?>
            <?php echo $form->field($model, 'second_network')->label('Second Network Label');?>
            <?php echo $form->field($model, 'version')->label('VM Hardware version')->dropDownList(\app\models\Server::getVersionList());?>
            <?php echo $form->field($model, 'vcenter_ip');?>
            <?php echo $form->field($model, 'vcenter_username');?>
            <?php echo $form->field($model, 'vcenter_password')->passwordInput();?>
            <?php echo $form->field($model, 'parent_id')->label('Use IP Address from:')->dropDownList(\app\models\Server::getListData(), ['prompt' => 'None']);?>
	    <?php echo $form->field($model, 'virtualization')->dropDownList(\app\models\Server::getVirtualizationList());?>

            <?php echo $form->field($model, 'dns1')->textInput(['value' => '4.2.2.4']);?>
            <?php echo $form->field($model, 'dns2')->textInput(['value' => '8.8.8.8']);?>

            <div style="display:none;">
            <?php echo $form->field($model, 'server_address')->textInput(['value' => Url::toRoute('/candy/default', true) ]);?>
            </div>
                
            <div class="margin-top-10"></div>
            <div class="margin-top-10"></div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                <button type="reset" class="btn btn-danger">Reset</button>
            </div>
        <?php ActiveForm::end();?>
    </div>
</div>
<!-- END content -->
