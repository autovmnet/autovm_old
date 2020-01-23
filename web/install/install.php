<?php $step = 2;?>
<?php require('views/header.phtml');?>
<?php require('views/left.phtml');?>
<div class="col s12 m9">
    <div class="card">
        <div class="card-content">
            <div class="title">
                <h3>Installation Wizard</h3>
            </div>
            <form id="w0" action="src/install.php" method="post" role="form">
                <b>MySQL details</b>
                <div class="input-field form-group required">
                    <input class="form-control validate" type="text" value="localhost" name="host" id="mysql_host" required />
                    <label for="mysql_host">MySQL host</label>
                </div>

                <div class="input-field form-group required">
                    <input class="form-control validate" type="text" name="username" id="mysql_username" required />
                    <label for="mysql_username" >MySQL Username</label>
                </div>

                <div class="input-field form-group required">
                    <input class="form-control validate" type="text" name="database" id="mysql_database" required />
                    <label for="mysql_database" >Database Name</label>
                </div>

                <div class="input-field form-group required">
                    <input class="form-control validate" type="password" name="password" id="mysql_password" required autocomplete="off" />
                    <label for="mysql_password" >MySQL Password</label>
                </div>

                <b>Admin details</b>

                <div class="input-field form-group required">
                    <input class="form-control validate" type="text" name="email" id="email" required autocomplete="off" />
                    <label for="email" >Email Address</label>
                </div>

                <div class="input-field form-group required">
                    <input class="form-control validate" type="password" name="pass" id="password" required autocomplete="off" />
                    <label for="password" >Password</label>
                </div>

                <div class="divider"></div>
                <div class="input-field form-group">
                    <button type="submit" class="btn btn-success right">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
include_once ('views/footer.phtml');
?>