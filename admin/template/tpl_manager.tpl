<?php
function fnLink($filename, $path) {
    return substr($filename, strlen($path));
}
?>
<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['link'] ?>"><?php echo $breadcrumb['caption'] ?></a> <span
                class="divider">/</span></li>
    <?php } ?>
    <li class="active"><?php echo $breadcrumb_cur ?></li>
</ul>
<div class="hero-unit clearfix">
    <div class="pull-left"><h2><?php echo $language['template_manager'] ?></h2></div>
    <div class="pull-right">
        <div class="margin-top-button">
            <a id="save" class="btn btn-success">
                <i class="icon-ok icon-white"></i>
                <?php echo $language['save'] ?>
            </a>
            <a onclick="window.location = 'index.php?page=tpl_manager'" class="btn btn-warning">
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
    <div class="span12">
        <div class="span3">
            <ul id="red" class="treeview">
                <?php
                function print_tree($dir, $templates_path, $tpl_file) {
                    foreach (glob($dir . '/*') as $filename) {
                        if (!is_dir($filename)) {
                            ?>
                            <li><span <?php if (($tpl_file != false) && $tpl_file == $filename) echo 'class="active"'
                            ?> onclick="document.location.href='index.php?page=tpl_manager&file=<?php
                                echo fnLink($filename, $templates_path) ?>'"><?php echo basename($filename); ?></span>
                            </li>
                        <?php } else { ?>
                            <li><span><?php echo basename($filename); ?></span>
                                <ul>
                                    <?php print_tree($filename, $templates_path, $tpl_file); ?>
                                </ul>
                            </li>
                        <?php
                        }
                    }
                }

                print_tree($templates_path, $templates_path, $tpl_file);
                ?>
            </ul>
        </div>
        <div class="span9">
            <form id="form" method="post">
                <input type="hidden" id="action" name="action">
                <div style="border: 1px solid #919191; padding: 3px">
                    <?php if ($tpl_file == false) { ?>
                        <h2 style="text-align:center"><?php echo $language['select_file'] ?></h2>
                    <?php } ?>
                    <textarea style="min-height: 600px" name="code" id="code"><?php
                        if (($tpl_file != false) && file_exists($tpl_file)) echo str_replace('</textarea>', '&lt;/textarea&gt;', str_replace('&', '&amp;', file_get_contents($tpl_file))) ?>
                    </textarea>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
        lineNumbers: true,
        matchBrackets: true,
        <?php if (isset($ext) && $ext == 'tpl') { ?>
        mode: "application/x-httpd-php",
        <?php } ?>
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift"
    });
    jQuery(document).ready(function () {
        jQuery("#red").treeview({
            animated: "fast",
            collapsed: false,
            unique: true,
            persist: "cookie"
        });
    });
    jQuery('#save').click(function () {
        jQuery('#action').val('save');
        jQuery('#form').submit();
    });
</script>