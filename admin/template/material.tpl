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
            <h2><?php echo $language['cat_mat_editor'] ?></h2>
        </div>
        <div class="pull-right">
            <div class="margin-top-button">
                <a href="index.php?page=materials" class="btn btn-inverse"><i
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
    <div class="control-group clearfix">
        <div class="span6">
            <label class="control-label"><?php echo $language['alias'] ?></label>

            <div class="controls">
                <input type="text" name="alias"
                       value="<?php echo $material['alias'] ?>"/>
            </div>
            <label class="control-label"><?php echo $language['type'] ?></label>
            <select name="type">
                <option <?php if ((int)$material['is_category'] == 0) echo 'selected="selected" ' ?>value="0"><?php
                    echo $language['material'] ?></option>
                <option <?php if ((int)$material['is_category'] == 1) echo 'selected="selected" ' ?>value="1"><?php
                    echo $language['category'] ?></option>
            </select>
            <label class="control-label"><?php echo $language['parent'] ?></label>
            <select name="parent">
                <option <?php if ((int)$material['parent_id'] == 0) echo 'selected="selected ' ?>value="0">root
                </option>
                <?php foreach ($categories as $category) { ?>
                    <option <?php if ((int)$category['id'] == $material['parent_id']) echo 'selected="selected" '
                            ?>value="<?php echo $category['id'] ?>"><?php echo $category['caption'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="span6">
            <div class="control-group">
                <label class="control-label"><?php echo $language['preview'] ?></label>

                <div class="img" style="width: 150px; height: 155px; overflow: hidden;border: 1px solid #919191;">
                    <img src="<?php echo $material['thumb']; ?>">
                    <div class="links">
                        <a href="#"
                           onclick="openQFinder(this)"><?php echo $language['browse'] ?></a> |
                        <a href="#"
                           onclick="clearImage(this)"><?php echo $language['clear'] ?></a>
                    </div>
                </div>
                <input id="preview" type="hidden" name="preview" value="<?php echo $material['preview'] ?>">
            </div>
        </div>
    </div>
    <ul class="nav nav-tabs" id="myTab">
        <?php $dou = true; ?>
        <?php foreach ($engine->languages as $lang) { ?>
            <li <?php if ($dou) {
                echo 'class="active"';
                $dou = false;
            } ?>>
                <a data-toggle="tab" href="#<?php echo $lang['name'] ?>">
                    <?php echo $lang['description'] ?></a></li>
        <?php } ?>
    </ul>
    <div class="tab-content">
        <?php $dou = true; ?>
        <?php foreach ($engine->languages as $lang) { ?>
            <div class="tab-pane<?php if ($dou) {
                echo ' active';
                $dou = false;
            } ?>" id="<?php echo $lang['name'] ?>">
                <div class="row-fluid">
                    <div class="span12">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo $language['caption'] ?></label>

                                <div class="controls">
                                    <input type="text" class="span12"
                                           name="caption_<?php echo $lang['name'] ?>"
                                           value="<?php echo $material['caption_' . $lang['name']] ?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo $language['title'] ?></label>

                                <div class="controls">
                                    <input type="text" class="span12" name="title_<?php echo $lang['name'] ?>"
                                           value="<?php echo $material['title_' . $lang['name']] ?>"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo $language['kw'] ?></label>

                                <div class="controls">
                                    <input type="text" class="span12"
                                           name="kw_<?php echo $lang['name'] ?>"
                                           value="<?php echo $material['kw_' . $lang['name']] ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="control-group">
                    <label class="control-label"><?php echo $language['descr'] ?></label>

                    <div class="controls">
                        <textarea name="descr_<?php echo $lang['name'] ?>"
                                  style="width:99%; min-height: 100px"><?php echo $material['descr_' .
                            $lang['name']] ?></textarea>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="control-group">
                    <label class="control-label"><?php echo $language['description'] ?></label>

                    <div class="controls">
                        <textarea name="description_<?php echo $lang['name'] ?>"
                                  style="width:99%; min-height: 100px"><?php echo $material['description_' .
                            $lang['name']] ?></textarea>
                    </div>
                </div>
                <textarea name="text_<?php echo $lang['name'] ?>" class="ckeditor"
                          style="width:100%; min-height: 500px;"><?php echo $material['text_' . $lang['name']] ?></textarea>
            </div>
        <?php } ?>
    </div>
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
                jQuery('#preview').val(fileUrl);
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
        jQuery('#form').submit();
    })
</script>
