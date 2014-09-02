</div>
<?php $end_time = microtime(true) ?>
<?php $total_time = round($end_time - $start_time, 4) ?>
<div class="debug">All operations completed in: <?php echo $total_time ?> sec. Total database
    queries: <?php echo $engine->db->db_queries ?>. Memory
    usage: <?php echo round((memory_get_usage(true) / 1024 / 1024), 2) ?>MB
</div>
<div class="copyright"><a href="http://quantum.ollyxar.com" rel="nofollow" target="_blank"><?php echo '&copy;' . $language['cms-title'] ?></a> |
    <a href="http://ollyxar.com" rel="nofollow" target="_blank"><?php echo '&copy;' . $language['engine-title'] ?></a></div>
<div id="change-pass" class="modal hide fade" tabindex="-1">
    <form method="post" id="chp">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <span class="h3"><?php echo $language['change_pass'] ?></span>
        </div>
        <div class="modal-body">
            <div class="row-fluid">
                <div class="span12">
                    <div class="control-group">
                        <label class="control-label" for="oldPass"><?php echo $language['old_pass'] ?></label>

                        <div>
                            <input id="oldPass" type="password" name="oldPass" class="input-xlarge span12">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="newPass"><?php echo $language['new_pass'] ?></label>

                        <div class="controls">
                            <input id="newPass" type="password" name="newPass" class="input-xlarge span12">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="button" class="btn btn-primary" onclick="jQuery('#chp').submit()"
                   value="<?php echo $language['submit'] ?>"></div>
    </form>
</div>
<script type="text/javascript">
    setTimeout(function(){
        jQuery('.alert.text-center.alert-success').fadeOut(500);
    }, 3000);
</script>
</body>
</html>