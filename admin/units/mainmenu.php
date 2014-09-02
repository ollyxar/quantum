<?php
namespace A;

class MainMenu extends \AUnit {

    private $lst = array();

    private function saveSettings() {
        if ($this->access['rw'] >= $_SESSION['access']) {
            $params_arr = array('ha' => (bool)$_POST['ha']);
            $this->engine->db->query("UPDATE " . DB_PREF . "modules SET `params`='" .
                $this->engine->db->escape(serialize($params_arr)) . "' WHERE name='mainmenu'");
            $_SESSION['msg'] = 'success';
        } else {
            $_SESSION['msg'] = 'denied';
        }
        $this->engine->url->redirect($this->engine->url->full);
    }

    private function updateItems() {
        if ($this->access['rw'] >= $_SESSION['access']) {
            foreach ($_POST['menu'] as $id => $data) {
                $query = '';
                foreach ($this->engine->languages as $lang) {
                    $query .= '`caption_' . $this->engine->db->escape($lang['name']) . "`='" .
                        $this->engine->db->escape($data['caption_' . $lang['name']]) . "', ";
                }
                $query = substr($query, 0, -2);
                $this->engine->db->query("UPDATE " . DB_PREF . "main_menu SET `path`='" .
                    $this->engine->db->escape($data['path']) . "', `ordering`='" .
                    (int)$this->engine->db->escape($data['ordering']) . "', `parent`='" .
                    (int)$this->engine->db->escape($data['parent']) . "', `enabled`='" .
                    $this->engine->db->escape($data['enabled']) . "', " . $query . " WHERE id=" . (int)$id);
            }

            $_SESSION['msg'] = 'success';
        } else {
            $_SESSION['msg'] = 'denied';
        }
        $this->engine->url->redirect($this->engine->url->full);
    }

    private function addItem() {
        if ($this->access['rw'] >= $_SESSION['access']) {
            $query_names = '';
            foreach ($this->engine->languages as $lang) {
                $query_names .= '`caption_' . $lang['name'] . "`, ";
            }
            $query_vals = '';
            foreach ($this->engine->languages as $lang) {
                $query_vals .= "'" . $this->engine->db->escape($_POST['menu']['caption_' . $lang['name']]) . "', ";
            }
            $this->engine->db->query("INSERT INTO " . DB_PREF . "main_menu (`parent`, " . $query_names .
                " `path`, `ordering`, `enabled`) VALUES ('" .
                $this->engine->db->escape($_POST['menu']['parent']) . "', " . $query_vals . " '" .
                $this->engine->db->escape($_POST['menu']['path']) . "', '" .
                $this->engine->db->escape($_POST['menu']['ordering']) . "', '" .
                $this->engine->db->escape($_POST['menu']['enabled']) . "');");
            $_SESSION['msg'] = 'success';
        } else {
            $_SESSION['msg'] = 'denied';
        }
        $u = str_replace('menu_id=new', 'menu_id=' . $this->engine->db->getLastId(), $_SERVER['REQUEST_URI']);
        $this->engine->url->redirect(PRTCL . "://" . $this->engine->host . $u);
    }

    private function preRemove($id) {
        $q = $this->engine->db->query("SELECT id FROM " . DB_PREF . "main_menu WHERE parent=" . (int)$id);
        foreach ($q->rows as $f) {
            $this->lst[] = $f['id'];
            $this->preRemove($f['id']);
        }
    }

    private function removeItems() {
        if ($this->access['rw'] >= $_SESSION['access']) {
            foreach ($_POST['menu'] as $id => $data) {
                $this->preRemove($id);
                foreach ($this->lst as $sub_id) {
                    $this->engine->db->query("DELETE FROM " . DB_PREF . "main_menu WHERE id=" . (int)$sub_id);
                }
                $this->engine->db->query("DELETE FROM " . DB_PREF . "main_menu WHERE id=" . (int)$id);
                $this->lst = array();
            }
            $_SESSION['msg'] = 'success';
        } else {
            $_SESSION['msg'] = 'denied';
        }
        $this->engine->url->redirect($this->engine->url->full);
    }

    private function buildTree($parent = 0) {
        $q = $this->engine->db->query("SELECT id, caption_" . DEF_LANG . " as caption, path, enabled FROM " .
            DB_PREF . "main_menu WHERE parent=" . (int)$parent . " ORDER BY ordering ASC");
        $menu_arr = array();
        foreach ($q->rows as $f) {
            $menu_arr[$f['id']] = array(
                'caption'   => '(' . $f['id'] . ') ' . $f['caption'],
                'path'      => $f['path'],
                'enabled'   => $f['enabled'],
                'subs'      => $this->buildTree($f['id'])
            );
        }
        return $menu_arr;
    }

    private function getMenuList() {
        $q = $this->engine->db->query("SELECT id, caption_" . DEF_LANG . " as caption FROM " . DB_PREF . "main_menu");
        $menu_lst = array(0 => array('caption' => 'root'));
        foreach ($q->rows as $f) {
            $menu_lst[$f['id']]['caption'] = $f['caption'];
        }
        return $menu_lst;
    }

    private function getItem($menu_id) {
        $q = $this->engine->db->query("SELECT * FROM " . DB_PREF . "main_menu WHERE id='" . (int)$menu_id . "'");
        return $q->row;
    }

    private function getPages() {
        $sp_res = array();
        $m_res = array();
        $static_pages = $this->engine->db->query("SELECT id, caption_" . DEF_LANG . " as caption FROM " . DB_PREF . "static_pages  ORDER BY caption")->rows;
        for ($i = 0; $i < count($static_pages); $i++) {
            $sp_res[$i] = array(
                'caption' => $static_pages[$i]['caption'],
                'route'   => 'route=pages&page_id=' . $static_pages[$i]['id']
            );
        }
        $materials = $this->engine->db->query("SELECT id, caption_" . DEF_LANG . " as caption FROM " . DB_PREF . "materials  ORDER BY caption")->rows;
        for ($i = 0; $i < count($materials); $i++) {
            $m_res[$i] = array(
                'caption' => $materials[$i]['caption'],
                'route'   => 'route=materials&material_id=' . $materials[$i]['id']
            );
        }
        $result = array(
            'materials' => $m_res,
            's_pages'   => $sp_res
        );
        die(json_encode($result));
    }

    private function getRealPath($route) {
        die(json_encode(htmlspecialchars($this->engine->url->link(html_entity_decode($route)))));
    }

    public function index() {
        if ($_SESSION['access'] > $this->access['rr']) {
            die('Access denied');
        }

        if (isset($_POST['get_pages'])) {
            $this->getPages();
        }

        if (isset($_POST['get_real_path'])) {
            $this->getRealPath($_POST['get_real_path']);
        }

        if (isset($_POST['action']) && $_POST['action'] == 'save-settings') {
            $this->saveSettings();
        }

        if (isset($_POST['menu']) && $_POST['action'] == 'save' && $_GET['menu_id'] <> 'new') {
            $this->updateItems();
        }

        if (isset($_POST['menu']) && $_POST['action'] == 'save' && $_GET['menu_id'] == 'new') {
            $this->addItem();
        }

        if (isset($_POST['menu']) && $_POST['action'] == 'remove') {
            $this->removeItems();
        }

        if (isset($_SESSION['msg']) && $_SESSION['msg'] == 'success') {
            $this->data['text_message'] = $this->language['changes_applied'];
            $this->data['class_message'] = 'success';
            unset($_SESSION['msg']);
        }
        if (isset($_SESSION['msg']) && $_SESSION['msg'] == 'denied') {
            $this->data['text_message'] = $this->language['access_denied'];
            $this->data['class_message'] = 'error';
            unset($_SESSION['msg']);
        }

        $this->data['menu_id'] = isset($_GET['menu_id']) ? $_GET['menu_id'] : 0;
        $this->data['menu_item'] = $this->getItem($this->data['menu_id']);
        $this->data['menu'] = array('0' => array('caption' => 'root', 'subs' => $this->buildTree()));
        $this->data['parents_to_menu'] = $this->getMenuList();
        $this->data['settings'] = $this->params;

        $this->engine->document->addHeaderString('<script src="template/js/jquery.cookie.js"></script>');
        $this->engine->document->addHeaderString('<script src="template/js/jquery.treeview.js"></script>');
        $this->engine->document->addHeaderString('<link href="template/css/jquery.treeview.css" rel="stylesheet" media="screen">');
        $this->engine->document->addHeaderString('<link href="template/css/bootstrap-toggle-buttons.css" rel="stylesheet" media="screen">');
        $this->engine->document->addHeaderString('<script src="template/js/jquery.toggle.buttons.js"></script>');

        $this->data['breadcrumbs'][] = array(
            'caption'   => $this->language['home'],
            'link'      => ADM_PATH
        );
        $this->data['breadcrumbs'][] = array(
            'caption'   => $this->language['modules'],
            'link'      => 'index.php?page=modules'
        );
        $this->data['breadcrumb_cur'] = $this->language['main_menu'];

        $this->template = 'template/mainmenu.tpl';
    }
}