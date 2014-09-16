<h2><?php echo $caption ?></h2>
<div class="row">
    <form id="restore-form" method="post" style="margin:0 auto;width:300px">
        <div id="result"></div>

        <div class="form-group">
            <label><?php echo $new_pass ?></label>
            <input class="form-control" name="password" type="password" placeholder="<?php echo $new_pass ?>" />
        </div>

        <input type="button" id="send" class="btn btn-primary form-control" value="<?php echo $confirm ?>">
    </form>
</div>

<script type="text/javascript">
    jQuery('#send').click(function () {
        jQuery('#restore-form').submit();
    })
</script>                                        