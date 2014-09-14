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
    <div class="hero-unit">
        <div class="span6">
            <div class="control-group">
                <label class="control-label"><?php echo $language['captcha_register'] ?></label>

                <div class="controls">
                    <label>
                        <input type="hidden" name="captcha_required"
                               value="<?php echo $settings['captcha_required'] ?>">
                        <input type="checkbox" class="switcher" <?php if ((int)$settings['captcha_required'] == 1) { ?>
                            checked="checked"<?php } ?>> <?php echo $language['required'] ?>
                    </label>
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="control-group">
                <label class="control-label"><?php echo $language['agreement_link'] ?></label>

                <div class="controls">
                    <input type="text" name="agreement" value="<?php echo $settings['agreement'] ?>"/>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
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
    <div class="row-fluid">
        <div class="hero-unit span6">
            <h4><?php echo $language['captions'] ?></h4>
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th><?php echo $language['name'] ?></th>
                    <th><?php echo $language['value'] ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php echo $language['name'] ?></td>
                    <td><input type="text" class="span12" name="placeholder_name_<?php echo $lang['name'] ?>"
                               value="<?php echo $settings['placeholder_name_' . $lang['name']] ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $language['email'] ?></td>
                    <td><input type="text" class="span12" name="placeholder_email_<?php echo $lang['name'] ?>"
                               value="<?php echo $settings['placeholder_email_' . $lang['name']] ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $language['password'] ?></td>
                    <td><input type="text" class="span12" name="placeholder_password_<?php echo $lang['name'] ?>"
                               value="<?php echo $settings['placeholder_password_' . $lang['name']] ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $language['remember_me'] ?></td>
                    <td><input type="text" class="span12" name="remember_me_<?php echo $lang['name'] ?>"
                               value="<?php echo $settings['remember_me_' . $lang['name']] ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $language['old_pass'] ?></td>
                    <td><input type="text" class="span12" name="old_pass_<?php echo $lang['name'] ?>"
                               value="<?php echo $settings['old_pass_' . $lang['name']] ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $language['new_pass'] ?></td>
                    <td><input type="text" class="span12" name="new_pass_<?php echo $lang['name'] ?>"
                               value="<?php echo $settings['new_pass_' . $lang['name']] ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $language['agree_text'] ?></td>
                    <td><input type="text" class="span12" name="agree_<?php echo $lang['name'] ?>"
                               value="<?php echo $settings['agree_' . $lang['name']] ?>"/></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="hero-unit span6">
            <h4><?php echo $language['titles'] ?></h4>
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th><?php echo $language['name'] ?></th>
                    <th><?php echo $language['value'] ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php echo $language['registration_page'] ?></td>
                    <td><input type="text" class="span12" name="title_registration_<?php echo $lang['name'] ?>"
                               value="<?php echo $settings['title_registration_' . $lang['name']] ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $language['account_page'] ?></td>
                    <td><input type="text" class="span12" name="title_account_<?php echo $lang['name'] ?>"
                               value="<?php echo $settings['title_account_' . $lang['name']] ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $language['login_page'] ?></td>
                    <td><input type="text" class="span12" name="title_login_<?php echo $lang['name'] ?>"
                               value="<?php echo $settings['title_login_' . $lang['name']] ?>"/></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row-fluid">
        <div class="hero-unit span6">
            <h4><?php echo $language['buttons'] ?></h4>
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th><?php echo $language['name'] ?></th>
                    <th><?php echo $language['value'] ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php echo $language['log_in'] ?></td>
                    <td><input type="text" class="span12" name="log_in_<?php echo $lang['name'] ?>"
                               value="<?php echo $settings['log_in_' . $lang['name']] ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $language['log_out'] ?></td>
                    <td><input type="text" class="span12"
                               name="log_out_<?php echo $lang['name'] ?>"
                               value="<?php echo $settings['log_out_' . $lang['name']] ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $language['confirm'] ?></td>
                    <td><input type="text" class="span12"
                               name="confirm_<?php echo $lang['name'] ?>"
                               value="<?php echo $settings['confirm_' . $lang['name']] ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $language['save'] ?></td>
                    <td><input type="text" class="span12"
                               name="save_<?php echo $lang['name'] ?>"
                               value="<?php echo $settings['save_' . $lang['name']] ?>"/></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="hero-unit span6">
            <h4><?php echo $language['messages'] ?></h4>
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th><?php echo $language['name'] ?></th>
                    <th><?php echo $language['value'] ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php echo $language['account_exists'] ?></td>
                    <td><input type="text" class="span12" name="account_exists_<?php echo $lang['name'] ?>"
                               value="<?php echo $settings['account_exists_' . $lang['name']] ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $language['not_valid_email'] ?></td>
                    <td><input type="text" class="span12"
                               name="not_valid_email_<?php echo $lang['name'] ?>"
                               value="<?php echo $settings['not_valid_email_' . $lang['name']] ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $language['registration_finished'] ?></td>
                    <td><input type="text" class="span12"
                               name="registration_finished_<?php echo $lang['name'] ?>"
                               value="<?php echo $settings['registration_finished_' . $lang['name']] ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $language['account_confirmed'] ?></td>
                    <td><input type="text" class="span12"
                               name="account_confirmed_<?php echo $lang['name'] ?>"
                               value="<?php echo $settings['account_confirmed_' . $lang['name']] ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $language['not_valid_captcha'] ?></td>
                    <td><input type="text" class="span12"
                               name="not_valid_captcha_<?php echo $lang['name'] ?>"
                               value="<?php echo $settings['not_valid_captcha_' . $lang['name']] ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $language['not_valid_name'] ?></td>
                    <td><input type="text" class="span12"
                               name="not_valid_name_<?php echo $lang['name'] ?>"
                               value="<?php echo $settings['not_valid_name_' . $lang['name']] ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $language['not_valid_password'] ?></td>
                    <td><input type="text" class="span12"
                               name="not_valid_password_<?php echo $lang['name'] ?>"
                               value="<?php echo $settings['not_valid_password_' . $lang['name']] ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo $language['additional_text'] ?></td>
                    <td><textarea class="span12"
                                  name="additional_text_<?php echo $lang['name'] ?>"><?php echo $settings['additional_text_' . $lang['name']] ?></textarea></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    </div>
<?php } ?>
</div>
</form>
<script type="text/javascript">
    jQuery('.hero-unit table').css('display', 'none');
    jQuery('.hero-unit h4').css('cursor', 'pointer').click(function() {
        var table = jQuery(this).parent().find('table');
        if (table.css('display') == 'none') {
            table.slideDown(200);
        } else {
            table.slideUp(200);
        }
    });
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
