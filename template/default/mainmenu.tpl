<?php if (isset($menu_box)) { ?>
    <div class="navbar navbar-default">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">+</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <?php
                    function printMenu($menu_box, $subsub = false) {
                        foreach ($menu_box as $menu_item) {
                            ?>
                            <li class="<?php if ($menu_item['active'] == true) echo 'active ';
                            if ($subsub && !empty($menu_item['subs'])) echo 'dropdown-submenu ' ?>">
                                <a href="<?php echo $menu_item['url']; ?>"
                                   <?php if (!empty($menu_item['subs']) && !$subsub) {
                                   ?>class="dropdown-toggle" data-toggle="dropdown" <?php
                                } ?>><?php echo $menu_item['caption']; if (!empty($menu_item['subs']) && !$subsub) {?><span class="caret"></span><?php } ?></a>
                                <?php if (!empty($menu_item['subs'])) { ?>
                                    <ul class="dropdown-menu">
                                        <?php printMenu($menu_item['subs'], true) ?>
                                    </ul>
                                <?php } ?>
                            </li>
                        <?php
                        }
                    }

                    printMenu($menu_box);
                    ?>
                </ul>
            </div>

    </div>
<?php } ?>