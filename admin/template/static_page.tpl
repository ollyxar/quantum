<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['link'] ?>"><?php echo $breadcrumb['caption'] ?></a> <span
                class="divider">/</span></li>
    <?php } ?>
    <li class="active"><?php echo $breadcrumb_cur ?></li>
</ul>
<form method="post" id="form">
    <input type="hidden" name="action"/>

    <div class="hero-unit clearfix">
        <div class="pull-left">
            <h2><?php echo $language['page_editor'] ?></h2>
        </div>
        <div class="pull-right">
            <div class="margin-top-button">
                <a id="apply" class="btn btn-info"><i
                        class="icon-ok icon-white"></i> <?php echo $language['apply'] ?></a>
                <a id="save" class="btn btn-success"><i
                        class="icon-ok icon-white"></i> <?php echo $language['save'] ?></a>
                <a href="index.php?page=staticpages" class="btn btn-inverse"><i
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
    <div class="control-group">
        <label class="control-label"><?php echo $language['alias'] ?></label>

        <div class="controls">
            <input type="text" class="span12" name="alias"
                   value="<?php echo $static_page['alias'] ?>"/>
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
                                    <input type="text" class="span12"
                                           name="caption_<?php echo $lang['name'] ?>"
                                           value="<?php echo $static_page['caption_' . $lang['name']] ?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo $language['title'] ?></label>

                                <div class="controls">
                                    <input type="text" class="span12" name="title_<?php echo $lang['name'] ?>"
                                           value="<?php echo $static_page['title_' . $lang['name']] ?>"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo $language['kw'] ?></label>

                                <div class="controls">
                                    <input type="text" class="span12"
                                           name="kw_<?php echo $lang['name'] ?>"
                                           value="<?php echo $static_page['kw_' . $lang['name']] ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="control-group">
                    <label class="control-label"><?php echo $language['descr'] ?></label>

                    <div class="controls">
                        <textarea name="descr_<?php echo $lang['name'] ?>" style="width:99%; min-height: 100px"><?php
                            echo $static_page['descr_' . $lang['name']] ?></textarea>
                    </div>
                </div>
                <textarea name="text_<?php echo $lang['name'] ?>" class="ckeditor"
                          style="width:100%;min-height: 500px"><?php
                    echo str_replace('</textarea>', '&lt;/textarea&gt;', str_replace('&', '&amp;', $static_page['text_' . $lang['name']]))  ?></textarea>
            </div>
        <?php } ?>
    </div>
</form>
<script type="text/javascript">
    $('#save').click(function () {
        $('input:hidden[name=action]').val('save');
        $('#form').submit();
    });
    $('#apply').click(function () {
        $('input:hidden[name=action]').val('apply');
        $('#form').submit();
    })
</script>