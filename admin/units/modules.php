<?php
namespace A;

class Modules extends \AUnit {

    private function saveModules() {
        foreach ($_POST['modules'] as $id => $data) {
            $this->engine->db->query("UPDATE " . DB_PREF . "modules SET `description`='" .
                $this->engine->db->escape($data['description']) . "', `ordering`='" .
                $this->engine->db->escape($data['ordering']) . "' WHERE id=" . (int)$id);
        }
        $_SESSION['msg'] = 'success';
        $_SESSION['msg_text'] = $this->language['changes_applied'];
        $this->engine->url->redirect($this->engine->url->full);
    }

    private function updateModules() {
        foreach ($_POST['fp'] as $id) {
            switch ($_POST['action']) {
                case 'activate':
                    $this->engine->db->query("UPDATE " . DB_PREF . "modules SET `enabled`='1' WHERE id=" . $id);
                    $_SESSION['msg'] = 'success';
                    $_SESSION['msg_text'] = $this->language['changes_applied'];
                    break;
                case 'deactivate':
                    $this->engine->db->query("UPDATE " . DB_PREF . "modules SET `enabled`='0' WHERE id=" . $id);
                    $_SESSION['msg'] = 'success';
                    $_SESSION['msg_text'] = $this->language['changes_applied'];
                    break;
                case 'remove':
                    $installer = new \QInstaller($this->engine, $this->language);
                    $installer->uninstallModule($id);
                    $_SESSION['msg'] = 'success';
                    $_SESSION['msg_text'] = $this->language['changes_applied'];
                    break;
                default:
                    break;
            }
        }
        $this->engine->url->redirect($this->engine->url->full);
    }

    private function updateModule($id) {
        if ($_SESSION['access'] > (int)$_POST['rr']) {
            $_POST['rr'] = $_SESSION['access'];
        }
        if ($_SESSION['access'] > (int)$_POST['rw']) {
            $_POST['rw'] = $_SESSION['access'];
        }
        $this->engine->db->query("UPDATE " . DB_PREF . "modules SET `description`='" .
            $this->engine->db->escape($_POST['description']) . "', `route`='" .
            preg_replace('/\s+/', '', $this->engine->db->escape($_POST['route'])) . "', `position`='" .
            $this->engine->db->escape($_POST['position']) . "', `rr`='" .
            $this->engine->db->escape($_POST['rr']) . "', `rw`='" .
            $this->engine->db->escape($_POST['rw']) . "' WHERE id=" . (int)$id);
        $_SESSION['msg'] = 'success';
        $_SESSION['msg_text'] = $this->language['changes_applied'];
        $this->engine->url->redirect($this->engine->url->full);
    }

    private function installModule() {
        if ($_FILES["file"]["error"] > 0) {
            $_SESSION['msg'] = 'error_install';
            $_SESSION['msg_text'] = $_FILES["file"]["error"];
        } else {
            if (in_array($_FILES["file"]["type"], array('application/zip', 'application/x-zip-compressed'))) {
                $m_name = pathinfo($_FILES["file"]["name"], PATHINFO_FILENAME);
                if (!file_exists('../modules/app_data/' . $m_name)) {
                    mkdir('../modules/app_data/' . $m_name, 0755, true);
                }
                if (move_uploaded_file($_FILES["file"]["tmp_name"], '../modules/app_data/' . $m_name . '/' .
                    $_FILES["file"]['name'])) {
                    $installer = new \QInstaller($this->engine, $this->language);
                    $installer->installModule('../modules/app_data/' . $m_name . '/' . $_FILES["file"]['name'],
                        '../modules/app_data/' . $m_name);
                } else {
                    $_SESSION['msg'] = 'error_install';
                    $_SESSION['msg_text'] = $this->language['cant_move_up_file'];
                }
            } else {
                $_SESSION['msg'] = 'error_install';
                $_SESSION['msg_text'] = $this->language['pack_must_zip'];
            }
        }
        $this->engine->url->redirect($this->engine->url->full);
    }

    public function getModules($start, $limit) {
        $q = $this->engine->db->query("SELECT id, name, description, enabled, has_ui, ordering FROM " . DB_PREF .
            "modules LIMIT " . (int)$start . ", " . (int)$limit);
        return $q->rows;
    }

    private function getModule($id) {
        $q = $this->engine->db->query("SELECT * FROM " . DB_PREF . "modules WHERE id=" . (int)$id);
        return $q->row;
    }

    private function getRights() {
        $q = $this->engine->db->query("SELECT * FROM " . DB_PREF . "user_group");
        $rights = array();
        $tmp = '';
        foreach ($q->rows as $f) {
            if ($tmp <> '') $tmp .= ', ';
            $tmp .= $f['description'];
            $rights[$f['id']] = $tmp;
        }
        return $rights;
    }

    private function getPageCount($limit) {
        $q = $this->engine->db->query("SELECT COUNT(1) as count FROM " . DB_PREF . "modules");
        $all_vals = 0;
        if ($q->num_rows > 0) {
            $f = $q->row;
            $all_vals = (int)$f['count'];
        }
        return ceil($all_vals / $limit) > 1 ? ceil($all_vals / $limit) : 1;
    }

    public function index() {
        if ($_SESSION['access'] > 2) {
            die('Access denied');
        }

        if (isset($_POST['modules']) && $_POST['action'] == 'save') {
            $this->saveModules();
        }

        if (isset($_POST['fp'])) {
            $this->updateModules();
        }

        if (isset($_POST['description']) && (int)$_GET['id'] > 0) {
            $this->updateModule((int)$_GET['id']);
        }

        if (isset($_POST['action']) && $_POST['action'] == 'install') {
            $this->installModule();
        }

        if (!isset($_GET["page_n"]) || (int)$_GET['page_n'] < 1) {
            $_GET["page_n"] = 1;
        }

        if (!isset($_GET["per_page"]) || (int)$_GET['per_page'] < 1) {
            $_GET['per_page'] = 10;
        }

        if (isset($_SESSION['msg']) && $_SESSION['msg'] == 'success') {
            $this->data['text_message'] = $this->language['changes_applied'];
            $this->data['class_message'] = 'success';
            unset($_SESSION['msg']);
        }

        if (isset($_SESSION['msg']) && $_SESSION['msg'] <> 'success') {
            $this->data['text_message'] = $_SESSION['msg_text'];
            $this->data['class_message'] = 'error';
            unset($_SESSION['msg']);
            unset($_SESSION['msg_text']);
        }

        if (isset($_GET['id']) && (int)$_GET['id'] > 0) {
            $this->data['rights'] = $this->getRights();
            $this->data['module'] = $this->getModule((int)$_GET['id']);

            $this->data['breadcrumbs'][] = array(
                'caption'   => $this->language['home'],
                'link'      => ADM_PATH
            );
            $this->data['breadcrumbs'][] = array(
                'caption'   => $this->language['modules'],
                'link'      => 'index.php?page=modules'
            );
            $this->data['breadcrumb_cur'] = $this->data['module']['description'];

            $this->template = 'template/module.tpl';
        } else {
            $start = ((int)$_GET["page_n"] - 1) * (int)$_GET["per_page"];
            $limit = (int)$_GET["per_page"];
            $this->data['page_count'] = $this->getPageCount($limit);
            $this->data['modules'] = $this->getModules($start, $limit);

            $this->data['breadcrumbs'][] = array(
                'caption'   => $this->language['home'],
                'link'      => ADM_PATH
            );
            $this->data['breadcrumb_cur'] = $this->language['modules'];

            $this->template = 'template/modules.tpl';
        }
    }
}