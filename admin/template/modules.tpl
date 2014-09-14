<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['link'] ?>"><?php echo $breadcrumb['caption'] ?></a> <span
                class="divider">/</span></li>
    <?php } ?>
    <li class="active"><?php echo $breadcrumb_cur ?></li>
</ul>
<form id="form" method="post" enctype="multipart/form-data">
    <input name="file" id="ms" type="file" style="width: 0; height: 0; float: left;"/>

    <div class="row-fluid">
        <input type="hidden" id="action" name="action"/>

        <div class="hero-unit clearfix">
            <div class="pull-left">
                <h2><?php echo $language['module_management'] ?></h2>
            </div>
            <div class="pull-right">
                <div class="margin-top-button">
                    <a id="install" class="btn btn-primary"><i
                            class="icon-download-alt icon-white"></i> <?php echo $language['install'] ?>
                    </a>
                    <a id="save" class="btn btn-success"><i
                            class="icon-ok icon-white"></i> <?php echo $language['save']; ?></a>
                    <a id="activate" class="btn btn-warning"><i
                            class="icon-ok-circle icon-white"></i> <?php echo $language['activate'] ?></a>
                    <a id="deactivate" class="btn btn-danger"><i
                            class="icon-lock icon-white"></i> <?php echo $language['deactivate'] ?></a>
                    <a id="remove" class="btn btn-inverse"><i
                            class="icon-trash icon-white"></i> <?php echo $language['uninstall'] ?></a>
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
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th style="width: 7%"><input type="checkbox" id="chk"/></th>
                <th style="width: 7%"><?php echo $language['id'] ?></th>
                <th style="width: 55%"><?php echo $language['description'] ?></th>
                <th style="width: 7%"><?php echo $language['text_version'] ?></th>
                <th style="width: 7%"><?php echo $language['enabled'] ?></th>
                <th style="width: 7%"><?php echo $language['ordering'] ?></th>
                <th><?php echo $language['action'] ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($modules as $module) { ?>
                <tr>
                    <td><input type="checkbox" class="ids" name="fp[]" value="<?php echo $module['id'] ?>"/></td>
                    <td><?php echo $module['id'] ?></td>
                    <td><input type="text" name="modules[<?php echo $module['id'] ?>][description]"
                               value="<?php echo $module['description'] ?>"/></td>
                    <td style="text-align: center"><?php echo $engine->modules[$module['name']]->getVersion() ?></td>
                    <td style="text-align: center">
                        <?php if ((int)$module['enabled'] == 1) { ?>
                            <i class="icon-ok-sign"></i>
                        <?php } else { ?>
                            <i class="icon-minus-sign"></i>
                        <?php } ?>
                    </td>
                    <td><input type="text" name="modules[<?php echo $module['id'] ?>][ordering]"
                               value="<?php echo $module['ordering'] ?>"/></td>
                    <td>
                        <?php if ($module['has_ui']) { ?>
                        <a href="index.php?page=<?php echo $module['name'] ?>"
                           class="btn btn-info" style="display: block; margin: 0 auto"><i class="icon-edit icon-white"></i> <?php echo $language['manage'] ?></a>
                        <?php } ?>
                        <a href="index.php?page=modules&id=<?php echo $module['id'] ?>"
                           class="btn btn-warning" style="display: block; margin: 0 auto"><i class="icon-edit icon-white"></i> <?php echo $language['edit'] ?></a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</form>
<div class="pull-right">
    <?php echo printPagination($page_count, ADM_PATH . 'index.php?page=modules&per_page=' . (int)$_GET['per_page'], false, true) ?>
</div>
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
    jQuery('#activate').click(function () {
        jQuery('#action').val('activate');
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
    jQuery('#install').click(function () {
        jQuery('#action').val('install');
        jQuery('#ms').trigger('click');

    });
    jQuery('#ms').change(function () {
        if (jQuery('#ms').attr('value') != '') {
            jQuery('#form').submit();
        }
    })
</script>
