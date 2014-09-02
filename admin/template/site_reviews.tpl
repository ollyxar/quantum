<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['link'] ?>"><?php echo $breadcrumb['caption'] ?></a> <span
                class="divider">/</span></li>
    <?php } ?>
    <li class="active"><?php echo $breadcrumb_cur ?></li>
</ul>
<div class="tab-content active">
    <div class="row-fluid">
        <form id="form" method="post">
            <input type="hidden" id="action" name="action"/>

            <div class="hero-unit clearfix">
                <div class="pull-left">
                    <h2><?php echo $language['site_reviews'] ?></h2>
                </div>
                <div class="pull-right">
                    <div class="margin-top-button">
                        <a href="index.php?page=sitereviews&view=tiny&id=new"
                           class="btn btn-primary"><i
                                class="icon-pencil icon-white"></i> <?php echo $language['create'] ?>
                        </a>
                        <a id="activate" class="btn btn-warning"><i
                                class="icon-ok-circle icon-white"></i> <?php echo $language['activate'] ?></a>
                        <a id="deactivate" class="btn btn-danger"><i
                                class="icon-lock icon-white"></i> <?php echo $language['deactivate'] ?></a>
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
            <div class="pull-right hero-unit">
                <div class="input-append" style="margin-bottom: 0;">
                    <input name="search" type="text" value="<?php echo $search ?>">
                    <button id="button-search" class="btn" type="button"><?php
                        echo $language['button_search'] ?></button>
                    <button class="btn" type="button" onclick="window.location = 'index.php?page=sitereviews'"><?php echo $language['button_clear'] ?></button>
                </div>
            </div>

            <div class="control-group pull-left">
                <label class="control-label"><?php echo $language['alias'] ?></label>

                <div class="controls">
                    <input type="text" class="span12" name="alias"
                           value="<?php echo $alias ?>"/>
                </div>
            </div>
            <div class="clearfix"></div>

            <a id="save" class="btn btn-success pull-right"><i
                    class="icon-ok icon-white"></i> <?php echo $language['save']; ?></a>

            <ul class="nav nav-tabs">
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
                                <div class="control-group">
                                    <label class="control-label"><?php echo $language['title'] ?></label>

                                    <div class="controls">
                                        <input type="text" class="span12" name="title_<?php echo $lang['name'] ?>"
                                               value="<?php echo $settings['title_' . $lang['name']] ?>"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo $language['kw'] ?></label>

                                    <div class="controls">
                                        <input type="text" class="span12"
                                               name="kw_<?php echo $lang['name'] ?>"
                                               value="<?php echo $settings['kw_' . $lang['name']] ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row-fluid">
                        <div class="control-group">
                            <label class="control-label"><?php echo $language['descr'] ?></label>

                            <div class="controls">
                                <textarea name="descr_<?php echo $lang['name'] ?>"
                                          style="width:99%; min-height: 100px"><?php echo $settings['descr_' . $lang['name']] ?></textarea>
                            </div>
                        </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row-fluid">
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo $language['leave_review_btn'] ?></label>
                                    <div class="controls">
                                        <input class="span12" name="leave_review_btn_<?php echo $lang['name'] ?>" type="text" value="<?php echo $settings['leave_review_btn_' . $lang['name']] ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo $language['form_caption'] ?></label>
                                    <div class="controls">
                                        <input class="span12" name="form_caption_<?php echo $lang['name'] ?>" type="text" value="<?php echo $settings['form_caption_' . $lang['name']] ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo $language['name_placeholder'] ?></label>
                                    <div class="controls">
                                        <input class="span12" name="name_placeholder_<?php echo $lang['name'] ?>" type="text" value="<?php echo $settings['name_placeholder_' . $lang['name']] ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row-fluid">
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo $language['email_placeholder'] ?></label>
                                    <div class="controls">
                                        <input class="span12" name="email_placeholder_<?php echo $lang['name'] ?>" type="text" value="<?php echo $settings['email_placeholder_' . $lang['name']] ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo $language['review_placeholder'] ?></label>
                                    <div class="controls">
                                        <input class="span12" name="review_placeholder_<?php echo $lang['name'] ?>" type="text" value="<?php echo $settings['review_placeholder_' . $lang['name']] ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo $language['post_btn'] ?></label>
                                    <div class="controls">
                                        <input class="span12" name="post_btn_<?php echo $lang['name'] ?>" type="text" value="<?php echo $settings['post_btn_' . $lang['name']] ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row-fluid">
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo $language['cancel_btn'] ?></label>
                                    <div class="controls">
                                        <input class="span12" name="cancel_btn_<?php echo $lang['name'] ?>" type="text" value="<?php echo $settings['cancel_btn_' . $lang['name']] ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo $language['error_name'] ?></label>
                                    <div class="controls">
                                        <input class="span12" name="error_name_<?php echo $lang['name'] ?>" type="text" value="<?php echo $settings['error_name_' . $lang['name']] ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo $language['error_text'] ?></label>
                                    <div class="controls">
                                        <input class="span12" name="error_text_<?php echo $lang['name'] ?>" type="text" value="<?php echo $settings['error_text_' . $lang['name']] ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>


            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th><input type="checkbox" id="chk"/></th>
                    <th><?php echo $language['id'] ?></th>
                    <th><?php echo $language['photo'] ?></th>
                    <th><?php echo $language['name'] ?></th>
                    <th><?php echo $language['email'] ?></th>
                    <th><?php echo $language['post'] ?></th>
                    <th><?php echo $language['enabled'] ?></th>
                    <th><?php echo $language['action'] ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($reviews as $review) { ?>
                    <tr>
                        <td><input type="checkbox" class="ids" name="fp[]" value="<?php echo $review['id'] ?>"/></td>
                        <td><?php echo $review['id'] ?></td>
                        <td>
                            <img style="height: 50px; width: 50px" src="<?php echo $review['photo'] ?>" alt="photo"/>
                        </td>
                        <td><?php echo $review['name'] ?></td>
                        <td><?php echo $review['email'] ?></td>
                        <td><?php echo mb_substr($review['post'], 0, 150, "utf-8") . '...' ?></td>
                        <td style="text-align: center">
                            <?php if ($review['enabled']) { ?>
                                <i class="icon-ok-sign"></i>
                            <?php } else { ?>
                                <i class="icon-minus-sign"></i>
                            <?php } ?>
                        </td>
                        <td>
                            <a href="index.php?page=sitereviews&view=tiny&id=<?php echo $review['id'] ?>"
                               class="btn btn-info btn-mini"><i
                                    class="icon-edit icon-white"></i> <?php echo $language['edit'] ?></a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </form>
    </div>
    <?php $s = ($search <> '') ? '&search=' . $search : ''; ?>
    <form class="form-inline pull-left">
        <input type="hidden" name="page" value="users">
        <?php if ($search <> '') { ?>
            <input type="hidden" name="search" value="<?php echo $search ?>">
        <?php } ?>
        <input type="hidden" name="page_n" value="<?php echo (int)$_GET['page_n'] ?>">

        <div class="control-group">
            <label for="per_page"><?php echo $language['per_page'] ?>:</label>
            <select id="per_page" name="per_page" class="input-small" onchange="this.form.submit();">
                <?php for ($i = 1; $i <= 5; $i++) { ?>
                    <option <?php if ((int)$_GET['per_page'] == (int)($i . '0')) echo 'selected="selected"'
                    ?> value="<?php echo $i ?>0"><?php echo $i ?>0
                    </option>
                <?php } ?>
            </select>
        </div>
    </form>
    <div class="pull-right">
        <?php echo printPagination($page_count, 'index.php?page=sitereviews' . $s . '&per_page=' . (int)$_GET['per_page'], false, true) ?>
    </div>
</div>
<script type="text/javascript">
    jQuery('#chk').change(function () {
        if (jQuery('#chk').attr("checked") == "checked") {
            jQuery('.ids').attr('checked', 'checked');
        } else {
            jQuery('.ids').removeAttr('checked');
        }
    });
    jQuery('#activate').click(function () {
        jQuery('#action').val('activate');
        jQuery('#form').submit();
    });
    jQuery('#deactivate').click(function () {
        jQuery('#action').val('deactivate');
        jQuery('#form').submit();
    });
    jQuery('#save').click(function () {
        jQuery('#action').val('save_site_reviews');
        jQuery('#form').submit();
    });
    jQuery('#remove').click(function () {
        if (confirm("<?php echo $language['confirm_delete'] ?>") == true) {
            jQuery('#action').val('remove');
            jQuery('#form').submit();
        }
    });
    jQuery('#button-search').click(function () {
        var srch = jQuery('input[name=\'search\']').attr('value');
        window.location = 'index.php?page=sitereviews&search=' + srch;
        return false;
    });
    jQuery('input[name=\'search\']').on('keydown', function (e) {
        if (e.keyCode == 13) {
            var srch = jQuery(this).attr('value');
            window.location = 'index.php?page=sitereviews&search=' + srch;
            return false;
        }
    });
</script>