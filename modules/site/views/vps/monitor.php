<?php
use yii\helpers\Html;
?>

<style type="text/css">
.select-os-table{
    box-shadow:none!important;
    border:0!important;
}

.select-os-table td{
    border:0!important;
}
</style>

<table class="table select-os-table">
    <tbody>
        <tr>
            <td width="90">RAM</td>
            <td>
                <div class="progress">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo (($usedRam / $ram) * 100);?>%">
                  </div>
                </div>
                <span style="float:left;margin-top:-10px;font-size:13px;"><?php echo $usedRam;?> MB Of <?php echo $ram;?> MB Used</span>
            </td>
        </tr>
        <tr>
            <td width="90">CPU</td>
            <td>
                <div class="progress">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo (($usedCpu / $cpu) * 100);?>%">
                  </div>
                </div>
                <span style="float:left;margin-top:-10px;font-size:13px;"><?php echo $usedCpu;?>MHZ Of <?php echo $cpu;?> MHZ Used</span>
            </td>
        </tr>
        <!--<tr>
            <td width="90">BW USED</td>
            <td>
                <table class="table table-bordered">
                    <thead>
                        <th>One week ago </th>
                        <th>One month ago</th>
                        <th>One year ago</th>
                        <th>All time</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>30 GB</td>
                            <td>45 GB</td>
                            <td>85 GB</td>
                            <td>160 GB</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>-->
        <tr>
            <td width="90">UPTIME</td>
            <td>
                <i class="fa fa-clock-o fa-1x"></i>&nbsp;&nbsp;<?php echo $uptime['days'];?> Days and <?php echo $uptime['hours'];?> Hours and <?php echo $uptime['mins'];?> Minutes and <?php echo $uptime['secs'];?> Seconds<br>
            </td>
        </tr>
    </tbody>
</table>