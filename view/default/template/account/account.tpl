<h2><?php echo $caption ?></h2>
<form id="account-form" method="post">
    <div class="col-md-3">
        <img src="<?php echo $photo ?>" alt="<?php echo $name ?>"/>
    </div>
    <div class="col-md-9">
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
            <input class="form-control" name="email" type="email" placeholder="<?php echo $placeholder_email ?>"
                   value="<?php echo $email ?>">
        </div>
        <input type="button" id="send" class="btn btn-primary pull-right" value="<?php echo $save ?>">
    </div>
</form>

<div id="modal_pass" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><?php echo $change_my_pass ?></h4>
            </div>
            <div class="modal-body">
                <div id="r_results"></div>
                <form id="site-review">
                    <div class="form-group">
                        <label><?php echo $old_pass ?></label>
                        <input class="form-control" name="password" type="password" placeholder="<?php echo $old_pass ?>">
                    </div>
                    <div class="form-group">
                        <label><?php echo $new_pass ?></label>
                        <input class="form-control" name="password" type="password" placeholder="<?php echo $new_pass ?>">
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
    jQuery('#logout').click(function() {
        jQuery.ajax({
            url: '/?module=account/logout',
            complete: function() {
                window.location.reload();
            }
        })
    })
</script>