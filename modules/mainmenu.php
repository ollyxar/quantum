<?php

class MainMenu extends QModule {

    public function addLanguage($lang) {
        $this->engine->db->query("ALTER TABLE `" . DB_PREF . "main_menu` ADD `caption_" . $lang .
            "` VARCHAR( 255 ) NOT NULL;");
        $q = $this->engine->db->query("SELECT id, caption_" . DEF_LANG . " as caption FROM " . DB_PREF . "main_menu");
        foreach($q->rows as $f) {
            $this->engine->db->query("UPDATE " . DB_PREF . "main_menu SET `caption_" . $lang . "`='" .
                $f['caption'] . "' WHERE id=" . $f['id']);
        }
        if ($q) return true; else return false;
    }

    public function removeLanguage($lang) {
        $q = $this->engine->db->query("ALTER TABLE `" . DB_PREF . "main_menu` DROP `caption_" . $lang ."`;");
        if ($q) return true; else return false;
    }

    private function buildTreeEx($parent = 0, $arr_mass = array()) {
        $arr = array();
        if ($parent == 0) {
            $q = $this->engine->db->query("SELECT id, parent, caption_" . $_SESSION['lang'] .
                " as caption, path FROM " . DB_PREF . "main_menu WHERE enabled=1 ORDER BY ordering ASC");
            $arr_mass = $q->rows;
        }

        foreach ($arr_mass as $f) {
            if ($f['parent'] == $parent) {
                $arr[$f['id']]['caption'] = $f['caption'];
                $arr[$f['id']]['url'] = $this->engine->url->link($f['path']);

                if (($f['path'] == $_GET['_route_'] || ($f['path'] == 'route=home' && $_GET['route'] == 'home' && $this->engine->ERROR_404 == false)) && $this->params['ha'] == true) {
                    $active = true;
                } else {
                    $active = false;
                }

                $arr[$f['id']]['active'] = $active;
                $arr[$f['id']]['subs'] = $this->buildTreeEx($f['id'], $arr_mass);
            }
        }
        return $arr;
    }

    public function index() {
        $menu_box = $this->buildTreeEx();
        $this->data['menu_box'] = $menu_box;
        $this->template = TEMPLATE.'mainmenu.tpl';
    }
}

