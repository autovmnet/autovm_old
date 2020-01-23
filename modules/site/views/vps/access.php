<?php
use yii\helpers\Html;

$this->registerJs("
    var vpsId = " . $vps->id . ";

    $(document).ready(function () {
    \"use strict\";

});

    $.ajax({
        url:baseUrl + '/site/vps/bandwidth',
        type:'POST',
        dataType:'JSON',
        data:{vpsId:vpsId},
        success:function(data){
            Morris.Line({
                element: 'chart{$vps->id}',
                data: data,
                xkey: 'date',
                ykeys: ['total'],
                labels: ['Bandwidth MB'],
                smooth:false,
                lineWidth:2,
                lineColors: ['#189C7E'],
            });
        }
    });
");

?>

<style type="text/css">
.view-vps-table{
    box-shadow:none;
}
</style>
<?= Yii::$app->session->set('username', $vps->server->username); ?>
        <div class="col-md-12 access">
            <table class="bordered vpss striped centered responsive-tables">
                <thead>
                    <th><p><?php echo Yii::t('app', 'IP Address');?></p></th>
                    <th><p><?php echo Yii::t('app', 'Server');?></p></th>
                    <th><p><?php echo Yii::t('app', 'Operating System');?></p></th>
                    <th><p><?php echo Yii::t('app', 'Memory');?></p></th>
                    <th><p><?php echo Yii::t('app', 'CPU Cores');?></p></th>
                    <th><p><?php echo Yii::t('app', 'CPU MHZ');?></p></th>
                    <th><p><?php echo Yii::t('app', 'Disk Space');?></p></th>
                    <th><p><?php echo Yii::t('app', 'Username');?></p></th>
                    <th><p><?php echo Yii::t('app', 'Disk');?></p></th>                    
                    <th colspan="2"><p><?php echo Yii::t('app', 'Bandwidth');?></p></th>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo Html::encode(isset($vps->ip->ip)?$vps->ip->ip:'');?></td>
                        <td><?php echo Html::encode(isset($vps->server)?$vps->server->name:'');?></td>
                        <td><?php echo Html::encode(isset($vps->os->name)?$vps->os->name:'');?></td>

                        <td><?php
                            //var_dump($vps);exit;
                            if($vps->plan_type==VpsPlansTypeDefault) {
                                echo $vps->plan->ram;
                            }
                            else {
                                echo $vps->vps_ram;
                            }
                            ?>
                        <td>
                            <?php
                            //var_dump($vps);exit;
                            if($vps->plan_type==VpsPlansTypeDefault) {
                                echo $vps->plan->cpu_core;
                            }
                            else {
                                echo $vps->vps_cpu_core;
                            }
                            ?> Core</td>
                        <td><?php
                            //var_dump($vps);exit;
                            if($vps->plan_type==VpsPlansTypeDefault) {
                                echo $vps->plan->cpu_mhz;
                            }
                            else {
                                echo $vps->vps_cpu_mhz;
                            }
                            ?> MHZ</td>
                        <td><?php
                            //var_dump($vps);exit;
                            if($vps->plan_type==VpsPlansTypeDefault) {
                                echo $vps->plan->hard;
                            }
                            else {
                                echo $vps->vps_hard;
                            }
                            ?> GB</td>
                            <td><?php echo Html::encode(isset($vps->os->username)?$vps->os->username:'');?></td>
                            <td><?php echo Html::encode(isset($vps->disk)?$vps->disk:'');?></td>

                        <td colspan="2">
                            <?php
                            if($vps->plan_type==VpsPlansTypeDefault)
                                echo number_format($used_bandwidth/1024, 3) .' /'. $vps->plan->band_width;
                            else
                                echo number_format($used_bandwidth/1024, 3) .' / '. $vps->vps_band_width;?>
                            GB
                        </td>
                    </tr>
                    <tr>
                        <td><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="1"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="1">computer</i> <br><?php echo Yii::t('app', 'Change Os');?></p></a></td>
                       <td><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="10"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="10">album</i> <br><?php echo Yii::t('app', 'ISO');?></p></a></td>
                        <td><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="2"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="2">autorenew</i> <br><?php echo Yii::t('app', 'Restart');?></p></a></td>
                        <td><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="3"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="3">stop</i> <br> <?php echo Yii::t('app', 'Stop');?></p></a></td>
                        <td><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="4"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="4">play_arrow</i> <br><?php echo Yii::t('app', 'Start');?></p></a></td>
                        <td><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="5"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="5">gps_fixed</i> <br><?php echo Yii::t('app', 'VM Status');?></a></td>
                        <td><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="6"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="6">timeline</i> <br><?php echo Yii::t('app', 'Monitor');?></a></td>
                        <td><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="7"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="7">layers</i> <br><?php echo Yii::t('app', 'Extend hard');?></a></td>
                        <td><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="8"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="8">history</i> <br><?php echo Yii::t('app', 'Action logs');?></a></td>
                        <td><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="9"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="9">developer_board</i> <br><?php echo Yii::t('app', 'Console');?></a></td>
                        <td><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="11"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="11">backup</i> <br><?php echo Yii::t('app', 'SnapShot');?></a></td>
                    </tr>
                </tbody>
            </table>
            <div id="chart<?php echo $vps->id;?>" style="width:100%;height:350px"></div>
        </div>



