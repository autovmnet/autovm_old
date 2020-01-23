<?php foreach($data as $key => $messages) {?>
    <?php foreach($messages as $message) {?>
    <div class="alert alert-<?php echo $key;?>"><?php echo $message;?></div>
    <?php }?>
<?php }?>