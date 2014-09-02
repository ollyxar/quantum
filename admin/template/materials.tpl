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
            <h2><?php echo $language['materials'] ?></h2>
        </div>
        <div class="pull-right">
            <div class="margin-top-button">
                <a id="settings" class="btn btn-info"><i
                        class="icon-gear icon-white"></i> <?php echo $language['button_settings'] ?>
                </a>
                <a href="index.php?page=materials&view=tiny&id=new&parent=<?php echo $parent_id ?>"
                   class="btn btn-primary"><i class="icon-pencil icon-white"></i> <?php echo $language['create'] ?>
                </a>
                <a id="save" class="btn btn-success"><i
                        class="icon-ok icon-white"></i> <?php echo $language['save']; ?></a>
                <a id="activate" class="btn btn-warning"><i
                        class="icon-ok-circle icon-white"></i> <?php echo $language['activate'] ?></a>
                <a id="deactivate" class="btn btn-danger"><i
                        class="icon-lock icon-white"></i> <?php echo $language['deactivate'] ?></a>
                <a id="duplicate" class="btn btn-warning"><i
                        class="icon-ok-circle icon-white"></i> <?php echo $language['duplicate'] ?></a>
                <a id="remove" class="btn btn-inverse"><i
                        class="icon-trash icon-white"></i> <?php echo $language['delete'] ?></a>
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
    <div id="settings-block" class="hero-unit" style="display: none">
        <div class="pull-left"><label><?php echo $language['count_per_page'] ?>:</label>
            <input type="text" name="count_per_page" value="<?php
            echo $settings['count_per_page'] ?>"></div>

        <?php foreach ($engine->languages as $lang) { ?>
            <div class="pull-right"><label><?php echo $language['details'] . ' (' . $lang['description'] . ')' ?>
                    :</label>
                <input type="text" name="details_<?php echo $lang['name'] ?>" value="<?php
                echo $settings['details_' . $lang['name']] ?>"></div>

        <?php } ?>
        <div class="clearfix"></div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="span3">
                <ul id="red" class="treeview">
                    <?php
                    global $cur_cat;
                    function printCategories($categories, $cat_id)
                    {
                        global $cur_cat;
                        foreach ($categories as $id => $category) {
                            ?>
                            <li><span onclick="document.location.href='index.php?page=materials&parent=<?php
                                echo $id ?>'" class="<?php if ($cat_id == $id) {
                                    $cur_cat = $category['caption'];
                                    echo "active";
                                } ?>"><?php echo $category['caption'] ?></span>
                                <?php if (!empty($category['subs'])) { ?>
                                    <ul>
                                        <?php printCategories($category['subs'], $cat_id) ?>
                                    </ul>
                                <?php } ?>
                            </li>
                        <?php
                        }
                    }

                    printCategories($categories, $parent_id);
                    ?>
                </ul>
            </div>
            <div class="span9">
                <?php if (isset($cur_cat)) { ?>
                    <div class="hero-unit clearfix">
                        <div class="pull-left">
                            <?php if ($parent_id > 0) { ?>
                                <a href="index.php?page=materials&view=tiny&id=<?php echo $parent_id ?>"
                                   class="btn btn-primary" style="margin-top: -2px">
                                    <i class="icon-pencil icon-white"></i>
                                    <?php echo $language['edit'] ?>
                                </a>
                                <a id="remove_cat" class="btn btn-inverse" style="margin-top: -2px">
                                    <i class="icon-remove icon-white"></i>
                                    <?php echo $language['text_remove'] ?>
                                </a>
                            <?php } ?>
                            <?php echo $cur_cat ?>
                        </div>
                        <div class="pull-right">
                            <div class="input-append" style="margin-bottom: 0;">
                                <input name="search" type="text" value="<?php echo $search ?>">
                                <button id="button-search" class="btn" type="button"><?php
                                    echo $language['button_search'] ?></button>
                                <button class="btn" type="button" onclick="window.location = 'index.php?page=materials&parent=<?php echo $parent_id ?>'"><?php echo
                                    $language['button_clear'] ?></button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="chk"/></th>
                            <th><?php echo $language['id'] ?></th>
                            <?php foreach ($engine->languages as $lang) { ?>
                                <th><?php echo $language['caption'] ?> (<?php echo $lang['description'] ?>)</th>
                            <?php } ?>
                            <th><?php echo $language['enabled'] ?></th>
                            <th><?php echo $language['action'] ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($materials)) { ?>
                            <tr>
                                <td colspan="10"
                                    style="text-align: center"><?php echo $language['no_records'] ?></td>
                            </tr>
                        <?php } ?>
                        <?php foreach ($materials as $material) { ?>
                            <tr>
                                <td><input type="checkbox" class="ids" name="fp[]" value="<?php echo $material['id'] ?>"/>
                                </td>
                                <td><?php echo $material['id'] ?></td>
                                <?php foreach ($engine->languages as $lang) { ?>
                                    <td><input type="text" name="pages[<?php echo $material['id'] ?>][caption_<?php
                                        echo $lang['name'] ?>]"
                                               value="<?php echo $material['caption_' . $lang['name']] ?>"/>
                                    </td>
                                <?php } ?>
                                <td style="text-align: center">
                                    <?php if ((int)$material['enabled'] == 1) { ?>
                                        <i class="icon-ok-sign"></i>
                                    <?php } else { ?>
                                        <i class="icon-minus-sign"></i>
                                    <?php } ?>
                                </td>
                                <td>
                                    <a href="index.php?page=materials&view=tiny&id=<?php echo $material['id'] ?>"
                                       class="btn btn-info btn-mini"><i
                                            class="icon-edit icon-white"></i> <?php echo $language['edit'] ?></a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <div class="hero-unit clearfix"><?php echo $language['pls_select_category'] ?></div>
                <?php } ?>
            </div>
        </div>
    </div>
</form>
<?php $s = ($search <> '') ? '&search=' . $search : ''; ?>
<div class="pull-right"><?php echo printPagination($page_count, 'index.php?page=materials' . $s . '&parent=' .
        $parent_id, false, true) ?></div>
<script type="text/javascript">
    jQuery('#chk').change(function () {
        if (jQuery('#chk').attr("checked") == "checked") {
            jQuery('.ids').attr('checked', 'checked');
        } else {
            jQuery('.ids').removeAttr('checked');
        }
    });
    jQuery('#save').click(function () {
        jQuery('#action').val('save');
        jQuery('#form').submit();
    });
    jQuery('#settings').click(function () {
        jQuery('#settings-block').slideToggle(200);
    });
    jQuery('#activate').click(function () {
        jQuery('#action').val('activate');
        jQuery('#form').submit();
    });
    jQuery('#duplicate').click(function () {
        jQuery('#action').val('duplicate');
        jQuery('#form').submit();
    });
    jQuery('#deactivate').click(function () {
        jQuery('#action').val('deactivate');
        jQuery('#form').submit();
    });
    jQuery('#remove').click(function () {
        if (confirm("<?php echo $language['confirm_delete'] ?>") == true) {
            jQuery('#action').val('remove');
            jQuery('#form').submit();
        }
    });
    jQuery('#remove_cat').click(function () {
        if (confirm("<?php echo $language['confirm_delete'] ?>") == true) {
            jQuery('#action').val('remove_cat');
            jQuery('#form').submit();
        }
    });
    jQuery('#button-search').click(function () {
        var srch = jQuery('input[name=\'search\']').attr('value');
        window.location = 'index.php?page=materials&parent=<?php echo $parent_id ?>&search=' + srch;
    });
    jQuery('input[name=\'search\']').keydown(function (e) {
        if (e.keyCode == 13) {
            var srch = jQuery(this).attr('value');
            window.location = 'index.php?page=materials&parent=<?php echo $parent_id ?>&search=' + srch;
        }
    });
    jQuery(document).ready(function () {
        jQuery("#red").treeview({
            animated: "fast",
            collapsed: false,
            unique: true,
            persist: "cookie"
        });
    });

</script>
