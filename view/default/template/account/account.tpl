<h2><?php echo $caption ?></h2>
<form id="register-form" method="post">
    <input type="hidden" name="register" value="1">

    <div class="form-group">
        <label><?php echo $placeholder_name ?></label>
        <input class="form-control" name="name" type="text" placeholder="<?php echo $placeholder_name ?>"
               value="<?php echo $name ?>">
    </div>

    <div class="form-group">
        <label><?php echo $placeholder_email ?></label>
        <input class="form-control" name="email" type="email" placeholder="<?php echo $placeholder_email ?>"
               value="<?php echo $email ?>">
    </div>

    <div class="form-group">
        <label><?php echo $old_pass_caption ?></label>
        <input class="form-control" name="password" type="password" placeholder="<?php echo $old_pass_caption ?>"
               value="<?php echo $old_pass ?>">
    </div>

    <div class="form-group">
        <label><?php echo $new_pass_caption ?></label>
        <input class="form-control" name="password" type="password" placeholder="<?php echo $new_pass_caption ?>"
               value="<?php echo $new_pass ?>">
    </div>

    <input type="button" id="send" class="btn btn-primary form-control" value="<?php echo $confirm ?>">
</form>