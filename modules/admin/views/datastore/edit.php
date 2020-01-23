<?php use yii\widgets\ActiveForm;?>
<!-- content -->
<div class="content">     
    <div class="col-md-6">
        <?php echo \app\widgets\Alert::widget();?>
        <?php $form = ActiveForm::begin(['enableClientValidation' => true]);?>
            <?php echo $form->field($model, 'uuid');?>
            <?php echo $form->field($model, 'value');?>
            <?php echo $form->field($model, 'space');?>
            <?php echo $form->field($model, 'is_default')->label('ISO files')->dropDownList(\app\models\Datastore::getDefaultYesNo());?>
            <?php echo $form->field($model, 'vsan')->dropDownList(\app\models\Datastore::getVsanYesNo());?>
            <?php echo $form->field($model, 'is_public')->dropDownList(\app\models\Datastore::getPublicYesNo());?>
        
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
