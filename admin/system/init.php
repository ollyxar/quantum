<?php
if (!isset($engine)) exit();

/*
$ch = curl_init('http://licensing.ollyxar.com/');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'action=license_check&lic_number=AAAA-AAAA-AAAA-AAAA-AAAA&site=mysite.com');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$res = curl_exec($ch);
curl_close($ch);

$alu_start = create_function('', $res);
$alu_start();
*/

if (isset($_GET['fm'])) {
    include_once('template/header.tpl');
    include_once('template/js/qfinder/starter.php');
    include_once('template/footer.tpl');
    exit();
}

if (!isset($_GET['page']) || !file_exists('units/' . $_GET['page'] . '.php')) {
    $_GET['page'] = 'info';
}
include_once('units/' . $_GET['page'] . '.php');
try {
    $module_name = '\\A\\'. $_GET['page'];
    $AUnit = new $module_name($engine, $language);
    $AUnit->index();
    include_once('template/header.tpl');
    $AUnit->output();
    include_once('template/footer.tpl');
} catch (Exception $e) {
    $e->getMessage();
}