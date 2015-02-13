<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['link'] ?>"><?php echo $breadcrumb['caption'] ?></a> <span
                class="divider">/</span></li>
    <?php } ?>
    <li class="active"><?php echo $breadcrumb_cur ?></li>
</ul>
<form id="form" method="post">
<input type="hidden" id="action" name="action">

<div class="hero-unit clearfix">
    <div class="pull-left"><h2><?php echo $language['control_panel'] ?></h2></div>
    <div class="pull-right">
        <div class="margin-top-button">
            <a id="create_db_backup" class="btn btn-primary">
                <i class="icon-book icon-white"></i>
                <?php echo $language['create_db_backup'] ?>
            </a>
            <a id="create_backup" class="btn btn-primary">
                <i class="icon-archive icon-white"></i>
                <?php echo $language['create_backup'] ?>
            </a>
            <a id="save" class="btn btn-success">
                <i class="icon-ok icon-white"></i>
                <?php echo $language['save'] ?>
            </a>
            <a onclick="window.location = 'index.php?page=control'" class="btn btn-warning">
                <i class="icon-ban-circle icon-white"></i>
                <?php echo $language['cancel'] ?>
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
<div class="row-fluid">
<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab"
                          href="#system_settings"><?php echo $language['system_settings'] ?></a></li>
    <li><a data-toggle="tab" href="#site_settings"><?php echo $language['site_settings'] ?></a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="system_settings">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th style="width: 50%"><?php echo $language['name'] ?></th>
                <th><?php echo $language['value'] ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <?php echo $language['site_code'] ?>
                </td>
                <td>
                    <input type="text" name="site_code" value="<?php echo SITE_CODE ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $language['curl'] ?><span data-tooltip="<?php echo $language['clean_url'] ?>" class="icon-info-sign"></span>
                </td>
                <td>
                    <input id="curl" type="hidden" name="curl"
                           value="<?php if (CLEAN_URL) echo '1'; else echo '0' ?>">

                    <div id="callback-toggle-button">
                        <input type="checkbox"<?php if (CLEAN_URL) echo ' checked="checked"'; else echo '' ?>>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $language['use_compression'] ?><span data-tooltip="<?php echo $language['compression'] ?>" class="icon-info-sign"></span>
                </td>
                <td>
                    <input id="uc" type="hidden" name="use_compression"
                           value="<?php if (USE_COMPRESSION) echo '1'; else echo '0' ?>">

                    <div id="callback-toggle-uc">
                        <input type="checkbox"<?php if (USE_COMPRESSION) echo ' checked="checked"'; else echo '' ?>>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $language['use404r'] ?>
                </td>
                <td>
                    <input id="use404r" type="hidden" name="use404r"
                           value="<?php if (USE_404_REDIRECT) echo '1'; else echo '0' ?>">

                    <div id="callback-toggle-button-4">
                        <input type="checkbox"<?php if (USE_404_REDIRECT) echo ' checked="checked"'; else echo '' ?>>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $language['def_lang'] ?>
                </td>
                <td>
                    <select name="def_lang">
                        <?php foreach ($engine->languages as $lang) { ?>
                            <option
                                <?php if ($lang['name'] == DEF_LANG) echo 'selected="selected"' ?> value="<?php echo $lang['name'] ?>"><?php echo $lang['description'] ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $language['multilang'] ?>
                </td>
                <td>
                    <input id="multilang" type="hidden" name="multilang"
                           value="<?php if (MULTILANG) echo '1'; else echo '0' ?>">

                    <div id="callback-toggle-button-ml">
                        <input type="checkbox"<?php if (MULTILANG) echo ' checked="checked"'; else echo '' ?>>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $language['seomultilang'] ?><span data-tooltip="<?php echo $language['seolang'] ?>" class="icon-info-sign"></span>
                </td>
                <td>
                    <input id="seomultilang" type="hidden" name="seomultilang"
                           value="<?php if (SEO_MULTILANG) echo '1'; else echo '0' ?>">

                    <div id="callback-toggle-button-sml">
                        <input
                            type="checkbox"<?php if (SEO_MULTILANG) echo ' checked="checked"'; else echo '' ?>>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $language['page_suffix'] ?>
                </td>
                <td>
                    <input type="text" name="page_suffix" value="<?php echo PAGE_SUFFIX ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $language['template'] ?>
                </td>
                <td>
                    <input type="text" name="template" value="<?php echo TEMPLATE ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $language['email'] ?>
                </td>
                <td>
                    <input type="text" name="email" value="<?php echo EMAIL ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $language['db_driver'] ?>
                </td>
                <td>
                    <input type="text" name="db_driver" value="<?php echo DB_DRIVER ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $language['db_host'] ?>
                </td>
                <td>
                    <input type="text" name="db_host" value="<?php echo DB_HOST ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $language['db_name'] ?>
                </td>
                <td>
                    <input type="text" name="db_name" value="<?php echo DB_NAME ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $language['db_user'] ?>
                </td>
                <td>
                    <input type="text" name="db_user" value="<?php echo DB_USER ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $language['db_pref'] ?>
                </td>
                <td>
                    <input type="text" name="db_pref" value="<?php echo DB_PREF ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $language['db_pass'] ?>
                </td>
                <td>
                    <input type="text" name="db_pass" value="<?php echo DB_PASS ?>">
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="tab-pane" id="site_settings">
        <ul class="nav nav-tabs">
            <?php foreach ($engine->languages as $lang) { ?>
                <li><a data-toggle="tab" href="#<?php echo $lang['name'] ?>"><?php echo $lang['description'] ?></a></li>
            <?php } ?>
        </ul>
        <div class="tab-content">
            <?php foreach ($engine->languages as $lang) { ?>
                <div class="tab-pane" id="<?php echo $lang['name'] ?>">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td><?php echo $language['site_name'] ?></td>
                            <td><input type="text" name="settings[site_name_<?php echo $lang['name']
                                ?>]" value="<?php echo $settings['site_name_' . $lang['name']] ?>"></td>
                        </tr>
                        <tr>
                            <td><?php echo $language['site_description'] ?></td>
                            <td><input type="text" name="settings[site_descr_<?php echo $lang['name']
                                ?>]" value="<?php echo $settings['site_descr_' . $lang['name']] ?>"></td>
                        </tr>
                        <tr>
                            <td><?php echo $language['kw'] ?></td>
                            <td><input type="text" name="settings[site_kw_<?php echo $lang['name']
                                ?>]" value="<?php echo $settings['site_kw_' . $lang['name']] ?>"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
</div>
</form>
<script type="text/javascript">
    $('#callback-toggle-button').toggleButtons({
        onChange: function ($el, status, e) {
            if (status == true) {
                $('#curl').val('1');
            } else {
                $('#curl').val('0');
            }
        }
    });
    $('#callback-toggle-uc').toggleButtons({
        onChange: function ($el, status, e) {
            if (status == true) {
                $('#uc').val('1');
            } else {
                $('#uc').val('0');
            }
        }
    });
    $('#callback-toggle-button-4').toggleButtons({
        onChange: function ($el, status, e) {
            if (status == true) {
                $('#use404r').val('1');
            } else {
                $('#use404r').val('0');
            }
        }
    });
    $('#callback-toggle-button-ml').toggleButtons({
        onChange: function ($el, status, e) {
            if (status == true) {
                $('#multilang').val('1');
            } else {
                $('#multilang').val('0');
            }
        }
    });
    $('#callback-toggle-button-sml').toggleButtons({
        onChange: function ($el, status, e) {
            if (status == true) {
                $('#seomultilang').val('1');
            } else {
                $('#seomultilang').val('0');
            }
        }
    });
    $('#save').click(function () {
        $('#action').val('save');
        $('#form').submit();
    });
    $('#create_backup').click(function () {
        $('#action').val('backup');
        $('#form').submit();
    });
    $('#create_db_backup').click(function () {
        $('#action').val('backup_db');
        $('#form').submit();
    });
</script>