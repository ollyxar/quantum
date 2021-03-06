<?php

class QUser {

    private $engine;
    public $logged = false;
    private $data = array();

    public function __construct(QOllyxar &$engine) {
        $this->engine = $engine;
        if (isset($_SESSION['user_id'])) {
            $this->data = $this->engine->db->query("SELECT * FROM " . DB_PREF . "users WHERE id=" . (int)$_SESSION['user_id'])->row;
            $this->logged = true;
        }
    }

    public function getData($key = false) {
        if (!$key) {
            return $this->data;
        } else {
            return isset($this->data[$key]) ? $this->data[$key] : 'Parameter does not exists';
        }
    }

    /**
     * @param $email
     * @param $password
     * @return int [0 - success, 1 - email or password is not correct, 2 - email is not confirmed]
     */
    public function login($email, $password) {
        $error_code = 0;
        $email = $this->engine->db->escape(strtolower($email));
        $password = md5(md5($this->engine->db->escape($password)));
        $user = $this->engine->db->query("SELECT id, enabled FROM " . DB_PREF . "users WHERE LOWER(email) = '" . $email . "' AND password='" . $password . "'")->row;
        if (!empty($user)) {
            if ((bool)$user['enabled']) {
                $_SESSION['user_id'] = $user['id'];
                $this->logged = true;
                $this->engine->db->query("UPDATE " . DB_PREF . "users SET `last_login` = '" . strtotime("now") . "' WHERE id=" . (int)$user['id']);
            } else {
                $error_code = 2;
            }
        } else {
            $error_code = 1;
        }
        return $error_code;
    }

    public function logout() {
        session_destroy();
    }

} 