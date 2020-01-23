<?php
use yii\helpers\Url;
use yii\helpers\Html;

Yii::$app->setting->title .= ' - virtual servers';
?>

<div class="content">
    <div class="container vpsServers">
        <div class="row">
            <h3 class="title"><?php echo Yii::t('app', 'Virtual Machines');?><p><?php echo Yii::t('app', 'List of your active virtual servers');?></p></h3>
            <?php echo \app\widgets\Alert::widget();?>
            <ul class="collapsible popout z-depth-4">
                <?php foreach($virtualServers as $vps) {?>
                    <li data-id="<?php echo $vps->id;?>">
                        <div class="collapsible-header">
                            <div class="row">
                                <div class="col l1 s2 center-align">
                                    <?php echo $vps->id;?>
                                </div>
								
                                <div class="col l1 s2 center-align">
                                    <?php if($vps->getIsOnline()){
                                    	echo('<div class="green circle" style="width: 16px; height: 16px; margin-top: 15px;"></div>');
                                    	}
                                    	else{
                                    	echo('<div class="grey circle" style="width: 16px; height: 16px; margin-top: 15px;"></div>');
                                    	}
                                     ?>
                                    
                                </div>
								
                                <div class="col l2 s3 center-align">
                                    <?php echo Html::encode($vps->ip ? $vps->ip->ip : 'NONE');?>
                                </div>
								<div class="col l2 s2 center-align">
                                    <?php echo Html::encode(!empty($vps->hostname) ? $vps->hostname : '');?>
                                </div>
                                <div class="col l3 hide-on-small-and-down center-align">
				    <?php echo Html::encode(isset($vps->os->name)?$vps->os->name:'NONE');?>
                                </div>
                                <div class="col l3 s4 center-align">
                                    <a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="12" class="btn blue vps-stop waves-effect waves-light" style="padding:5px;"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="12">change_history</i></a>
                                    <a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="2" class="btn blue vps-stop waves-effect waves-light" style="padding:5px;"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="2">autorenew</i></a>
                                    <a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="3" class="btn blue vps-stop waves-effect waves-light" style="padding:5px;"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="3">stop</i></a>
                                    <a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="4" class="btn blue white-text vps-start waves-effect waves-light" style="padding:5px;"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="4">play_arrow</i></a>
                                </div>
                            </div>
                        </div>
                        <div class="collapsible-body">
<!-- -->
<table class="bordered vpss striped centered responsive-table">
                <thead>
                    <th><?php echo Yii::t('app', 'IP Address');?></th>
                    <th><?php echo Yii::t('app', 'Server');?></th>
                    <th><?php echo Yii::t('app', 'Operating System');?></th>
                    <th><?php echo Yii::t('app', 'Memory');?></th>
                    <th><?php echo Yii::t('app', 'CPU Cores');?></th>
                    <th><?php echo Yii::t('app', 'CPU MHZ');?></th>
                    <th><?php echo Yii::t('app', 'Disk Space');?></th>
                    <th><?php echo Yii::t('app', 'Username');?></th>
                    <th><?php echo Yii::t('app', 'Disk');?></th>
                    <th colspan="2"><?php echo Yii::t('app', 'Bandwidth');?></th>
                </thead>
                <tbody>
		<tr>
                        <td><?php echo Html::encode(isset($vps->ip->ip)?$vps->ip->ip:'');?></td>
                        <td><?php echo Html::encode(isset($vps->server)?$vps->server->name:'');?></td>
                        <td><?php echo Html::encode(isset($vps->os->name)?$vps->os->name:'');?></td>
                        <td><?php echo Yii::t('app', 'Loading');?></td>
                        <td><?php echo Yii::t('app', 'Loading');?></td>
                        <td><?php echo Yii::t('app', 'Loading');?></td>
                        <td><?php echo Yii::t('app', 'Loading');?></td>
                        <td><?php echo Yii::t('app', 'Loading');?></td>
                        <td><?php echo Yii::t('app', 'Loading');?></td>
                        <td colspan="2"><?php echo Yii::t('app', 'Loading');?></td>
                    </tr>
                    <tr>
                        <td><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="1"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="1">computer</i> <br><?php echo Yii::t('app', 'Change Os');?></p></a></td>
                        <td><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="10"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="10">album</i> <br><?php echo Yii::t('app', 'ISO');?></p></a></td>
                        <td><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="2"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="2">autorenew</i> <br><?php echo Yii::t('app', 'Restart');?></p></a></td>
                        <td><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="3"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="3">stop</i> <br> <?php echo Yii::t('app', 'Stop');?></p></a></td>
                        <td><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="4"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="4">play_arrow</i> <br><?php echo Yii::t('app', 'Start');?></p></a></td>
                        <td class="hide-on-med-and-down"><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="5"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="5">gps_fixed</i> <br><p><?php echo Yii::t('app', 'VM Status');?></p></a></td>
                        <td class="hide-on-med-and-down"><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="6"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="6">timeline</i> <br> <p><?php echo Yii::t('app', 'Monitor');?></p></a></td>
                        <td class="hide-on-med-and-down"><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="7"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="7">layers</i> <br> <p><?php echo Yii::t('app', 'Extend hard');?></p></a></td>
                        <td class="hide-on-med-and-down"><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="8"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="8">history</i> <br> <p><?php echo Yii::t('app', 'Action logs');?></p></a></td>
                        <td class="hide-on-med-and-down"><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="9"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="9">developer_board</i> <br> <p><?php echo Yii::t('app', 'Console');?></p></a></td>
                        <td class="hide-on-med-and-down"><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="11"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="11">backup</i> <br> <p><?php echo Yii::t('app', 'SnapShot');?></p></a></td>
                    </tr>
                    <tr>
                        <td class="hide-on-large-only"><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="5"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="5">gps_fixed</i> <br> <p><?php echo Yii::t('app', 'VM status');?></p></a></td>
                        <td class="hide-on-large-only"><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="6"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="6">timeline</i> <br> <p><?php echo Yii::t('app', 'Monitor');?></p></a></td>
                        <td class="hide-on-large-only"><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="7"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="7">layers</i> <br> <p><?php echo Yii::t('app', 'Extend hard');?></p></a></td>
                        <td class="hide-on-large-only"><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="8"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="8">history</i> <br> <p><?php echo Yii::t('app', 'Action logs');?></p></a></td>
                        <td class="hide-on-large-only"><a href="javascript:void(0);" data-id="<?php echo $vps->id;?>" data-action="9"><i class="material-icons" data-id="<?php echo $vps->id;?>" data-action="9">developer_board</i> <br> <p><?php echo Yii::t('app', 'Console');?></p></a></td>
                    </tr>
                    </tbody>
                    </table>
<!-- -->
                        </div>
                    </li>
                <?php }?>
            </ul>
            <?php echo \yii\widgets\LinkPager::widget(['pagination' => $pages]);?>
        </div>
    </div>
</div>
