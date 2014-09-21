<?php

class AccountWidget extends QModule {

    public function index() {
        $params = unserialize($this->engine->db->query("SELECT params FROM " . DB_PREF . "modules WHERE name = 'account'")->row['params']);
        if ($this->engine->user->logged) {
            $this->data['links'][] = array(
                'caption'   => $params['title_account_' . $_SESSION['lang']],
                'href'      => $this->engine->url->link('route=account')
            );
            $this->data['links'][] = array(
                'caption'   => $params['log_out_' . $_SESSION['lang']],
                'href'      => $this->engine->url->link('route=account', 'action=logout')
            );
        } else {
            $this->data['links'][] = array(
                'caption'   => $params['log_in_' . $_SESSION['lang']],
                'href'      => $this->engine->url->link('route=account', 'action=login')
            );
            $this->data['links'][] = array(
                'caption'   => $params['title_registration_' . $_SESSION['lang']],
                'href'      => $this->engine->url->link('route=account', 'action=register')
            );
        }
        $this->template = TEMPLATE . 'template/widgets/account.tpl';
    }
}