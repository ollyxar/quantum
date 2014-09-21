<?php if (isset($lang_box)) { ?>
    <div class="pull-right">
        <?php if (SEO_MULTILANG) { ?>
            <?php foreach ($lang_box as $lang_i) { ?>
                <a href="<?php echo $lang_i['link'] ?>"><?php echo $lang_i['description'] ?></a>
            <?php } ?>
        <?php } else { ?>
            <?php foreach ($lang_box as $lang_i => $lang_val) { ?>
                <form method="post">
                    <input type="hidden" name="lang_name" value="<?php echo $lang_i ?>"/>
                    <input type="submit" name="lang_post" value="<?php echo $lang_val['description'] ?>"/>
                </form>
            <?php } ?>
        <?php } ?>
    </div>
<?php } ?>

