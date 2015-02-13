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
                    <th><?php echo $language['picture'] ?></th>
                    <th><?php echo $language['link'] ?></th>
                    <th><?php echo $language['action'] ?></th>
                </tr>
                </thead>
                <?php foreach ($slides as $id => $slide) { ?>
                    <tbody data-id="<?php echo $id ?>">
                    <tr>
                        <td>
                            <div class="img">
                                <img src="<?php echo $slide['thumb'] ?>" alt="">

                                <div class="links">
                                    <a onclick="openQFinder(this)"><?php echo $language['browse'] ?></a> |
                                    <a onclick="clearImage(this)"><?php echo $language['clear'] ?></a>
                                </div>
                                <input id="slides[<?php echo $id ?>][src]" type="hidden" name="slides[<?php echo $id ?>][src]" value="<?php echo $slide['src'] ?>">
                            </div>
                            </td>
                        <td><input type="text" name="slides[<?php echo $id ?>][link]"
                                   value="<?php echo $slide['link'] ?>"/></td>
                        <td><a onclick="$(this).parent().parent().parent().remove();" class="btn btn-danger"><?php
                                echo $language['text_remove'] ?></a></td>
                    </tr>
                    </tbody>
                <?php } ?>
                <tfoot>
                <tr>
                    <td colspan="2"></td>
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
        var div = $(a).parent().parent();
        div.find('img').replaceWith('<img src="<?php echo ROOT_DIR ?>upload/cache/images/no-image-90x80a.jpg" />');
        div.find('input[type=hidden]').val('<?php echo ROOT_DIR ?>upload/images/no-image.jpg');
    }
    function openQFinder(a) {
        function onSelect(fileUrl, data, allFiles) {
            var div = $(a).parent().parent();
            div.find('img').replaceWith('<img src="template/images/ajax-loader.gif" alt="processing..." />');
            var img = new Image();
            img.src = fileUrl;
            img.onload = function () {
                div.find('img').replaceWith('<img src="' + fileUrl + '" />');
                div.find('input[type=hidden]').val(fileUrl);
            };
            $('#qfm').remove();
        }
        if ($('#qfm').length > 0) return false;
        $('#form').before('<div id="qfm"></div>');
        var qfm = $('#qfm');
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

        $('body').on('click', '.ui-dialog-titlebar-close', function () {
            $('#qfinder').remove();
            $('#qfm').remove();
        });
    }
    function addSlide() {
        var id = $('#sl tbody:last').attr('data-id') || 0;
        id++;
        var html = '<tbody data-id="' + id + '"><tr>';
        html += '<td><div class="img" id="slides[' + id + ']">';
        html += '<img src="<?php echo ROOT_DIR ?>upload/cache/images/no-image-90x80a.jpg" alt="">';
        html += '<div class="links">';
        html += '<a onclick="openQFinder(this)"><?php echo $language['browse'] ?></a> | ';
        html += '<a onclick="clearImage(this)"><?php echo $language['clear'] ?></a></div>';
        html += '<input type="hidden" name="slides[' + id + '][src]" value="<?php echo ROOT_DIR ?>upload/images/no-image.jpg" /></div></td>';
        html += '<td><input type="text" name="slides[' + id + '][link]"></td>';
        html += '<td><a onclick="$(this).parent().parent().parent().remove();" class="btn btn-danger"><?php echo $language['text_remove'] ?></a></td>';
        html += '</tr></tbody>';
        $('#sl tfoot').before(html);
    }
    $('#save').click(function () {
        $('#action').val('save');
        $('#form').submit();
    });
</script>
