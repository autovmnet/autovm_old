<?php use yii\helpers\Html;?>
<!-- content -->
<div class="maindash" style="padding:30px;">

       <div class="row row-margin"> <!-- row1  -->

<!--<div class="col-md-12">
<div class="alert alert-success">You can ask your desired questions in our <a href="http://stack.autovm.net">stack</a></div>
</div>-->

<div class="col-md-12">
<div class="alert alert-danger">Important notice: We will not support ESXi 6.0 or older versions in the new AutoVM platform. In addition, old templates will be removed such as Debian 8, Centos 6 and windows 2008.</div>
</div>
           
<style>
.tweet{float:left;width:100%;padding:15px 30px;background:#fff;border:1px solid #ddd;border-radius:4px;box-shadow:0 5px 5px #ddd;margin-bottom:40px;}
.tweet h3,.tweet p{float:left;width:100%;direction:ltr;text-align:left;font:14px sans-serif;color:#7d7d7d;line-height:30px;margin:0;}
.tweet p{text-align:justify;}
.tweet h3{font-size:14px;color:#3d3d3d;font-weight:bold;}
.tweet h3 span{font-size:14px;color:#2AA4F2;}
.tweet p span{color:#2AA4F2!important;font-weight:bold;}
</style>

       <div class="col-md-4"> <!-- start1  -->

    <div class="col-md-12s"> <!-- start 2  -->

        <div class="stat-box stat-box-blue"> <!-- start 2b  -->


<div class="col-md-12">

		<i class="fa fa-pie-chart"></i>
            <h3>total users</h3>

</div>

<div class="col-md-4">
            <span><?php echo $stats->totalUsers;?></span>

</div>

<div class="col-md-8">

<div class="usage chartist-chart" style="height:60px"><div class="chartist-tooltip" style="top: 16px; left: 191.212px;"></div><svg xmlns:ct="http://gionkunz.github.com/chartist-js/ct" width="100%" height="100%" class="ct-chart-line" style="width: 100%; height: 100%;"><g class="ct-grids"></g><g><g class="ct-series ct-series-a"><path d="M30,55L30,35C34.157,38.333,46.627,59.667,54.941,55C63.255,50.333,71.568,7.667,79.882,7C88.196,6.333,96.51,48.333,104.823,51C113.137,53.667,121.451,24.333,129.764,23C138.078,21.667,146.392,45.667,154.705,43C163.019,40.333,171.333,15,179.646,7C187.96,-1,200.431,-3,204.588,-5L204.588,55Z" class="ct-area"></path><path d="M30,35C34.157,38.333,46.627,59.667,54.941,55C63.255,50.333,71.568,7.667,79.882,7C88.196,6.333,96.51,48.333,104.823,51C113.137,53.667,121.451,24.333,129.764,23C138.078,21.667,146.392,45.667,154.705,43C163.019,40.333,171.333,15,179.646,7C187.96,-1,200.431,-3,204.588,-5" class="ct-line"></path><line x1="30" y1="35" x2="30.01" y2="35" class="ct-point" value="5"></line><line x1="54.94107273646763" y1="55" x2="54.95107273646763" y2="55" class="ct-point" value="0"></line><line x1="79.88214547293526" y1="7" x2="79.89214547293527" y2="7" class="ct-point" value="12"></line><line x1="104.82321820940291" y1="51" x2="104.83321820940292" y2="51" class="ct-point" value="1"></line><line x1="129.76429094587053" y1="23" x2="129.77429094587052" y2="23" class="ct-point" value="8"></line><line x1="154.70536368233817" y1="43" x2="154.71536368233816" y2="43" class="ct-point" value="3"></line><line x1="179.64643641880582" y1="7" x2="179.6564364188058" y2="7" class="ct-point" value="12"></line><line x1="204.58750915527344" y1="-5" x2="204.59750915527343" y2="-5" class="ct-point" value="15"></line></g></g><g class="ct-labels"></g></svg></div>

</div><!-- end col-md-8  -->

        </div><!-- start 2b  -->
    </div> <!-- end start2  -->


    <div class="col-md-12s">
        <div class="stat-box stat-box-green">
<div class="col-md-12">

<i class="fa fa-cloud-download"></i>
            <h3>used bandwidth</h3>

</div>
<div class="col-md-4">
  <span><?php echo number_format($stats->bandwidth/1024, 2);?> GB</span>
</div>
<div class="col-md-8">


<div class="usage chartist-chart" style="height:60px"><div class="chartist-tooltip" style="top: 16px; left: 191.212px;"></div><svg xmlns:ct="http://gionkunz.github.com/chartist-js/ct" width="100%" height="100%" class="ct-chart-line" style="width: 100%; height: 100%;"><g class="ct-grids"></g><g><g class="ct-series ct-series-a"><path d="M30,55L30,35C34.157,38.333,46.627,59.667,54.941,55C63.255,50.333,71.568,7.667,79.882,7C88.196,6.333,96.51,48.333,104.823,51C113.137,53.667,121.451,24.333,129.764,23C138.078,21.667,146.392,45.667,154.705,43C163.019,40.333,171.333,15,179.646,7C187.96,-1,200.431,-3,204.588,-5L204.588,55Z" class="ct-area"></path><path d="M30,35C34.157,38.333,46.627,59.667,54.941,55C63.255,50.333,71.568,7.667,79.882,7C88.196,6.333,96.51,48.333,104.823,51C113.137,53.667,121.451,24.333,129.764,23C138.078,21.667,146.392,45.667,154.705,43C163.019,40.333,171.333,15,179.646,7C187.96,-1,200.431,-3,204.588,-5" class="ct-line"></path><line x1="30" y1="35" x2="30.01" y2="35" class="ct-point" value="5"></line><line x1="54.94107273646763" y1="55" x2="54.95107273646763" y2="55" class="ct-point" value="0"></line><line x1="79.88214547293526" y1="7" x2="79.89214547293527" y2="7" class="ct-point" value="12"></line><line x1="104.82321820940291" y1="51" x2="104.83321820940292" y2="51" class="ct-point" value="1"></line><line x1="129.76429094587053" y1="23" x2="129.77429094587052" y2="23" class="ct-point" value="8"></line><line x1="154.70536368233817" y1="43" x2="154.71536368233816" y2="43" class="ct-point" value="3"></line><line x1="179.64643641880582" y1="7" x2="179.6564364188058" y2="7" class="ct-point" value="12"></line><line x1="204.58750915527344" y1="-5" x2="204.59750915527343" y2="-5" class="ct-point" value="15"></line></g></g><g class="ct-labels"></g></svg></div>

</div>


        </div>
    </div>

   </div> <!-- end start1  -->

    <div class="col-md-8">
        <div class="stat-box stat-box-yellow virtualserver1">

<div class="col-md-12 virtualserver">
 <div class="col-md-8">
 <i class="fa fa-cubes"></i>
            <h3>total virtual servers</h3>
</div>
<div class="col-md-4">
            <span><?php echo $stats->totalVps;?></span>
</div>
</div>
<div class="col-md-12">
<div class="totalvir3s table-responsive">


 <table class="table table-bordered">
            <thead>
                <th>ID</th>
                <th>Virtual server</th>
                <th>Action</th>
                <th>Time</th>
            </thead>
            <tbody>
            <?php foreach($stats->vpsActions as $action) {?>
                <tr>
                    <td><?php echo $action->id;?></td>
                    <td><?php echo Html::encode($action->vps->ip ? $action->vps->ip->ip : ' NO IP');?></td>
                    <td>
                    <?php if($action->getIsStart()) {?>
                        Start
                    <?php } elseif ($action->getIsStop()) {?>
                        Stop
                    <?php } elseif ($action->getIsRestart()) {?>
                        Restart
                    <?php } elseif ($action->getIsInstall()) {?>
                        Install
                    <?php } elseif ($action->getIsSuspend()) {?>
                        Suspend <?php echo ($action->description ? ' - ' . Html::encode($action->description) : null);?>
                    <?php } elseif ($action->getIsUnsuspend()) {?>
                        Unsuspend <?php echo ($action->description ? ' - ' . Html::encode($action->description) : null);?>
                    <?php }?>
                    </td>
                    <td><?php echo date('d M Y - H:i', $action->created_at);?></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
</div>
</div>

        </div>
    </div>

 </div> <!-- end row1 -->


   <div class="row row-margin " > <!--  row2 -->

    <div class="col-md-12  usertable1">
<div class="usertable " >
<div class="" >
    <div class="col-md-12">
<div class="title_up ">
<h3>
Last login
</h3>
</div>
</div>
</div>
<div class="col-md-12  table-responsive" >
        <table class="table table-bordered">
            <thead>
				<th width="10">#</th>
                <th>Name</th>
                <th>IP</th>
                <th>Browser</th>
                <th>Time</th>
                <th>Action</th>
            </thead>
            <tbody>




            <?php
						$i=0;
						foreach($stats->logins as $login) {
						$i++;
				?>
                <tr>
					<td > <span class=" round round-success"><?php echo $i; ?> </span> </td>
                    <td><a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/user/edit', 'id' => $login->user->id]);?>"><?php echo Html::encode($login->user->getFullName());?></a></td>
                    <td><?php echo Html::encode($login->ip);?></td>
                    <td><?php echo Html::encode($login->browser_name); ?></td>
                    <td><?php echo date('d M Y - H:i', $login->created_at);?></td>
                    <td><?php echo ($login->getIsSuccessful() ? ' Successful' : ' Unsuccessful');?></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
    </div>
    </div>
    </div><!-- END row2 -->

</div>
<!-- END content -->
