<?php
namespace A;

class Control extends \AUnit {

    private function saveData() {
        $this->engine->config->replaceConst('TEMPLATE', $_POST['template']);
        $this->engine->config->replaceConst('SITE_CODE', $_POST['site_code']);
        $this->engine->config->replaceConst('DEF_LANG', $_POST['def_lang']);
        $this->engine->config->replaceConst('PAGE_SUFFIX', $_POST['page_suffix']);
        $this->engine->config->replaceConst('EMAIL', $_POST['email']);
        $this->engine->config->replaceConst('DB_DRIVER', $_POST['db_driver']);
        $this->engine->config->replaceConst('DB_HOST', $_POST['db_host']);
        $this->engine->config->replaceConst('DB_NAME', $_POST['db_name']);
        $this->engine->config->replaceConst('DB_USER', $_POST['db_user']);
        $this->engine->config->replaceConst('DB_PASS', $_POST['db_pass']);
        $this->engine->config->replaceConst('DB_PREF', $_POST['db_pref']);
        $this->engine->config->replaceBool('SEO_MULTILANG', $_POST['seomultilang']);
        $this->engine->config->replaceBool('CLEAN_URL', $_POST['curl']);
        $this->engine->config->replaceBool('USE_COMPRESSION', $_POST['use_compression']);
        $this->engine->config->replaceBool('MULTILANG', $_POST['multilang']);
        $this->engine->config->replaceBool('USE_404_REDIRECT', $_POST['use404r']);
        if ($this->engine->config->saveConfig() === false) {
            $_SESSION['msg'] = 'denied_perm';
        } else {
            $_SESSION['msg'] = 'success';
        }

        $this->engine->url->redirect($this->engine->url->full);
    }

    private function backupF() {
        $b_name = 'backup_' . date("Y-m-d_H-i-s") . '.zip';
        if (zip(dirname(getcwd()), '../upload/files/' . $b_name)) {
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $b_name . '"');
            header('Cache-Control: max-age=0');
            readfile('../upload/files/' . $b_name);
            exit();
        }
    }

    private function backupDB() {
        $b_name = 'backup_' . date("Y-m-d_H-i-s") . '.sql';
        if (mysqlExport(DB_NAME, DB_USER, DB_PASS, DB_HOST, '../upload/files/' . $b_name)) {
            header('Content-Type: application/sql');
            header('Content-Disposition: attachment; filename="' . $b_name . '"');
            header('Cache-Control: max-age=0');
            readfile('../upload/files/' . $b_name);
            exit();
        }
    }


    private function updateSettings() {
        $this->engine->db->query("UPDATE " . DB_PREF . "settings SET data = '" . $this->engine->db->escape(serialize($_POST['settings'])) . "' WHERE id=1");
    }

    public function index() {
        if ($_SESSION['access'] > 2) {
            die('Access denied');
        }
        if (isset($_POST['action']) && $_POST['action'] == 'save') {
            $this->updateSettings();
            $this->saveData();
        }
        if (isset($_POST['action']) && $_POST['action'] == 'backup') {
            $this->backupF();
        }
        if (isset($_POST['action']) && $_POST['action'] == 'backup_db') {
            $this->backupDB();
        }
        $this->data['settings'] = $this->engine->getSetting();
        $this->engine->document->addHeaderString('<link href="template/css/bootstrap-toggle-buttons.css" rel="stylesheet" media="screen">');
        $this->engine->document->addHeaderString('<script src="template/js/jquery.toggle.buttons.js"></script>');
        if (isset($_SESSION['msg']) && $_SESSION['msg'] == 'success') {
            $this->data['text_message'] = $this->language['changes_applied'];
            $this->data['class_message'] = 'success';
            unset($_SESSION['msg']);
        }
        if (isset($_SESSION['msg']) && $_SESSION['msg'] == 'denied_perm') {
            $this->data['text_message'] = $this->language['perm_denied'] . ' ' . $this->language['cur_perm'] .
                $this->engine->config->getPerm();
            $this->data['class_message'] = 'error';
            unset($_SESSION['msg']);
        }

        $this->data['breadcrumbs'][] = array(
            'caption'   => $this->language['home'],
            'link'      => ADM_PATH
        );
        $this->data['breadcrumb_cur'] = $this->language['control_panel'];

        $this->template = 'template/control.tpl';
    }
}