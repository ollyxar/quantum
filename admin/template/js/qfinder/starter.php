<?php
if (!isset($_SESSION['is_adm']) || !$_SESSION['is_adm']) {
    include_once('template/log.tpl');
} else {

    require_once 'core/qfinder_php5.php';
    $QFinder = new QFinder(QFINDER_DEFAULT_BASEPATH, '100%', '700px');
    $QFinder->Create();
}
