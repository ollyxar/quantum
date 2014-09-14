<?php if (isset($text_message)) { ?>
    <div class="alert bg-<?php echo $class_message ?> alert-<?php echo $class_message ?>">
        <?php echo $text_message ?>
        <button type="button" class="close" data-dismiss="alert">×</button>
    </div>
<?php } ?>

<h2><?php echo $caption ?></h2>
<div class="row">
    <form id="contact-form" method="post" class="col-md-4">
        <input type="hidden" name="feedback" value="1">

        <div class="form-group">
            <label><?php echo $name_placeholder ?></label>
            <input class="form-control" name="name" type="text" placeholder="<?php echo $name_placeholder ?>" value="<?php echo $name ?>">
        </div>

        <div class="form-group">
            <label><?php echo $phone_placeholder ?></label>
            <input class="form-control" name="phone" type="tel" placeholder="<?php echo $phone_placeholder ?>" value="<?php echo $phone ?>">
        </div>

        <div class="form-group">
            <label><?php echo $email_placeholder ?></label>
            <input class="form-control" name="email" type="email" placeholder="<?php echo $email_placeholder ?>" value="<?php echo $email ?>">
        </div>

        <div class="form-group">
            <label><?php echo $message_placeholder ?></label>
            <textarea class="form-control" name="message" placeholder="<?php echo $message_placeholder ?>"><?php echo $message ?></textarea>
        </div>

        <?php if (isset($captcha)) { ?>
            <label>Captcha</label>
            <div class="form-group row">
                <div class="col-md-6 col-xs-6">
                    <img id="captcha" src="<?php echo $captcha ?>" alt="captcha" style="display: block; margin: 0 auto" />
                </div>
                <div class="col-md-6 col-xs-6">
                    <input name="captcha" type="text" class="form-control">
                </div>
            </div>
        <?php } ?>

        <input type="button" id="send" class="btn btn-primary form-control" value="<?php echo $send ?>">
    </form>
    <div class="col-md-8">
        <?php echo $info ?>
    </div>
</div>

<script type="text/javascript">
    jQuery('#send').click(function () {
        jQuery('#contact-form').submit();
    });
</script>                                        