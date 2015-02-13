<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang'] ?>">
<head>
    <link rel="shortcut icon" href="favicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $engine->document->addStyle('template/css/bootstrap.css') ?>
    <?php $engine->document->addStyle('template/css/bootstrap-responsive.css') ?>
    <?php $engine->document->addStyle('template/css/font-awesome.css') ?>
    <?php $engine->document->addStyle('template/css/jquery-ui-1.10.4.custom.css') ?>
    <!--[if IE 7]>
    <?php $engine->document->addStyle('template/css/font-awesome-ie7.css') ?>
	<![endif]-->
    <?php $engine->document->addScript('template/js/bootstrap.js') ?>
    <?php echo $engine->document->render() ?>
</head>
<body>
<div class="container-fluid">
    <div class="navbar navbar-inverse">
        <div class="navbar-inner">
            <button type="button" class="btn btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <div class="container">
                <a class="brand" href="index.php"><?php echo $language['cms-title'] ?></a>

                <div class="nav-collapse">
                    <ul class="nav">
                        <?php if ($_SESSION['access'] <= 2) { ?>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown"><?php echo $language['system'] ?>
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="index.php?page=control"><?php echo $language['control_panel'] ?></a>
                                </li>
                                <li>
                                    <a href="index.php?page=tpl_manager"><?php echo $language['template_manager'] ?></a>
                                </li>
                                <li>
                                    <a href="index.php?page=lang_manager"><?php
                                        echo $language['language_manager'] ?></a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown"><?php echo $language['users'] ?>
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-submenu">
                                    <a tabindex="-1"
                                       href="index.php?page=users"><?php echo $language['user_management']
                                        ?></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="index.php?page=users&view=tiny&id=new"><?php echo $language['create_user'] ?></a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <?php } ?>
                        <li class="dropdown">
                            <a class="dropdown-toggle"
                               data-toggle="dropdown"><?php echo $language['modules'] ?>
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <?php foreach ($engine->modules as $module) { ?>
                                    <?php if ($_SESSION['access'] <= $module->adm_access['rr'] && $module->has_ui && $module->enabled) { ?>
                                        <li><a href="<?php echo 'index.php?page=' . strtolower(get_class($module)) ?>">
                                                <?php echo $module->description ?></a></li>
                                    <?php } ?>
                                <?php } ?>
                                <?php if ($_SESSION['access'] <= 2) { ?>
                                    <li class="divider"></li>
                                    <li><a href="index.php?page=modules">
                                            <?php echo $language['module_management'] ?></a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <li>
                            <a href="index.php?fm"><?php echo $language['file_manager'] ?></a>
                        </li>
                    </ul>
                    <ul class="nav pull-right">
                        <li>
                            <a href="/" target="_blank"><i
                                    class="icon-share-alt icon-white"></i> <?php echo $language['go_to_site'] ?></a>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown">
                                <i class="icon-user icon-white"></i> <?php echo $_SESSION['adm_name'] ?>
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="index.php?log=out"><i class="icon-off"></i> <?php echo
                                        $language['log_out'] ?></a></li>
                                <li><a data-toggle="modal" href="#change-pass"><i class="icon-key"></i> <?php echo
                                        $language['change_pass'] ?></a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php if (isset($_SESSION['password_changed'])) { ?>
    <?php if ($_SESSION['password_changed']) { ?>
        <div class="alert text-center alert-success">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <?php echo $language['password_changed'] ?>
        </div>
    <?php } else { ?>
        <div class="alert text-center alert-error">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <?php echo $language['old_pass_incorrect'] ?>
        </div>
    <?php } ?>

<?php } ?>