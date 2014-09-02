<?php
// testing engine
global $start_time;
$start_time = microtime(true);
// including main configuration
require_once('config.php');

// including library
require_once('system/library/engine.php');
require_once('system/library/db.php');
require_once('system/library/module.php');
require_once('system/library/document.php');
require_once('system/library/url.php');
require_once('system/library/user.php');
require_once('system/library/cache.php');
require_once('system/library/captcha.php');
require_once('system/library/image.php');

// including core functions
require_once('system/functions.php');

// initialization engine
require_once('system/init.php');
