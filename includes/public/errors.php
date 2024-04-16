<?php foreach ($errors as $error => $value) { ?>
    <div class="error">
        <?php 
        print_r($errors[$error]);
        unset($errors[$error]);
        ?>
    </div>
<?php } ?>