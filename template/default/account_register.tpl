<?php if (isset($text_message)) { ?>
    <div class="alert bg-danger alert-<?php echo $class_message ?>">
        <?php echo $text_message ?>
        <button type="button" class="close" data-dismiss="alert">×</button>
    </div>
<?php } ?>

<h2><?php echo $caption ?></h2>
<div class="row">
    <form id="contact-form" method="post" style="margin:0 auto;width:300px">
        <input type="hidden" name="register" value="1">

        <div class="form-group">
            <label><?php echo $placeholder_name ?></label>
            <input class="form-control" name="name" type="text" placeholder="<?php echo $placeholder_name ?>" value="<?php echo $name ?>">
        </div>

        <div class="form-group">
            <label><?php echo $placeholder_email ?></label>
            <input class="form-control" name="email" type="email" placeholder="<?php echo $placeholder_email ?>" value="<?php echo $email ?>">
        </div>

        <div class="form-group">
            <label><?php echo $placeholder_password ?></label>
            <input class="form-control" name="email" type="password" placeholder="<?php echo $placeholder_password ?>" value="<?php echo $password ?>">
        </div>

        <?php if (isset($captcha)) { ?>
            <label>Captcha</label>
            <div class="form-group row">
                <div class="col-md-6 col-xs-6">
                    <img src="<?php echo $captcha ?>" alt="captcha" style="display: block; margin: 0 auto" />
                </div>
                <div class="col-md-6 col-xs-6">
                    <input name="captcha" type="text" class="form-control">
                </div>
            </div>
        <?php } ?>

        <div class="form-group">
            <label style="cursor: pointer; font-weight: normal">
                <input type="checkbox" name="agree" value="1">&nbsp;<?php echo $agree ?>
            </label>
        </div>

        <input type="button" id="send" class="btn btn-primary form-control" value="<?php echo $confirm ?>">
    </form>
</div>

<script type="text/javascript">
    jQuery('#send').click(function () {
        jQuery('#contact-form').submit();
    });
</script>                                        