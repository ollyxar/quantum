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

    public function getData() {
        return $this->data;
    }

    public function login($email, $password) {
        $email = $this->engine->db->escape(strtolower($email));
        $password = md5(md5($this->engine->db->escape($password)));
        $user = $this->engine->db->query("SELECT id FROM " . DB_PREF . "users WHERE LOWER(email)='" . $email . "' AND password='" . $password . "' AND enabled=1")->row;
        if (!empty($user)) {
            $_SESSION['user_id'] = $user['id'];
            $this->logged = true;
            $this->engine->db->query("UPDATE " . DB_PREF . "users SET `last_login` = '" . strtotime("now") . "' WHERE id=" . (int)$user['id']);
        }
        return $this->logged;
    }

    public function logout() {
        session_destroy();
    }

    public function register($name, $email, $password) {
        $result = $this->validate($name, $email, $password);
        if ($result == '') {
            $name = $this->engine->db->escape($name);
            $email = $this->engine->db->escape(strtolower($email));
            $password = $this->engine->db->escape($password);
            if ($this->engine->db->query("INSERT INTO " . DB_PREF . "users (`name`, `email`, `password`, `user_group`) VALUES ('" . $name . "', '" . $email . "', '" . $password . "', '5')")) {
                $this->sendConfirm($email);
            }
            return true;
        } else {
            return $result;
        }
    }

    private function validate($name, $email, $password) {
        $result = '';
        $email = $this->engine->db->query(strtolower($email));
        if (preg_match('/\w+@\w+?\.[a-zA-Z]{2,6}/', $email) && strlen($email) < 40) {
            $exists = $this->engine->db->query("SELECT COUNT(1) FROM " . DB_PREF . "users WHERE email = '" . $this->engine->db->escape($email) . "'")->row;
            if (!empty($exists)) {
                $result .= '1';
            }
        } else {
            $result .= '2';
        }
        if (strlen($name) < 2 || strlen($name) > 254) {
            $result .= '3';
        }
        if (strlen($password) < 5 || strlen($password) > 20) {
            $result .= '4';
        }

        return (int)$result;
    }

    private function sendConfirm($email) {
        $confirm_key = md5(md5($email));
        $this->engine->db->query("UPDATE " . DB_PREF . "users SET `confirm` = '" . $confirm_key . "' WHERE LOWER(email) = '" . strtolower($email) . "' ");
        $message = '<h1>Congratulations! You have successfully registered on ' . $_SERVER['HTTP_HOST'] . '</h1>';
        $message .= '<p>To finish your registration just follow the link.</p>';
        $message .= '<p><a href="' . $this->engine->url->link('route=account&action=register') . '&amp;confirm_key=' . $confirm_key . '">Confirm registration</a></p>';
        $message .= '<p>If it was not You just ignore this message.</p>';
        $this->engine->sendMail($email, 'system@' . $_SERVER['HTTP_HOST'], $_SERVER['HTTP_HOST'] . ' - Notification system', 'Подтверждение регистрации на "Блабла"', $message);
    }
} 