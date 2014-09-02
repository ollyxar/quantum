<?php

// finding languages
$adm_languages = array();
$dir = 'language/';
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($lng = readdir($dh)) !== false) {
            if ($lng != '.' && $lng != '..' && is_dir($dir . $lng)) {
                $adm_languages[] = $lng;
            }
        }
        closedir($dh);
    }
} else die('Can\'t find language directory');
if (empty($adm_languages)) die('Can\'t find any language');
asort($adm_languages);

// load language
if (!isset($_SESSION['lang'])) {
    foreach ($adm_languages as $lang) {
        $_SESSION['lang'] = $lang;
        break;
    }
}
if (!file_exists('language/' . $_SESSION['lang'] . '/index.ini')) {
    session_destroy();
    die('Specified language does not exists!');
}
$language = parse_ini_file('language/' . $_SESSION['lang'] . '/index.ini');
$dir = 'language/' . $_SESSION['lang'] . '/';
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != '.' && $file != '..' && $file != 'index.ini') {
                $language = array_merge($language, parse_ini_file($dir . $file));
            }
        }
        closedir($dh);
    }
}