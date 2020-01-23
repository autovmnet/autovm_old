<?php use yii\helpers\Html;?>
<!-- content -->
<div class="content">     
    <div class="col-md-8 pull-left">
		<table class="table table-bordered">
            <tbody>
                <tr>
                    <td>Server Name</td>
                    <td><?php echo Html::encode($server->name);?></td>
                </tr>
                <tr>
                    <td>Host address</td>
                    <?php if($api->host) {?>
                    <td><?php echo Html::encode($api->host);?></td>
                    <?php } else {?>
                    <td>Nothing</td>
                    <?php }?>
                </tr>
                <tr>
                    <td>Connect to host</td>
                    <?php if(empty($result)) {?>
                    <td><font color="red">Not connected</font></td>
                    <?php } else {?>
                    <td><font color="green">Connected</font></td>
                    <?php }?>
                </tr>
                <?php if(!empty($result)) {?>
                <tr>
                    <td>Connect to ESXi</td>
                    <?php if($result->server) {?>
                    <td><font color="green">Connected</font></td>
                    <?php } else {?>
                    <td><font color="red">Not connected</font></td>
                    <?php }?>
                </tr>
                <tr>
                    <td>Connect to Vcenter</td>
                    <?php if($result->center) {?>
                    <td><font color="green">Connected</font></td>
                    <?php } else {?>
                    <td><font color="red">Not connected</font></td>
                    <?php }?>
                </tr>
                <tr>
                    <td>Connect to SSH</td>
                    <?php if($result->ssh) {?>
                    <td><font color="green">Connected</font></td>
                    <?php } else {?>
                    <td><font color="red">Not connected</font></td>
                    <?php }?>
                </tr>
                <tr>
                    <td>Datastores</td>
                    <?php if($result->storage) {?>
                    <td><font color="green">Validated</font></td>
                    <?php } else {?>
                    <td><font color="red">Is not valid</font></td>
                    <?php }?>
                </tr>
                <?php }?>
                <tr>
                    <td colspan="2">You must upload all the operating system templates on your each ESXi servers.</td>
                </tr>
			</tbody>
		</table>
    </div>
</div>
<!-- / content -->
