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
            <h2><?php echo $language['photos'] ?></h2>
        </div>
        <div class="pull-right">
            <div class="margin-top-button">
                <a onclick="window.location = 'index.php?page=photos'" class="btn btn-warning">
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
    <div class="control-group">
        <label class="control-label"><?php echo $language['alias'] ?></label>

        <div class="controls">
            <input type="text" class="span12" name="alias"
                   value="<?php echo $alias ?>"/>
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
                <div class="control-group">
                    <label class="control-label"><?php echo $language['descr'] ?></label>

                    <div class="controls">
                        <textarea name="descr_<?php echo $lang['name'] ?>"
                                  style="width:99%; min-height: 100px"><?php echo $settings['descr_' . $lang['name']] ?></textarea>
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
</script>
