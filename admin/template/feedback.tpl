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
        <h2><?php echo $language['feedback'] ?></h2>
    </div>
    <div class="pull-right">
        <div class="margin-top-button">
            <a onclick="window.location = 'index.php?page=feedback'" class="btn btn-warning">
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
    <div class="span6">
        <div class="control-group">
            <label class="control-label"><?php echo $language['alias'] ?></label>

            <div class="controls">
                <input type="text" class="span12" name="alias"
                       value="<?php echo $alias ?>"/>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Captcha</label>

            <div class="controls">
                <input type="hidden" name="captcha_required" value="<?php echo $settings['captcha_required'] ?>">
                <label>
                    <input type="checkbox" class="switcher" <?php if ((int)$settings['captcha_required'] == 1) { ?>
                        checked="checked"<?php } ?>> <?php echo $language['required'] ?>
                </label>
            </div>
        </div>
    </div>
</div>
<div class="hero-unit">
    <h4><?php echo $language['inputs'] ?></h4>

    <div class="row-fluid">
        <div class="span12">
            <div class="span3">
                <h5><?php echo $language['email'] ?></h5>

                <div class="pull-right">
                    <input type="hidden" name="email_required" value="<?php echo $settings['email_required'] ?>">
                    <label>
                        <input type="checkbox" class="switcher" <?php if ((int)$settings['email_required'] == 1) { ?>
                            checked="checked"<?php } ?>> <?php echo $language['required'] ?>
                    </label>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo $language['placeholder'] ?></label>

                    <div class="controls">
                        <?php foreach ($engine->languages as $lang) { ?>
                            <img class="pull-left" style="margin-top: 10px" src="<?php echo $lang['picture'] ?>" alt="flag"/>
                            <input type="text" class="span11 pull-right"
                                   name="email_placeholder_<?php echo $lang['name'] ?>"
                                   value="<?php echo $settings['email_placeholder_' . $lang['name']] ?>"/>
                                <div class="clearfix"></div>

                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="span3">
                <h5><?php echo $language['name'] ?></h5>

                <div class="pull-right">
                    <input type="hidden" name="name_required" value="<?php echo $settings['name_required'] ?>">
                    <label>
                        <input type="checkbox" class="switcher" <?php if ((int)$settings['name_required'] == 1) { ?>
                            checked="checked"<?php } ?>> <?php echo $language['required'] ?>
                    </label>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo $language['placeholder'] ?></label>

                    <div class="controls">
                        <?php foreach ($engine->languages as $lang) { ?>
                            <img class="pull-left" style="margin-top: 10px" src="<?php echo $lang['picture'] ?>" alt="flag"/>
                            <input type="text" class="span11 pull-right"
                                   name="name_placeholder_<?php echo $lang['name'] ?>"
                                   value="<?php echo $settings['name_placeholder_' . $lang['name']] ?>"/>
                            <div class="clearfix"></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="span3">
                <h5><?php echo $language['phone'] ?></h5>

                <div class="pull-right">
                    <input type="hidden" name="phone_required" value="<?php echo $settings['phone_required'] ?>">
                    <label>
                        <input type="checkbox" class="switcher" <?php if ((int)$settings['phone_required'] == 1) { ?>
                            checked="checked"<?php } ?>> <?php echo $language['required'] ?>
                    </label>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo $language['placeholder'] ?></label>

                    <div class="controls">
                        <?php foreach ($engine->languages as $lang) { ?>
                            <img class="pull-left" style="margin-top: 10px" src="<?php echo $lang['picture'] ?>" alt="flag"/>
                            <input type="text" class="span11 pull-right"
                                   name="phone_placeholder_<?php echo $lang['name'] ?>"
                                   value="<?php echo $settings['phone_placeholder_' . $lang['name']] ?>"/>
                            <div class="clearfix"></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="span3">
                <h5><?php echo $language['message'] ?></h5>

                <div class="pull-right">
                    <input type="hidden" name="message_required" value="<?php echo $settings['message_required'] ?>">
                    <label>
                        <input type="checkbox" class="switcher" <?php if ((int)$settings['message_required'] == 1) { ?>
                            checked="checked"<?php } ?>> <?php echo $language['required'] ?>
                    </label>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo $language['placeholder'] ?></label>

                    <div class="controls">
                        <?php foreach ($engine->languages as $lang) { ?>
                            <img class="pull-left" style="margin-top: 10px" src="<?php echo $lang['picture'] ?>" alt="flag"/>
                            <input type="text" class="span11 pull-right"
                                   name="message_placeholder_<?php echo $lang['name'] ?>"
                                   value="<?php echo $settings['message_placeholder_' . $lang['name']] ?>"/>
                            <div class="clearfix"></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<ul class="nav nav-tabs">
    <?php foreach ($engine->languages as $lang) { ?>
        <li><a data-toggle="tab" href="#<?php echo $lang['name'] ?>"><?php echo $lang['description'] ?></a></li>
    <?php } ?>
</ul>
<div class="tab-content">
    <?php foreach ($engine->languages as $lang) { ?>
        <div class="tab-pane" id="<?php echo $lang['name'] ?>">
            <div class="row-fluid">
                <div class="span12">
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label"><?php echo $language['caption'] ?></label>

                            <div class="controls">
                                <input type="text" class="span12" name="caption_<?php echo $lang['name'] ?>"
                                       value="<?php echo $settings['caption_' . $lang['name']] ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
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
            </div>
            <div class="clearfix"></div>
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
                <h4><?php echo $language['messages'] ?></h4>

                <div class="row-fluid">
                    <div class="span12">
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label"><?php echo $language['message_sent'] ?></label>

                                <div class="controls">
                                    <input type="text" class="span12" name="sent_<?php echo $lang['name'] ?>"
                                           value="<?php echo $settings['sent_' . $lang['name']] ?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label"><?php echo $language['empty_vars'] ?></label>

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
            <div class="clearfix"></div>
            <textarea name="info_<?php echo $lang['name'] ?>" class="ckeditor"
                      style="width:100%;min-height: 300px"><?php
                echo $settings['info_' . $lang['name']] ?></textarea>
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
            jQuery(this).parent().parent().children().attr('value', '1');
        } else {
            jQuery(this).parent().parent().children().attr('value', '0');
        }
    })
</script>
