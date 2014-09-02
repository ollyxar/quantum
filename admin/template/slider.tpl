<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['link'] ?>"><?php echo $breadcrumb['caption'] ?></a> <span
                class="divider">/</span></li>
    <?php } ?>
    <li class="active"><?php echo $breadcrumb_cur ?></li>
</ul>
<form id="form" method="post">
    <input type="hidden" id="action" name="action"/>

    <div class="hero-unit clearfix">
        <div class="pull-left">
            <h2><?php echo $language['slider'] ?></h2>
        </div>
        <div class="pull-right">
            <div class="margin-top-button">
                <a onclick="window.location = 'index.php?page=slider'" class="btn btn-warning">
                    <i class="icon-ban-circle icon-white"></i> <?php echo $language['cancel']; ?>
                </a>
                <a id="save" class="btn btn-success"><i
                        class="icon-ok icon-white"></i> <?php echo $language['save']; ?></a>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <?php if (isset($text_message)) { ?>
        <div class="alert text-center alert-<?php echo $class_message ?>">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <?php echo $text_message ?>
        </div>
    <?php } ?>
    <div class="row-fluid">
        <div class="span12">
            <table class="table table-bordered table-striped" id="sl">
                <thead>
                <tr>
                    <th><?php echo $language['id'] ?></th>
                    <th><?php echo $language['picture'] ?></th>
                    <th><?php echo $language['link'] ?></th>
                    <th><?php echo $language['action'] ?></th>
                </tr>
                </thead>
                <?php $id = -1; ?>
                <?php foreach ($slides as $id => $slide) { ?>
                    <tbody id="slide<?php echo $id ?>">
                    <tr>
                        <td><?php echo $id ?></td>
                        <td>
                            <div class="img" id="slides[<?php echo $id ?>]">
                                <img src="<?php echo $slide['thumb'] ?>" alt="">

                                <div class="links">
                                    <a href="#"
                                       onclick="openQFinder(this)"><?php echo $language['browse'] ?></a> |
                                    <a href="#"
                                       onclick="clearImage(this)"><?php echo $language['clear'] ?></a>
                                </div>
                            </div>
                            <input id="slides[<?php echo $id ?>][src]" type="hidden" name="slides[<?php
                            echo $id ?>][src]" value="<?php echo $slide['src'] ?>"></td>
                        <td><input type="text" name="slides[<?php echo $id ?>][link]"
                                   value="<?php echo $slide['link'] ?>"/></td>
                        <td><a onclick="jQuery('#slide<?php echo $id ?>').remove();" class="btn btn-danger"><?php
                                echo $language['text_remove'] ?></a></td>
                    </tr>
                    </tbody>
                <?php } ?>
                <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td><a onclick="addSlide();" class="btn btn-success pull-right"><?php
                            echo $language['text_add'] ?></a></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</form>
<script type="text/javascript">
    function clearImage(a) {
        var div = jQuery(a).parent().parent();
        div.find('img').replaceWith('<img src="<?php echo ROOT_DIR ?>upload/cache/images/no-image-90x80a.jpg" />');
        jQuery('input[name=\'' + div.attr('id') + '[src]\']').val('<?php echo ROOT_DIR ?>upload/images/no-image.jpg');
    }
    function openQFinder(a) {
        function onSelect(fileUrl, data, allFiles) {
            var div = jQuery(a).parent().parent();
            div.find('img').replaceWith('<img src="template/images/ajax-loader.gif" alt="processing..." />');
            var img = new Image();
            img.src = fileUrl;
            img.onload = function () {
                div.find('img').replaceWith('<img src="' + fileUrl + '" />');
                jQuery('input[name=\'' + div.attr('id') + '[src]\']').val(fileUrl);
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
    var id = <?php echo $id ?>;
    function addSlide() {
        id++;
        var html = '<tbody id="slide' + id + '"><tr>';
        html += '<td>' + id + '</td>';
        html += '<td><div class="img" id="slides[' + id + ']">';
        html += '<img src="<?php echo ROOT_DIR ?>upload/cache/images/no-image-90x80a.jpg" alt="">';
        html += '<div class="links">';
        html += '<a href="#" onclick="openQFinder(this)"><?php echo $language['browse'] ?></a> | ';
        html += '<a href="#" onclick="clearImage(this)"><?php echo $language['clear'] ?></a></div></div>';
        html += '<input id="slides[' + id + '][src]" type="hidden" name="slides[' + id + '][src]" value="<?php echo ROOT_DIR ?>upload/images/no-image.jpg" /></td>';
        html += '<td><input type="text" name="slides[' + id + '][link]"></td>';
        html += '<td><a onclick="jQuery(\'#slide' + id + '\').remove();" class="btn btn-danger"><?php
                                    echo $language['text_remove'] ?></a></td>';
        html += '</tr></tbody>';
        jQuery('#sl tfoot').before(html);
    }
    jQuery('#save').click(function () {
        jQuery('#action').val('save');
        jQuery('#form').submit();
    });
</script>
