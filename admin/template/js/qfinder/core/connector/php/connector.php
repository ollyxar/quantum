<?php

/**
 * Protect against sending content before all HTTP headers are sent (#186).
 */
ob_start();

/**
 * define required constants
 */
require_once dirname(__FILE__) . '/constants.php';

// @ob_end_clean();
// header("Content-Encoding: none");

/**
 * we need this class in each call
 */
require_once QFINDER_CONNECTOR_LIB_DIR . '/CommandHandler/CommandHandlerBase.php';
/**
 * singleton factory
 */
require_once QFINDER_CONNECTOR_LIB_DIR . '/Core/Factory.php';
/**
 * utils class
 */
require_once QFINDER_CONNECTOR_LIB_DIR . '/Utils/Misc.php';
/**
 * hooks class
 */
require_once QFINDER_CONNECTOR_LIB_DIR . '/Core/Hooks.php';

/**
 * Simple function required by config.php - discover the server side path
 * to the directory relative to the "$baseUrl" attribute
 *
 * @package QFinder
 * @subpackage Connector
 * @param string $baseUrl
 * @return string
 */
function resolveUrl($baseUrl) {
    $fileSystem =& QFinder_Connector_Core_Factory::getInstance("Utils_FileSystem");
    $baseUrl = preg_replace("|^http(s)?://[^/]+|i", "", $baseUrl);
    return $fileSystem->getDocumentRootPath() . $baseUrl;
}

$utilsSecurity =& QFinder_Connector_Core_Factory::getInstance("Utils_Security");
$utilsSecurity->getRidOfMagicQuotes();

/**
 * $config must be initialised
 */
$config = array();
$config['Hooks'] = array();
$config['Plugins'] = array();

/**
 * Fix cookies bug in Flash.
 */
if (!empty($_GET['command']) && $_GET['command'] == 'FileUpload' && !empty($_POST)) {
	foreach ($_POST as $key => $val) {
		if (strpos($key, "qfcookie_") === 0)
			$_COOKIE[str_replace("qfcookie_", "", $key)] = $val;
	}
}

/**
 * read config file
 */
require_once QFINDER_CONNECTOR_CONFIG_FILE_PATH;

QFinder_Connector_Core_Factory::initFactory();
$connector =& QFinder_Connector_Core_Factory::getInstance("Core_Connector");

if(isset($_GET['command'])) {
    $connector->executeCommand($_GET['command']);
}
else {
    $connector->handleInvalidCommand();
}
