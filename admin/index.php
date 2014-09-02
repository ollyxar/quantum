<?php

// error reporting
error_reporting(E_ALL);
ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');

// testing backend
global $start_time;
$start_time = microtime(true);

$_GET['cms'] = 'quantum';
session_name("QUANTUM_ADM");
session_start();

// including frame of QFinder
if (isset($_GET['auth']) && isset($_SESSION['is_adm']) && $_SESSION['is_adm'] == true) {
    $_GET['lng'] = $_SESSION['lang'];
} elseif (isset($_GET['auth'])) {
    // do nothing - security fix for QFinder
} else {
    include_once('language/lang_description.php');
    require_once('system/language_loader.php');
    require_once('../config.php');

    require_once('../system/library/engine.php');
    require_once('../system/library/config.php');
    require_once('../system/library/db.php');
    require_once('../system/library/module.php');
    require_once('../system/library/installer.php');
    require_once('../system/library/document.php');
    require_once('../system/library/url.php');
    require_once('../system/library/user.php');
    require_once('../system/library/cache.php');
    require_once('../system/library/image.php');
    require_once('../system/functions.php');
    require_once('system/library/unit.php');

    magicQuotesFix();

    $engine = new QOllyxar(true);
    $engine->cache->path_to_system = dirname(getcwd()) . '/';
    $engine->loadConfig(dirname(getcwd()));

    // get modules
    $modules = $engine->db->query("SELECT id, name, position, description, rr, rw, has_ui, enabled, ordering FROM " . DB_PREF . "modules")->rows;
    foreach ($modules as $module) {
        include_once('../modules/' . $module['name'] . '.php');
        $QModule = new $module['name']($engine);
        $engine->a_modules[$module['id']] = array(
            'id'            => $module['id'],
            'name'          => $module['name'],
            'position'      => $module['position'],
            'description'   => $module['description'],
            'rr'            => $module['rr'],
            'rw'            => $module['rw'],
            'has_ui'        => $module['has_ui'],
            'enabled'       => (bool)$module['enabled'],
            'ordering'      => $module['ordering'],
            'version'       => $QModule->getVersion()
        );
        // language manager compatibility
        $engine->modules[$module['name']] = new $module['name']($engine);
        unset($QModule);
    }
    unset($modules);

    $engine->document->addHeaderString('<meta charset="utf-8">');
    $engine->document->addHeaderString('<script src="template/js/jquery-1.8.1.min.js"></script>');
    $engine->document->addHeaderString('<script src="template/js/jquery-ui-1.10.4.custom.min.js"></script>');
    $engine->document->setTitle($_SERVER['HTTP_HOST'] . ' - ' . $language['cms-title']);
    // log in
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST["enter"]) && $_POST['username'] <> '') {
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['lang'] = $_POST['lang'];
        setcookie("lang", $_SESSION['lang'], null, '/');
        $a_name = $engine->db->escape($_POST['username']);
        $a_pass = md5(md5($engine->db->escape($_POST['password'])));
        $q = $engine->db->query("SELECT * FROM " . DB_PREF . "users WHERE name='" . $a_name . "' AND password='" . $a_pass .
            "' AND user_group <= 3 AND enabled=1");
        if (!empty($q->rows)) {
            $_SESSION['is_adm'] = true;
            $_SESSION['adm_name'] = $a_name;
            $_SESSION['access'] = $q->row['user_group'];
            $engine->db->query("UPDATE " . DB_PREF . "users SET `adm_last_login` = '" . strtotime("now") . "' WHERE id="
                . (int)$q->row['id']);
            $engine->url->redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    // logging out
    if (isset($_GET["log"]) && $_GET["log"] == "out") {
        session_destroy();
        $engine->url->redirect(PRTCL . '://' . $engine->host . ADM_PATH);
    }

    // quick password rename
    if (isset($_POST['oldPass'])) {
        $_SESSION['password_changed'] = false;
        $engine->db->query("UPDATE " . DB_PREF . "users SET password='" . md5(md5($engine->db->escape($_POST['newPass']))) .
            "' WHERE name='" . $_SESSION['username'] . "' AND password='" . md5(md5($_POST['oldPass'])) . "'");
        if ($engine->db->countAffected() > 0) {
            $_SESSION['password_changed'] = true;
            session_destroy();
            header("Refresh: 3; URL=" . $engine->url->full);
        }
    }

    if (isset($_SESSION['is_adm']) && $_SESSION['is_adm'] == true) {
        include_once('system/init.php');
    } else {
        include_once('template/log.tpl');
    }

}