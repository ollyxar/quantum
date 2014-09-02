<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['link'] ?>"><?php echo $breadcrumb['caption'] ?></a> <span
                class="divider">/</span></li>
    <?php } ?>
    <li class="active"><?php echo $breadcrumb_cur ?></li>
</ul>
<form method="post" id="form">
    <div class="hero-unit clearfix">
        <div class="pull-left">
            <h2><?php echo $module['description'] ?></h2>
        </div>
        <div class="pull-right">
            <div class="margin-top-button">
                <a id="save" class="btn btn-success"><i
                        class="icon-ok icon-white"></i> <?php echo $language['save'] ?></a>
                <a href="index.php?page=modules" class="btn btn-inverse"><i
                        class="icon-remove icon-white"></i> <?php echo $language['cancel'] ?></a>
            </div>
        </div>
    </div>
    <?php if (isset($text_message)) { ?>
        <div class="alert text-center alert-<?php echo $class_message ?>">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <?php echo $text_message ?>
        </div>
    <?php } ?>
    <div class="row-fluid">
        <div class="span4">
            <label class="control-label"><?php echo $language['description'] ?></label>

            <input type="text" class="span12" name="description"
                   value="<?php echo $module['description'] ?>"/>
        </div>
        <div class="span4">
            <label class="control-label"><?php echo $language['position'] ?></label>

            <input type="text" class="span12" name="position"
                   value="<?php echo $module['position'] ?>"/>
        </div>
        <div class="span4">
            <label class="control-label"><?php echo $language['router'] ?></label>

            <input type="text" class="span12" name="route"
                   value="<?php echo $module['route'] ?>"/>
        </div>
    </div>
    <hr />
    <div class="row-fluid">
        <div class="span6">
            <label class="control-label"><?php echo $language['permission_read'] ?></label>

            <select name="rr" class="span12">
                <?php foreach ($rights as $id => $right) { ?>
                    <option value="<?php echo $id ?>" <?php if ($module['rr'] == $id) echo 'selected="selected"'
                    ?>><?php echo $right ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="span6">
            <label class="control-label"><?php echo $language['permission_mod'] ?></label>

            <select name="rw" class="span12">
                <?php foreach ($rights as $id => $right) { ?>
                    <option value="<?php echo $id ?>" <?php if ($module['rw'] == $id) echo 'selected="selected"'
                    ?>><?php echo $right ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</form>
<script type="text/javascript">
    jQuery('#save').click(function () {
        jQuery('#form').submit();
    });
</script>