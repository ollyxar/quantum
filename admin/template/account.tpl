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
            <h2><?php echo $language['account'] ?></h2>
        </div>
        <div class="pull-right">
            <div class="margin-top-button">
                <a onclick="window.location = 'index.php?page=account'" class="btn btn-warning">
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
            <div class="control-group">
                <label class="control-label"><?php echo $language['captcha_register'] ?></label>

                <div class="controls">
                    <label>
                        <input type="hidden" name="captcha_required" value="<?php echo $settings['captcha_required'] ?>">
                        <input type="checkbox" class="switcher" <?php if ((int)$settings['captcha_required'] == 1) { ?>
                            checked="checked"<?php } ?>> <?php echo $language['required'] ?>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <ul class="nav nav-tabs">
        <?php $dou = TRUE; ?>
        <?php foreach ($engine->languages as $lang) { ?>
            <li <?php if ($dou) {
                echo 'class="active"';
                $dou = FALSE;
            } ?>>
                <a data-toggle="tab" href="#<?php echo $lang['name'] ?>">
                    <?php echo $lang['description'] ?></a></li>
        <?php } ?>
    </ul>
    <div class="tab-content">
        <?php $dou = TRUE; ?>
        <?php foreach ($engine->languages as $lang) { ?>
            <div class="tab-pane<?php if ($dou) {
                echo ' active';
                $dou = FALSE;
            } ?>" id="<?php echo $lang['name'] ?>">
                <div class="hero-unit">
                    <h4><?php echo $language['captions'] ?></h4>
                    <div class="row-fluid">
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label"><?php echo $language['name'] ?></label>

                                <div class="controls">
                                    <input type="text" class="span12" name="placeholder_name_<?php echo $lang['name'] ?>"
                                           value="<?php echo $settings['placeholder_name_' . $lang['name']] ?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label"><?php echo $language['email'] ?></label>

                                <div class="controls">
                                    <input type="text" class="span12" name="placeholder_email_<?php echo $lang['name'] ?>"
                                           value="<?php echo $settings['placeholder_email_' . $lang['name']] ?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label"><?php echo $language['password'] ?></label>

                                <div class="controls">
                                    <input type="text" class="span12" name="placeholder_password_<?php echo $lang['name'] ?>"
                                           value="<?php echo $settings['placeholder_password_' . $lang['name']] ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo $language['old_pass'] ?></label>

                                <div class="controls">
                                    <input type="text" class="span12" name="old_pass_<?php echo $lang['name'] ?>"
                                           value="<?php echo $settings['old_pass_' . $lang['name']] ?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo $language['new_pass'] ?></label>

                                <div class="controls">
                                    <input type="text" class="span12" name="new_pass_<?php echo $lang['name'] ?>"
                                           value="<?php echo $settings['new_pass_' . $lang['name']] ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="hero-unit">
                    <h4><?php echo $language['titles'] ?></h4>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo $language['registration_page'] ?></label>
                                <div class="controls">
                                    <input type="text" class="span12" name="title_registration_<?php echo $lang['name'] ?>"
                                           value="<?php echo $settings['title_registration_' . $lang['name']] ?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo $language['account_page'] ?></label>
                                <div class="controls">
                                    <input type="text" class="span12" name="title_account_<?php echo $lang['name'] ?>"
                                           value="<?php echo $settings['title_raccount_' . $lang['name']] ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo $language['title'] ?></label>

                                <div class="controls">
                                    <input type="text" class="span12" name="title_<?php echo $lang['name'] ?>"
                                           value="<?php echo $settings['title_' . $lang['name']] ?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo $language['btn_send'] ?></label>

                                <div class="controls">
                                    <input type="text" class="span12"
                                           name="send_<?php echo $lang['name'] ?>"
                                           value="<?php echo $settings['send_' . $lang['name']] ?>"/>
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
                                  style="width:99%; min-height: 100px"><?php echo $settings['descr_' . $lang['name']] ?></textarea>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="hero-unit">
                    <h4><?php echo $language['buttons'] ?></h4>

                    <div class="row-fluid">
                        <div class="span12">
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo $language['log_in'] ?></label>

                                    <div class="controls">
                                        <input type="text" class="span12" name="sent_<?php echo $lang['name'] ?>"
                                               value="<?php echo $settings['sent_' . $lang['name']] ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo $language['log_out'] ?></label>

                                    <div class="controls">
                                        <input type="text" class="span12"
                                               name="empty_vars_<?php echo $lang['name'] ?>"
                                               value="<?php echo $settings['empty_vars_' . $lang['name']] ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label"><?php echo $language['message_fail'] ?></label>

                                    <div class="controls">
                                        <input type="text" class="span12"
                                               name="message_fail_<?php echo $lang['name'] ?>"
                                               value="<?php echo $settings['message_fail_' . $lang['name']] ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</form>
<script type="text/javascript">
    jQuery('#save').click(function () {
        jQuery('#action').val('save');
        jQuery('#form').submit();
    });
    jQuery('.switcher').change(function () {
        if ((jQuery(this).attr('checked') != undefined) && (jQuery(this).attr('checked') == 'checked')) {
            jQuery(this).parent().find('input:hidden').attr('value', '1');
        } else {
            jQuery(this).parent().find('input:hidden').attr('value', '0');
        }
    })
</script>