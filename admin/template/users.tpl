<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['link'] ?>"><?php echo $breadcrumb['caption'] ?></a> <span
                class="divider">/</span></li>
    <?php } ?>
    <li class="active"><?php echo $breadcrumb_cur ?></li>
</ul>
<div class="row-fluid">
    <form id="form" method="post">
        <input type="hidden" id="action" name="action"/>

        <div class="hero-unit clearfix">
            <div class="pull-left">
                <h2><?php echo $language['users'] ?></h2>
            </div>
            <div class="pull-right">
                <div class="margin-top-button">
                    <a href="index.php?page=users&view=tiny&id=new"
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
                <button class="btn" type="button" onclick="window.location = 'index.php?page=users'"><?php echo $language['button_clear'] ?></button>
            </div>
        </div>
        <table class="table table-bordered table-striped table-responsive">
            <thead>
            <tr>
                <th><input type="checkbox" id="chk"/></th>
                <th><?php echo $language['id'] ?></th>
                <th><?php echo $language['photo'] ?></th>
                <th><?php echo $language['name'] ?></th>
                <th><?php echo $language['description'] ?></th>
                <th><?php echo $language['email'] ?></th>
                <th><?php echo $language['joined'] ?></th>
                <th><?php echo $language['enabled'] ?></th>
                <th><?php echo $language['group'] ?></th>
                <th><?php echo $language['action'] ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $id => $user) { ?>
                <tr>
                    <td><input type="checkbox" class="ids" name="fp[]" value="<?php echo $id ?>"/></td>
                    <td><?php echo $id ?></td>
                    <td>
                        <img src="<?php echo $user['photo'] ?>" alt="photo"/>
                    </td>
                    <td><?php echo $user['name'] ?></td>
                    <td><?php echo $user['description'] ?></td>
                    <td><?php echo $user['email'] ?></td>
                    <td><?php echo $user['joined'] ?></td>
                    <td style="text-align: center">
                        <?php if ($user['enabled']) { ?>
                            <i class="icon-ok-sign"></i>
                        <?php } else { ?>
                            <i class="icon-minus-sign"></i>
                        <?php } ?>
                    </td>
                    <td><?php echo $user['user_group'] ?></td>
                    <td>
                        <a href="index.php?page=users&view=tiny&id=<?php echo $id ?>"
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
    <?php echo printPagination($page_count, 'index.php?page=users' . $s . '&per_page=' . (int)$_GET['per_page'], false, true) ?>
</div>
<script type="text/javascript">
    $('#chk').change(function () {
        if ($(this).is(':checked')) {
            $('.ids').attr('checked', 'checked');
        } else {
            $('.ids').removeAttr('checked');
        }
    });
    $('#activate').click(function () {
        $('#action').val('activate');
        $('#form').submit();
    });
    $('#deactivate').click(function () {
        $('#action').val('deactivate');
        $('#form').submit();
    });
    $('#remove').click(function () {
        if (confirm("<?php echo $language['confirm_delete'] ?>") == true) {
            $('#action').val('remove');
            $('#form').submit();
        }
    });
    $('#button-search').click(function () {
        var search = $('input[name=\'search\']').attr('value');
        window.location = 'index.php?page=users&search=' + search;
        return false;
    });
    $('input[name=\'search\']').on('keydown', function (e) {
        if (e.keyCode == 13) {
            var search = $(this).attr('value');
            window.location = 'index.php?page=users&search=' + search;
            return false;
        }
    });
</script>