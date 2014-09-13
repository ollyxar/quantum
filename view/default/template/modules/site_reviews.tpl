<div class="otziv">
    <div class="clearfix bor">
        <div class="pull-right">
            <a href="#" class="href" data-toggle="modal" data-target="#modal"><span class="icon-otz"></span><?php echo $leave_review_btn ?></a>
        </div>
    </div>
    <div class="reviews">
        <ul class="list-unstyled sl">
            <?php if (isset($reviews) && !empty($reviews)) { ?>
                <?php foreach ($reviews as $review) { ?>
                    <li class="clearfix">
                        <img src="<?php echo $review['photo'] ?>" class="pull-left">

                        <div class="review-text">
                            <p><?php echo $review['post'] ?></p>
                        </div>
                        <div class="review-author">
                            <?php echo $review['name'] ?>
                        </div>
                    </li>
                <?php } ?>
            <?php } ?>
        </ul>
    </div>
</div>

<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel_review" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><?php echo $form_caption ?></h4>
            </div>
            <div class="modal-body">
                <div id="r_results"></div>
                <form id="site-review">
                    <div class="form-group">
                        <label for="input-name"><?php echo $name_placeholder ?></label>
                        <input type="text" id="input-name" class="form-control" name="review_name">
                    </div>
                    <div class="form-group">
                        <label for="input-mail"><?php echo $email_placeholder ?></label>
                        <input type="text" id="input-mail" class="form-control" name="review_email">
                    </div>
                    <div class="form-group">
                        <label for="input-review"><?php echo $review_placeholder ?></label>
                        <textarea class="form-control" id="input-review" name="review-post"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">

                <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo $cancel_btn ?></button>
                <button class="btn btn-primary" id="send-review"><?php echo $post_btn ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery('#send-review').on('click', function () {
        var go = true;
        jQuery('#r_results').html();
        if (jQuery('input[name=review_name]').val() == "") {
            jQuery('#r_results').html('<div class="alert bg-danger"><?php echo addslashes($error_name) ?></div>');
            jQuery('input[name=review_name]').parent().addClass('has-error has-feedback').append('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
            go = false;
        } else {
            jQuery('input[name=review_name]').parent().removeClass('has-error has-feedback');
            jQuery('span.glyphicon-remove').remove();
        }
        if (jQuery('textarea[name=review-post]').val() == "") {
            jQuery('#r_results').html('<div class="alert bg-danger"><?php echo addslashes($error_text) ?></div>');
            jQuery('textarea[name=review-post]').parent().addClass('has-error has-feedback').append('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
            go = false;
        } else {
            jQuery('textarea[name=review-post]').parent().removeClass('has-error has-feedback');
            jQuery('span.glyphicon-remove').remove();
        }
        if (go) {
            jQuery.ajax({
                type: 'POST',
                dataType: 'json',
                url: '?module=sitereviews/add',
                data: jQuery('#site-review').serialize(),
                beforeSend: function () {
                    jQuery('#review_loader').attr('display', 'block');
                },
                success: function (data) {
                    jQuery('#r_results').html(data);
                    jQuery('#send-review').trigger('reset');
                },
                error: function (data) {
                    jQuery("#r_results").html(data);
                },
                complete: function () {
                    jQuery('#review_loader').attr('display', 'none');
                }
            });
        }
    });
</script>
<div>

</div>


