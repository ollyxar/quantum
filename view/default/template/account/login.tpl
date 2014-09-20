<h2><?php echo $caption ?></h2>
<div class="row">
    <form id="login-form" method="post" style="margin:0 auto;width:300px">
        <div id="result"></div>
        <div class="form-group">
            <label><?php echo $placeholder_email ?></label>
            <input class="form-control" name="email" type="email" placeholder="<?php echo $placeholder_email ?>"
                   value="<?php echo $email ?>">
        </div>

        <div class="form-group">
            <label><?php echo $placeholder_password ?></label>
            <input class="form-control" name="password" type="password"
                   placeholder="<?php echo $placeholder_password ?>" value="<?php echo $password ?>">
        </div>

        <div class="form-group">
            <label style="cursor: pointer; font-weight: normal">
                <input type="checkbox" name="remember_me" value="1">&nbsp;<?php echo $remember_me ?>
            </label>
        </div>

        <input type="button" id="send" class="btn btn-primary form-control" value="<?php echo $log_in ?>">
        <a class="link-login"><?php echo $restore_password ?></a>
    </form>
    <form id="restore-block" method="post" style="margin: 0 auto; width:300px">
        <a class="link-login"><?php echo $log_in ?></a>
        <div id="result-r"></div>
        <div class="form-group">
            <label><?php echo $placeholder_email ?></label>
            <input class="form-control" id="email-r" type="email" placeholder="<?php echo $placeholder_email ?>">
        </div>
        <input type="button" id="send-r" class="btn btn-primary form-control" value="<?php echo $confirm ?>">
    </form>
</div>

<script type="text/javascript">
    jQuery('.link-login').click(function () {
        jQuery('#restore-block, #login-form').slideToggle(200);
    });
    jQuery('#send-r').click(function() {
        jQuery.ajax({
            type: 'POST',
            url: '?module=account/restoreCheck',
            dataType: 'json',
            data: 'email=' + encodeURIComponent(jQuery('#email-r').val()),
            beforeSend: function () {
                jQuery('#result-r').html('');
            },
            success: function (data) {
                if (data[0] == false) {
                    jQuery('#result-r').html('<div class="alert bg-danger">' + data[1] + '</div>');
                } else  {
                    jQuery('#result-r').html('<div class="alert bg-success">' + data[1] + '</div>');
                    jQuery('#send-r').remove();
                }
            },
            error: function () {
                jQuery('#result-r').html('<div class="alert bg-danger">Error while sending data</div>');
            }
        });
    });
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
                    jQuery('#result').html('<div class="alert bg-danger"><?php echo addslashes($data_incorrect) ?></div>');
                } else if (data == "2") {
                    jQuery('#result').html('<div class="alert bg-danger"><?php echo addslashes($email_not_confirmed) ?></div>');
                } else if (data == "0") {
                    jQuery('#result').html('<div class="alert bg-success"><?php echo addslashes($login_success) ?></div>');
                    window.location = '<?php echo htmlspecialchars($engine->url->link('route=account')) ?>';
                } else {
                    jQuery('#result').html('<div class="alert bg-danger"><?php echo addslashes($unknown_error) ?></div>');
                }
            },
            error: function () {
                jQuery('#result').html('<div class="alert bg-danger">Error while sending data</div>');
            }
        });
    });
    jQuery('input[name="password"]').keydown(function(e) {
        if (e.keyCode == 13) {
            jQuery('#send').trigger('click');
        }
    });
</script>                                        