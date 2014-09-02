<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['link'] ?>"><?php echo $breadcrumb['caption'] ?></a> <span
                class="divider">/</span></li>
    <?php } ?>
    <li class="active"><?php echo $breadcrumb_cur ?></li>
</ul>
<form method="post" id="form">
    <div class="hero-unit clearfix">
        <div class="pull-left">
            <h2><?php echo $language['user_management'] ?></h2>
        </div>
        <div class="pull-right">
            <div class="margin-top-button">
                <a href="index.php?page=users" class="btn btn-inverse"><i
                        class="icon-ban-circle icon-white"></i> <?php echo $language['cancel'] ?></a>
                <a id="save" class="btn btn-success"><i
                        class="icon-ok icon-white"></i> <?php echo $language['save'] ?></a>
            </div>
        </div>
    </div>
    <?php if (isset($text_message)) { ?>
        <div class="alert text-center alert-<?php echo $class_message ?>">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <?php echo $text_message ?>
        </div>
    <?php } ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="span6">
                <div class="control-group">
                    <label class="control-label"><?php echo $language['name'] ?></label>
                    <input type="text" name="uname" class="span12"
                           value="<?php echo $user['name'] ?>"/>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo $language['email'] ?></label>
                    <input type="text" name="email" class="span12"
                           value="<?php echo $user['email'] ?>"/>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo $language['group'] ?></label>
                    <select name="group" class="span12">
                        <?php foreach ($user_groups as $id => $user_group) { ?>
                            <option
                                value="<?php echo $id ?>" <?php if ($user['user_group'] == $id) echo 'selected="selected"'
                            ?>><?php echo $user_group ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="span6">
                <div class="control-group">
                    <label class="control-label"><?php echo $language['photo'] ?></label>

                    <div class="img" style="width: 150px; height: 155px; overflow: hidden;border: 1px solid #919191;">
                        <img src="<?php echo $user['thumb']; ?>">
                        <div class="links">
                            <a href="#"
                               onclick="openQFinder(this)"><?php echo $language['browse'] ?></a> |
                            <a href="#"
                               onclick="clearImage(this)"><?php echo $language['clear'] ?></a>
                        </div>
                    </div>
                    <input id="photo" type="hidden" name="photo"
                           value="<?php echo $user['photo'] ?>">
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="control-group">
                <label class="control-label"><?php echo $language['description'] ?></label>
                <textarea name="description"
                          class="span12"><?php echo $user['description'] ?></textarea>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">
                        <?php if (isset($user['id'])) { ?>
                            <input type="checkbox" name="chp" id="chp"
                                   value="1"/> <?php echo $language['change_upass'] ?>
                        <?php } else echo $language['password'] ?>
                    </label>
                    <input type="text" name="pass" class="span12"
                           <?php if (isset($user['id'])) { ?>disabled="disabled"<?php } ?>/>
                </div>
            </div>
            <div class="span6">
                <div class="control-group">
                    <label class="control-label"><?php echo $language['birth'] ?></label>
                    <input id="datepicker" type="text" name="birth" class="span12"
                           value="<?php echo $user['birth'] ?>"/>
                </div>
            </div>
        </div>
    </div>
    <?php if (isset($user['id'])) { ?>
        <div class="row-fluid">
            <div class="span12">
                <div class="span4">
                    <div class="control-group">
                        <?php echo $language['joined'] . ': ' . $user['joined'] ?>
                    </div>
                </div>
                <div class="span4">
                    <div class="control-group">
                        <?php echo $language['last_login'] ?>:
                        <?php echo $user['last_login'] ?>
                    </div>
                </div>
                <div class="span4">
                    <div class="control-group">
                        <?php echo $language['adm_last_login'] ?>:
                        <?php echo $user['adm_last_login'] ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</form>
<script type="text/javascript">
    function clearImage(a) {
        var div = jQuery(a).parent().parent();
        div.find('img').replaceWith('<img src="<?php echo ROOT_DIR ?>upload/cache/images/no-image-150x130a.jpg" />');
        jQuery('#photo').val('<?php echo ROOT_DIR ?>upload/images/no-image.jpg');
    }
    function openQFinder(a) {
        function onSelect(fileUrl, data, allFiles) {
            var div = jQuery(a).parent().parent();
            div.find('img').replaceWith('<img src="template/images/ajax-loader.gif" alt="processing..." />');
            var img = new Image();
            img.src = fileUrl;
            img.onload = function () {
                div.find('img').replaceWith('<img src="' + fileUrl + '" />');
                jQuery('#photo').val(fileUrl);
            };
            jQuery('#qfm').remove();
        }

        jQuery('#form').before('<div id="qfm"></div>');
        var qfm = jQuery('#qfm');
        qfm.html('<div id="qfinder"></div>');
        qfm.dialog({
            height: 450,
            width: 900
        });
        var finder = new QFinder();
        var config = {};
        config.height = '380px';
        finder.selectActionFunction = onSelect;
        finder.resourceType = "Images";
        finder.replace('qfinder', config);

        jQuery('.ui-dialog-titlebar-close').live('click', function () {
            jQuery('#qfinder').remove();
            jQuery('#qfm').remove();
        });
    }
</script>
<script type="text/javascript">
    jQuery("#datepicker").datepicker({ dateFormat: "dd-mm-yy" });
    jQuery('#save').click(function () {
        if (jQuery('input:text[name=uname]').val() == '') {
            jQuery('input:text[name=uname]').addClass("alert-value");
            alert('<?php echo $language['name_required'] ?>');
        } else {
            jQuery('#form').submit();
        }
    });
    <?php if (!empty($user)) { ?>
    jQuery('#chp').click(function () {
        var p = jQuery('input[name=\'pass\']');
        if (p.attr('disabled') !== undefined) {
            p.removeAttr('disabled');
        } else {
            p.attr('disabled', 'disabled');
        }
    });
    <?php } ?>
    jQuery('input:text[name=uname]').keypress(function () {
        jQuery('input:text[name=uname]').removeClass("alert-value");
    })
</script>
