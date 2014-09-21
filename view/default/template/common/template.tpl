<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang'] ?>">
<head>
    <meta charset="utf-8"/>
    <meta name="robots" content="index,follow"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?php echo PRTCL ?>://<?php echo $engine->host . ROOT_DIR ?>" />
    <link href="<?php echo TEMPLATE ?>css/main.css" rel="stylesheet" media="screen">
    <link href="<?php echo TEMPLATE ?>css/bootstrap3.css" rel="stylesheet" media="screen">
    <script src="<?php echo TEMPLATE ?>js/jquery-1.11.1.min.js"></script>
    <script src="<?php echo TEMPLATE ?>js/common.js"></script>
    <script src="<?php echo TEMPLATE ?>js/jquery-ui-1.10.4.custom.min.js"></script>
    <script src="<?php echo TEMPLATE ?>js/bootstrap.min.js"></script>
    <?php echo $engine->document->render(); ?>
</head>
<body>

    <div class="container">
        <div class="header clearfix">
            <div class="logo">
                <a href="<?php echo $engine->url->link('route=home') ?>"></a>
            </div>
            <div class="pull-right"><?php $engine->loadModules('top-right') ?></div>
        </div>

        <?php $engine->loadModules('top-menu') ?>
        <?php $engine->loadModules('slider') ?>

        <div class="container-fluid">
            <div class="row">
                <?php $engine->loadModules('content') ?>
                <?php $engine->loadModules('reviews-w') ?>
                <?php $engine->loadModules('footer') ?>
            </div>
        </div>
    </div>
    <?php $end_time = microtime(true) ?>
    <?php $total_time = round($end_time - $start_time, 4) ?>
    <div class="debug">All operations completed in: <?php echo $total_time ?> sec. Total database
        queries: <?php echo $engine->db->db_queries ?>. Memory
        usage: <?php echo round((memory_get_usage(true) / 1024 / 1024), 2) ?>MB
    </div>

</body>
</html>
