<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['link'] ?>"><?php echo $breadcrumb['caption'] ?></a> <span
                class="divider">/</span></li>
    <?php } ?>
    <li class="active"><?php echo $breadcrumb_cur ?></li>
</ul>
<div class="hero-unit">
    <div class="pull-left">
        <h2><?php echo $language['language_manager'] ?></h2>
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
    <div class="span3">
        <form method="post" id="lang-new">
            <div class="hero-unit">
                <label><?php echo $language['language_code'] ?>
                    <input class="span12" name="language[code]" type="text"/></label>
                <label><?php echo $language['text_language'] ?>
                    <input class="span12" name="language[description]" type="text"/></label>
                <label><?php echo $language['text_image'] ?>
                    <div style="cursor: pointer" id="language[picture]"
                         onclick="openQFinder(this)"><img
                            src="" alt="no-image"/>
                    </div>
                </label>
                <input type="hidden" name="language[picture]"/>
                <label><?php echo $language['ordering'] ?>
                    <input class="span12" name="language[ordering]" type="text"/></label>
                <button id="lang-adder" type="button" style="width: 100%"
                        class="btn btn-success"><?php echo $language['text_add_language'] ?></button>
                <div class="clearfix"></div>
            </div>
        </form>
    </div>
    <div class="span9">
        <form method="POST" id="language-form">
            <input type="hidden" name="language-action" id="language-action"/>

            <div class="hero-unit" style="font-size: 14px;">
                <div class="hero-unit">
                    <div class="pull-right">
                        <a id="language-save" class="btn btn-success">
                            <i class="icon-ok icon-white"></i>
                            <?php echo $language['save'] ?>
                        </a>
                        <a id="language-remove" class="btn btn-inverse">
                            <i class="icon-trash icon-white"></i>
                            <?php echo $language['text_remove'] ?>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="chk"/></th>
                        <th><?php echo $language['id'] ?></th>
                        <th><?php echo $language['language_code'] ?></th>
                        <th><?php echo $language['text_language'] ?></th>
                        <th><?php echo $language['text_image'] ?></th>
                        <th><?php echo $language['ordering'] ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($engine->languages as $lng) { ?>
                        <tr>
                            <td>
                                <input type="checkbox" class="ids" name="fp-language[]"
                                       value="<?php echo $lng['id'] ?>"/>
                            </td>
                            <td><?php echo $lng['id'] ?></td>
                            <td><?php echo $lng['name'] ?></td>
                            <td><input type="text" name="lng[<?php echo $lng['id'] ?>][description]" value="<?php
                                echo $lng['description'] ?>"/></td>
                            <td>
                                <div style="cursor: pointer" id="lng[<?php echo $lng['id'] ?>][picture]"
                                     onclick="openQFinder(this)"><img
                                        src="<?php echo $lng['picture'] ?>" alt="<?php echo $lng['picture'] ?>"/>
                                </div>
                                <input type="hidden" name="lng[<?php echo $lng['id'] ?>][picture]" value="<?php
                                echo $lng['picture'] ?>"/>
                            </td>
                            <td><input type="text" name="lng[<?php echo $lng['id'] ?>][ordering]" value="<?php
                                echo $lng['ordering'] ?>"/></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    function openQFinder(div) {
        function onSelect(fileUrl, data, allFiles) {
            $(div).find('img').replaceWith('<img src="template/images/ajax-loader.gif" alt="processing..." />');
            var img = new Image();
            img.src = fileUrl;
            img.onload = function () {
                $(div).find('img').replaceWith('<img src="' + fileUrl + '" />');
                $('input[name=\'' + $(div).attr('id') + '\']').val(fileUrl);
            };
            $('#qfm').remove();
        }
        if ($('#qfm').length > 0) return false;
        $('#language-form').before('<div id="qfm"></div>');
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
    $('#lang-adder').click(function () {
        if ($('input:text[name="language[code]"]').val() == '') {
            $('input:text[name="language[code]"]').addClass("alert-value");
            alert('<?php echo $language['lang_code_required'] ?>');
        } else {
            $('#lang-new').submit();
        }
    });
    $('input:text[name="language[code]"]').keypress(function () {
        $('input:text[name="language[code]"]').removeClass("alert-value");
    });
    $('#chk').change(function () {
        if ($(this).is(':checked')) {
            $('.ids').attr('checked', 'checked');
        } else {
            $('.ids').removeAttr('checked');
        }
    });
    $('#language-save').click(function () {
        $('#language-action').val('save');
        $('#language-form').submit();
    });
    $('#language-remove').click(function () {
        if (confirm('<?php echo $language['confirm_delete'] ?>') == true) {
            $('#language-action').val('remove');
            $('#language-form').submit();
        }
    });
</script>