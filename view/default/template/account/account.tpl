<h2><?php echo $caption ?></h2>

<div class="col-md-3">
    <img src="<?php echo $photo ?>" alt="<?php echo $name ?>"/>
</div>

<div class="col-md-9">
    <form id="account-form" method="post">
        <div class="pull-right">
            <a href="#" data-toggle="modal" data-target="#modal_pass"><?php echo $change_my_pass ?></a> |
            <a id="logout" href="#"><?php echo $log_out ?></a>
        </div>
        <div class="form-group">
            <label><?php echo $placeholder_name ?></label>
            <input class="form-control" name="name" type="text" placeholder="<?php echo $placeholder_name ?>"
                   value="<?php echo $name ?>">
        </div>
        <div class="form-group">
            <label><?php echo $placeholder_email ?></label>
            <input class="form-control" name="email" disabled="disabled" type="email" placeholder="<?php echo $placeholder_email ?>"
                   value="<?php echo $email ?>">
        </div>
        <input type="button" id="send" class="btn btn-primary pull-right" value="<?php echo $save ?>">
    </form>
</div>


<div id="modal_pass" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><?php echo $change_my_pass ?></h4>
            </div>
            <div class="modal-body">
                <div id="r_results"></div>
                <form id="form-pass">
                    <div class="form-group">
                        <label><?php echo $old_pass ?></label>
                        <input class="form-control" name="old_pass" type="password" placeholder="<?php echo $old_pass ?>">
                    </div>
                    <div class="form-group">
                        <label><?php echo $new_pass ?></label>
                        <input class="form-control" name="new_pass" type="password" placeholder="<?php echo $new_pass ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo $cancel ?></button>
                <button class="btn btn-primary" id="change-pass"><?php echo $confirm ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery('#change-pass').click(function() {
        jQuery.ajax({
            url: '/?module=account/changePass',
            dataType: 'json',
            type: 'POST',
            data: 'old_pass=' + jQuery('input[name="old_pass"]').val() + '&new_pass=' + jQuery('input[name="new_pass"]').val(),
            beforeSend: function() {
                jQuery('#r_results').html('');
            },
            success: function(data) {
                if (data == 1) {
                    jQuery('#r_results').html('<div class="alert bg-success"><?php echo addslashes($login_success) ?></div>');
                    setTimeout(function() {
                        jQuery('#modal_pass button.close').trigger('click');
                        jQuery('#form-pass').trigger('reset');
                        jQuery('#r_results').html('');
                    }, 800);
                } else {
                    jQuery('#r_results').html('<div class="alert bg-danger"><?php echo addslashes($data_incorrect) ?></div>');
                }
            },
            error: function() {
                jQuery('#r_results').html('<div class="alert bg-danger"><?php echo addslashes($unknown_error) ?></div>');
            }
        });
    });
    jQuery('#logout').click(function() {
        jQuery.ajax({
            url: '/?module=account/logout',
            complete: function() {
                window.location.reload();
            }
        })
    });
    var timeout;
    var username = '<?php echo addslashes($name) ?>';

    function addslashes(str) {
        return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
    }

    jQuery('input[name="name"]').keyup(function() {
        clearTimeout(timeout);
        var value = addslashes(jQuery(this).val());
        if (value != username) {
            var parent = jQuery(this).parent();
            timeout = setTimeout(function () {
                var notice = parent.find('div.notice');
                jQuery.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '?module=account/nameCheck',
                    data: 'name=' + value,
                    beforeSend: function () {
                        notice.remove();
                        parent.removeClass('has-error');
                    },
                    success: function(data) {
                        if (data != '') {
                            parent.append('<div class="notice">' + data + '</div>');
                            parent.addClass('has-error');
                        }
                    }
                });
            }, 500);
        }
    });
    jQuery('#send').click(function() {
        jQuery('input[name="name"]').trigger('click');
        var error = jQuery('.has-error');
        var account_form = jQuery('#account-form');
        if (error.length < 1) {
            jQuery.ajax({
                type: 'POST',
                dataType: 'json',
                url: '?module=account/save',
                data: account_form.serialize(),
                success: function(data) {
                    if (data == 1) {
                        account_form.prepend('<div class="alert bg-success"><?php echo addslashes($changes_applied) ?></div>');
                        setTimeout(function() {
                            jQuery('.alert.bg-success').fadeOut(300);
                        }, 800)
                    } else {
                        account_form.prepend('<div class="alert bg-danger"><?php echo addslashes($unknown_error) ?></div>');
                    }
                }
            });
        }
    });
</script>