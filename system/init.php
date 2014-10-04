<?php

error_reporting(E_ALL);
ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');

if (version_compare(phpversion(), '5.3.0', '<') == true) {
	exit('PHP5.3+ Required');
}

if (!ini_get('date.timezone')) {
	date_default_timezone_set('UTC');
}

// start session
session_name(SITE_CODE);
if (isset($_POST['remember_me'])) {
	session_set_cookie_params(1209600);
	session_start();
	setcookie(session_name(SITE_CODE), session_id(), time() + 1209600, '/');
} else {
    session_start();
}
if (!isset($_SESSION['lang'])) {
	$_SESSION['lang'] = DEF_LANG;
}

magicQuotesFix();

if (USE_COMPRESSION) {
    ob_start("compress");
}

$engine = new QOllyxar();
$engine->start();

// including template
require_once(TEMPLATE . 'template/common/template.tpl');

if (USE_COMPRESSION) {
    ob_end_flush();
}
