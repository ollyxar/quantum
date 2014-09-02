<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['link'] ?>"><?php echo $breadcrumb['caption'] ?></a> <span
                class="divider">/</span></li>
    <?php } ?>
    <li class="active"><?php echo $breadcrumb_cur ?></li>
</ul>
<div class="hero-unit clearfix">
    <div class="pull-left">
        <h2><?php echo $language['main_menu'] ?></h2>
    </div>
    <div class="pull-right">
        <div class="margin-top-button">
            <a id="settings" class="btn btn-info"><i
                    class="icon-gear icon-white"></i> <?php echo $language['button_settings'] ?>
            </a>
            <a href="index.php?page=mainmenu&menu_id=new&parent=<?php echo $menu_id ?>"
               class="btn btn-primary"><i class="icon-pencil icon-white"></i> <?php echo $language['create'] ?>
            </a>
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
    <form method="post" id="form-settings">
        <input type="hidden" id="action-settings" name="action"/>

        <div class="pull-left"><label><?php echo $language['highlight_active'] ?>:</label></div>
        <div class="span2">
            <label class="pull-left span2">
                <input type="radio" name="ha" value="1" <?php if ($settings['ha'] == true)
                    echo 'checked="checked"' ?> /><?php echo $language['text_yes'] ?>
            </label>
            <label class="pull-left span2">
                <input type="radio" name="ha" value="0" <?php if ($settings['ha'] == false)
                    echo 'checked="checked"' ?> /><?php echo $language['text_no'] ?>
            </label>
        </div>
        <div class="pull-right">
            <a id="save-settings" class="btn btn-success"><i
                    class="icon-ok icon-white"></i> <?php echo $language['save']; ?></a>
        </div>
        <div class="clearfix"></div>
    </form>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="span3">
            <ul id="red" class="treeview">
                <?php
                function printMenu($menu, $menu_id) {
                    foreach ($menu as $id => $menu_item) {
                        ?>
                        <li><span
                                onclick="document.location.href='index.php?page=mainmenu&menu_id=<?php
                                echo $id ?>'" class="<?php if ($menu_id == $id) {
                                echo "active";
                            } ?>"><?php echo $menu_item['caption'] ?></span>
                            <?php if (!empty($menu_item['subs'])) { ?>
                                <ul>
                                    <?php printMenu($menu_item['subs'], $menu_id) ?>
                                </ul>
                            <?php } ?>
                        </li>
                    <?php
                    }
                }

                printMenu($menu, $menu_id);
                ?>
            </ul>
        </div>
        <div class="span9">
            <div class="hero-unit" style="font-size: 14px;">
                <form id="form" method="post">
                    <input type="hidden" id="action" name="action"/>
                    <?php if ($menu_id > 0 || (isset($_GET['menu_id']) && $_GET['menu_id'] == 'new')) { ?>
                    <div class="hero-unit">
                        <div class="pull-right">
                            <a id="save" class="btn btn-success"><i
                                    class="icon-ok icon-white"></i> <?php echo $language['save']; ?></a>
                            <a id="remove" class="btn btn-inverse"><i
                                    class="icon-trash icon-white"></i> <?php echo $language['delete'] ?></a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th><?php echo $language['id'] ?></th>
                            <th><?php echo $language['path'] ?></th>
                            <th><?php echo $language['caption'] ?></th>
                            <th><?php echo $language['ordering'] ?></th>
                            <th><?php echo $language['enabled'] ?></th>
                            <th><?php echo $language['parent'] ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($_GET['menu_id'] == 'new') { ?>
                            <tr>
                                <td><?php echo $language['new'] ?></td>
                                <td><input id="path" onclick="getPages()" type="text" name="menu[path]"/>
                                    <div id="hint"><ul></ul></div>
                                    <div id="real-path"></div>
                                </td>
                                <td>
                                    <table class="table-bordered span12">
                                        <?php foreach ($engine->languages as $lang) { ?>
                                            <tr>
                                                <td><?php echo $lang['description'] ?>
                                                    <input type="text" class="span12"
                                                           name="menu[caption_<?php
                                                           echo $lang['name'] ?>]"/>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </td>
                                <td style="width: 8%"><input class="span12" type="text"
                                                             name="menu[ordering]"/></td>
                                <td style="width: 8%">
                                    <input id="sts_h" type="hidden" name="menu[enabled]"
                                           value="1">

                                    <div id="sts">
                                        <input type="checkbox" checked="checked">
                                    </div>
                                </td>
                                <td><select name="menu[parent]">
                                        <?php foreach ($parents_to_menu as $id => $parent) { ?>
                                            <option <?php if ($_GET['parent'] == $id) echo 'selected="selected"'
                                            ?> value="<?php echo $id ?>"><?php echo $parent['caption']
                                                ?></option>
                                        <?php } ?>
                                    </select></td>
                            </tr>
                        <?php } elseif (empty($menu_item)) { ?>
                            <tr>
                                <td colspan="10"
                                    style="text-align: center"><?php echo $language['no_records'] ?></td>
                            </tr>
                        <?php } else { ?>
                            <tr>
                                <td><?php echo $menu_item['id'] ?></td>
                                <td><input id="path" onclick="getPages()" type="text" name="menu[<?php echo $menu_item['id'] ?>][path]"
                                           value="<?php echo $menu_item['path'] ?>"/>
                                    <div id="hint"><ul></ul></div>
                                    <div id="real-path"></div>
                                </td>
                                <td>
                                    <table class="table-bordered span12">
                                        <tbody>
                                        <?php foreach ($engine->languages as $lang) { ?>
                                            <tr>
                                                <td><?php echo $lang['description'] ?>
                                                    <input type="text" class="span12"
                                                           name="menu[<?php echo $menu_item['id'] ?>][caption_<?php
                                                           echo $lang['name'] ?>]"
                                                           value="<?php echo $menu_item['caption_' . $lang['name']] ?>"/>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </td>
                                <td style="width: 8%"><input class="span12" type="text"
                                                             name="menu[<?php echo $menu_item['id'] ?>][ordering]"
                                                             value="<?php echo $menu_item['ordering'] ?>"/></td>
                                <td style="width: 8%">
                                    <input id="sts_h" type="hidden" name="menu[<?php echo $menu_item['id'] ?>][enabled]"
                                           value="<?php echo $menu_item['enabled'] ?>">

                                    <div id="sts">
                                        <input
                                            type="checkbox"<?php if ($menu_item['enabled'] == '1') echo ' checked="checked"'; else echo '' ?>>
                                    </div>
                                </td>
                                <td><select name="menu[<?php echo $menu_item['id'] ?>][parent]">
                                        <?php foreach ($parents_to_menu as $id => $parent) { ?>
                                            <option <?php if ($menu_item['parent'] == $id) echo 'selected="selected"'
                                            ?> value="<?php echo $id ?>"><?php echo $parent['caption']
                                                ?></option>
                                        <?php } ?>
                                    </select></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </form>
            </div>
            <?php } else { ?>
                <div class="hero-unit clearfix"><?php echo $language['pls_select_menu_item'] ?></div>
            <?php } ?>
        </div>
    </div>
</div>

<script type="text/javascript">

    jQuery('#sts').toggleButtons({
        onChange: function ($el, status, e) {
            if (status == true) {
                jQuery('#sts_h').val('1');
            } else {
                jQuery('#sts_h').val('0');
            }
        }
    });

    jQuery('#save').click(function () {
        jQuery('#action').val('save');
        jQuery('#form').submit();
    });

    jQuery('#save-settings').click(function () {
        jQuery('#action-settings').val('save-settings');
        jQuery('#form-settings').submit();
    });

    jQuery('#settings').click(function () {
        jQuery('#settings-block').slideToggle(200);
    });

    jQuery('#remove').click(function () {
        if (confirm("<?php echo $language['confirm_delete'] ?>") == true) {
            jQuery('#action').val('remove');
            jQuery('#form').submit();
        }
    });

    jQuery(document).ready(function () {
        jQuery("#red").treeview({
            animated: "fast",
            collapsed: false,
            unique: true,
            persist: "cookie"
        });
        getRealPath(jQuery('#path').attr('value'));
    });

    jQuery('#path').keyup(function() {
        getRealPath(jQuery(this).attr('value'));
    });
    jQuery('#path').change(function() {
        getRealPath(jQuery(this).attr('value'));
    });

    jQuery('#hint ul').mouseleave(function() {
        jQuery(this).html('');
    });

    jQuery('#hint ul li').live('click', function() {
        jQuery('#path').attr('value', jQuery(this).attr('data-route'));
        getRealPath(jQuery('#path').attr('value'));
        jQuery('#hint ul').html('');
    });

    function getPages() {
        var res_box = jQuery('#hint ul');
        jQuery.ajax({
            url: 'index.php?page=mainmenu',
            type: 'POST',
            dataType: 'json',
            data: 'get_pages=1',
            beforeSend: function() {
                res_box.slideUp(200);
                res_box.html('');
            },
            success: function(data) {
                var m = '';
                jQuery.each(data['materials'], function(){
                    m += '<li data-route="' + this['route'] + '">' + this['caption'] + '</li>';
                });
                var s = '';
                jQuery.each(data['s_pages'], function(){
                    s += '<li data-route="' + this['route'] + '">' + this['caption'] + '</li>';
                });
                res_box.append('<li class="caption"><?php echo $language['static_pages'] ?></li>');
                res_box.append(s);
                res_box.append('<li class="caption"><?php echo $language['materials'] ?></li>');
                res_box.append(m);
            },
            complete: function() {
                res_box.slideDown(200);
            },
            error: function() {
                console.log('error occurred');
            }
        });
    }

    function getRealPath(path) {
        jQuery.ajax({
            url: 'index.php?page=mainmenu',
            type: 'POST',
            dataType: 'json',
            data: 'get_real_path=' + encodeURIComponent(path),
            success: function(data) {
                jQuery('#real-path').html(data);
            },
            complete: function() {
                console.log('complete');
            },
            error: function(data, status) {
                console.log('damn' + data + status);
            }
        });
    }

</script>
