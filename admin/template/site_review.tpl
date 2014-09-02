<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['link'] ?>"><?php echo $breadcrumb['caption'] ?></a> <span
                class="divider">/</span></li>
    <?php } ?>
    <li class="active"><?php echo $breadcrumb_cur ?></li>
</ul>
<div class="tab-content active">
    <form method="post" id="form">
        <input type="hidden" name="sitereview" value="1">
        <input type="hidden" name="action">

        <div class="hero-unit clearfix">
            <div class="pull-left">
                <h2><?php echo $language['site_reviews'] ?></h2>
            </div>
            <div class="pull-right">
                <div class="margin-top-button">
                    <a id="apply" class="btn btn-info"><i
                            class="icon-ok icon-white"></i> <?php echo $language['apply'] ?></a>
                    <a id="save" class="btn btn-success"><i
                            class="icon-ok icon-white"></i> <?php echo $language['save'] ?></a>
                    <a href="index.php?page=sitereviews" class="btn btn-inverse"><i
                            class="icon-remove icon-white"></i> <?php echo $language['cancel'] ?></a>
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
                        <input type="text" name="name" class="span12"
                               value="<?php echo (!empty($review)) ? $review['name'] : '' ?>"/>
                    </div>
                    <div class="control-group">
                        <label class="control-label"><?php echo $language['email'] ?></label>
                        <input type="text" name="email" class="span12"
                               value="<?php echo (!empty($review)) ? $review['email'] : '' ?>"/>
                    </div>
                    <div class="control-group">
                        <label class="control-label"><?php echo $language['enabled'] ?></label>
                        <input type="checkbox" name="enabled" value="1"
                            <?php echo (!empty($review) && $review['enabled'] == '1') ? 'checked="checked"' : '' ?>/>
                    </div>
                </div>
                <div class="span6">
                    <div class="control-group">
                        <label class="control-label"><?php echo $language['photo'] ?></label>

                        <div class="img" style="width: 150px; height: 155px; overflow: hidden;border: 1px solid #919191;">
                            <img src="<?php echo $review['thumb']; ?>">
                            <div class="links">
                                <a href="#"
                                   onclick="openQFinder(this)"><?php echo $language['browse'] ?></a> |
                                <a href="#"
                                   onclick="clearImage(this)"><?php echo $language['clear'] ?></a>
                            </div>
                        </div>
                        <input id="photo" type="hidden" name="photo"
                               value="<?php echo (!empty($review)) ? $review['photo'] : ROOT_DIR . 'upload/images/no-image.jpg' ?>">
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="control-group">
                    <label class="control-label"><?php echo $language['post'] ?></label>
                    <textarea name="post" style="min-height: 300px"
                              class="span12"><?php echo (!empty($review)) ? $review['post'] : '' ?></textarea>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    function clearImage(a) {
        var div = jQuery(a).parent().parent();
        div.find('img').replaceWith('<img src="<?php echo ROOT_DIR ?>upload/cache/images/no-image-150x130a.jpg" />');
        jQuery('#photo').val('<?php echo ROOT_DIR ?>upload/images/no-image.jpg');
    }
    function openQFinder(a) {
        function onSelect(fileUrl, data, allFiles) {
            var div = jQuery(a).parent().parent();
            div.find('img').replaceWith('<img src="<?php echo ADM_PATH ?>template/images/ajax-loader.gif" alt="processing..." />');
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
    jQuery('#save').click(function () {
        if (jQuery('input:text[name="name"]').val() == '') {
            jQuery('input:text[name="name"]').addClass("alert-value");
            alert('<?php echo $language['name_required'] ?>');
        } else {
            jQuery('input:hidden[name=action]').val('save');
            jQuery('#form').submit();
        }
    });
    jQuery('#apply').click(function () {
        if (jQuery('input:text[name="name"]').val() == '') {
            jQuery('input:text[name="name"]').addClass("alert-value");
            alert('<?php echo $language['name_required'] ?>');
        } else {
            jQuery('input:hidden[name=action]').val('apply');
            jQuery('#form').submit();
        }
    });
    jQuery('input:text[name="name"]').keypress(function () {
        jQuery('input:text[name="name"]').removeClass("alert-value");
    })
</script>
