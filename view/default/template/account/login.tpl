<h2><?php echo $caption ?></h2>
<div class="row">
    <form id="login-form" method="post" style="margin:0 auto;width:300px">
        <div id="result"></div>
        <div class="form-group">
            <label><?php echo $placeholder_email ?></label>
            <input class="form-control" name="email" type="email" placeholder="<?php echo $placeholder_email ?>" value="<?php echo $email ?>">
        </div>

        <div class="form-group">
            <label><?php echo $placeholder_password ?></label>
            <input class="form-control" name="password" type="password" placeholder="<?php echo $placeholder_password ?>" value="<?php echo $password ?>">
        </div>

        <div class="form-group">
            <label style="cursor: pointer; font-weight: normal">
                <input type="checkbox" name="remember_me" value="1">&nbsp;<?php echo $remember_me ?>
            </label>
        </div>

        <input type="button" id="send" class="btn btn-primary form-control" value="<?php echo $log_in ?>">
    </form>
</div>

<script type="text/javascript">
    jQuery('#send').click(function () {
        jQuery.ajax({
            type: 'POST',
            url: '?module=account/login',
            dataType: 'json',
            data: jQuery('#login-form').serialize(),
            beforeSend: function () {
                jQuery('#result').html('');
            },
            success: function (data) {
                if (data == "1") {
                    jQuery('#result').html('<div class="alert bg-danger">Data incorrect</div>');
                } else if (data == "2") {
                    jQuery('#result').html('<div class="alert bg-danger">Email is not confirmed</div>');
                } else if (data == "0") {
                    jQuery('#result').html('<div class="alert bg-success">Login success</div>');
                    window.location = '<?php echo htmlspecialchars($engine->url->link('route=account')) ?>';
                } else {
                    jQuery('#result').html('<div class="alert bg-danger">Other error</div>');
                }
            },
            error: function() {
                jQuery('#result').html('<div class="alert bg-danger">Error while sending data</div>');
            }
        });
    })
</script>                                        