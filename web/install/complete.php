<?php $step = 3;?>
<?php require('views/header.phtml');?>
<?php require('views/left.phtml');?>
<div class="col s12 m9">
    <div class="card">
        <div class="card-content">
            <div class="title">
                <h3>Completed !</h3>
                <p style="padding-left: 20px">Remove the install folder from the /web directory. Attention: Do not remove web directory.</p>
            </div>
            <p style="text-align: justify;margin-bottom: 20px;font-weight: bold;">
            </p>
            <div class="divider"></div>
            <div class="input-field form-group">
                <a href="../" class="btn btn-success right">Finish</a>
            </div>
        </div>
    </div>
</div>
<?php
include_once ('views/footer.phtml');
?>