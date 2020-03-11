<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<!-- content -->
<div class="content user seeting">
    
    <div class="col-md-12">
        <div class="userstyle" style="height:auto!important;min-height:auto!important">
            <div class="title_up"><h3>Cron jobs</h3></div>
            <p style="line-height:30px;">Please put these commands to your cron jobs</p>
            <p style="font-size:12px;line-height:30px;">*/5 * * * * php <?php echo Yii::getAlias('@app');?>/yii cron/index</p>
            <p style="font-size:12px;line-height:30px;">0 0 * * * php <?php echo Yii::getAlias('@app');?>/yii cron/reset</p>
        </div>
    </div>
    
    <div class="col-md-6">
    <div class="userstyle">
        <div class="title_up"><h3>Settings</h3></div>

        <?php $form = ActiveForm::begin(['enableClientValidation' => true]);?>
            <?php echo $form->field($model, 'title');?>
            <?php echo $form->field($model, 'language')->dropDownList(Yii::$app->lang->langs);?>
            <?php echo $form->field($model, 'terminate')->dropDownList([1 => 'Yes', 2 => 'No']);?>
            <?php echo $form->field($model, 'change_limit');?>
            <?php echo $form->field($model, 'from_port');?>
            <?php echo $form->field($model, 'to_port');?>

            <div class="margin-top-10"></div>
            <div class="margin-top-10"></div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                <a href="<?php echo Url::toRoute(['log/index']);?>" class="btn btn-primary waves-effect waves-light">System logs</a>
            </div>
        <?php ActiveForm::end();?>
    </div>   </div>

    <div class="col-md-6">
        <div class="userstyle" style="height:auto!important;min-height:auto!important">
            <div class="title_up"><h3>Configuration</h3></div>
            <p style="line-height:30px;">All these configuration must be OK</p>
            <p style="line-height:30px;">php exec <span style="float:right;"><?php echo (function_exists('exec') ? 'OK' : 'NO');?></span></p>
            <p style="line-height:30px;">php allow_url_fopen <span style="float:right;"><?php echo (ini_get('allow_url_fopen') ? 'OK' : 'NO');?></span></p>
            <p style="line-height:30px;">php zip extension <span style="float:right;"><?php echo (extension_loaded('zip') ? 'OK' : 'NO');?></span></p>
            <p style="line-height:30px;">php max_execution_time <span style="float:right;"><?php echo ini_get('max_execution_time');?></span></p>
        </div>
    </div>


</div>
<!-- END content -->

<style>
.form-control{
    display:inline-block;
}
</style>
