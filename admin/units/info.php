<?php
namespace A;

class Info extends \AUnit {
    private function getLastVisitors() {
        $q = $this->engine->db->query("SELECT " . DB_PREF . "users.id, " . DB_PREF . "users.name, " . DB_PREF .
            "users.adm_last_login, " . DB_PREF . "user_group.description FROM " . DB_PREF . "users, " . DB_PREF .
            "user_group WHERE " . DB_PREF . "user_group.id=" . DB_PREF . "users.user_group AND " . DB_PREF .
            "users.adm_last_login > 0 ORDER BY " . DB_PREF . "users.adm_last_login DESC LIMIT 0, 2");
        $last_visitors = array();
        foreach ($q->rows as $f) {
            $last_visitors[$f['id']]['name'] = $f['name'];
            $last_visitors[$f['id']]['description'] = $f['description'];
            $last_visitors[$f['id']]['adm_last_login'] = date('y/m/d H:i:s', $f['adm_last_login']);
        }
        return $last_visitors;
    }

    public function index() {
        $this->data['last_visitors'] = $this->getLastVisitors();
        $this->template = 'template/info.tpl';
    }
}