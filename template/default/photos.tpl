
<?php foreach ($photos as $id => $photo) { ?>
    <a href="<?php echo $photo['src'] ?>" class="photo"
       style="background: url('<?php echo $photo['thumb'] ?>') no-repeat center;"></a>
<?php } ?>
<div id="bottom"></div>

<script type="text/javascript">
    var photo_id = <?php echo $id ?>+1;
    var go = true;
    window.onscroll = function () {
        var B = document.body,
            DE = document.documentElement,
            O = Math.min(B.clientHeight, DE.clientHeight);
        if (!O) O = B.clientHeight;
        var S = Math.max(B.scrollTop, DE.scrollTop),
            C = Math.max(B.scrollHeight, DE.scrollHeight);

        if ((O + S + 300 > C) && (go == true)) {
            go = false;
            jQuery('#bottom').html('loading...');
            jQuery.ajax({
                url: '?module=photos/find',
                type: 'POST',
                dataType: 'json',
                data: 'photo_id='+photo_id,
                success: function(data) {
                    jQuery('#bottom').before(data);
                },
                complete: function() {
                    go = true;
                    photo_id = photo_id + 10;
                }
            });
            jQuery('#bottom').html('');
        }
        jQuery('.photo').colorbox({rel: 'photo', maxHeight: '100%', maxWidth: '100%'});
    }
</script>
<script type="text/javascript">
    jQuery('.photo').colorbox({rel: 'photo', maxHeight: '100%', maxWidth: '100%'});
</script>