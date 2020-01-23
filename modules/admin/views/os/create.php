<?php use yii\widgets\ActiveForm;?>
<!-- content -->
<div class="content">     
    <div class="col-md-6">
        <?php echo \app\widgets\Alert::widget();?>
        <?php $form = ActiveForm::begin(['enableClientValidation' => true]);?>
            <?php echo $form->field($model, 'name');?>
            <?php echo $form->field($model, 'type');?>
           <?php echo $form->field($model, 'operation_system')->dropDownList(\app\models\Os::getOperationSystem());?>
           <?php echo $form->field($model, 'adapter')->dropDownList(\app\models\Os::getAdapters());?>
           <?php echo $form->field($model, 'guest')->dropDownList(\app\models\Os::getGuests());?>
	       <?php echo $form->field($model, 'username');?>
	       <?php echo $form->field($model, 'password')->passwordInput();?>

           <?php echo $form->field($model, 'status')->dropDownList(\app\models\Os::getStatusList());?>
			
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
