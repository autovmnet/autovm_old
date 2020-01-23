<?php use yii\widgets\ActiveForm;?>
<!-- content -->
<div class="content">     
    <div class="col-md-6">
        <?php echo \app\widgets\Alert::widget();?>
        <?php $form = ActiveForm::begin(['enableClientValidation' => true]);?>
            <?php echo $form->field($model, 'name');?>
            <?php echo $form->field($model, 'ram')->label('RAM (MB)');?>
            <?php echo $form->field($model, 'cpu_mhz')->label('CPU Speed (MHZ)');?>
            <?php echo $form->field($model, 'cpu_core')->label('CPU Core');?>
            <?php echo $form->field($model, 'hard')->label('Hard (GB)');?>
            <?php echo $form->field($model, 'band_width')->label('Bandwidth (GB)');?>
            <?php echo $form->field($model, 'is_public')->dropDownList(\app\models\Plan::getPublicYesNo());?>

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