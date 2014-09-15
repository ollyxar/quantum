<?php

class Account extends QModule {

    protected $version = '1.0';

    public function addLanguage($lang) {
        $this->params['placeholder_name_' . $lang]      = $this->params['placeholder_name_' . DEF_LANG];
        $this->params['placeholder_email_' . $lang]     = $this->params['placeholder_email_' . DEF_LANG];
        $this->params['placeholder_password_' . $lang]  = $this->params['placeholder_password_' . DEF_LANG];
        $this->params['old_pass_' . $lang]              = $this->params['old_pass_' . DEF_LANG];
        $this->params['new_pass_' . $lang]              = $this->params['new_pass_' . DEF_LANG];
        $this->params['title_registration_' . $lang]    = $this->params['title_registration_' . DEF_LANG];
        $this->params['title_account_' . $lang]         = $this->params['title_account_' . DEF_LANG];
        $this->params['title_login_' . $lang]           = $this->params['title_login_' . DEF_LANG];
        $this->params['log_in_' . $lang]                = $this->params['log_in_' . DEF_LANG];
        $this->params['log_out_' . $lang]               = $this->params['log_out_' . DEF_LANG];
        $this->params['confirm_' . $lang]               = $this->params['confirm_' . DEF_LANG];
        $this->params['save_' . $lang]                  = $this->params['save_' . DEF_LANG];
        $this->params['agree_' . $lang]                 = $this->params['agree_' . DEF_LANG];
        $this->params['remember_me_' . $lang]           = $this->params['remember_me_' . DEF_LANG];
        $this->params['account_exists_' . $lang]        = $this->params['account_exists_' . DEF_LANG];
        $this->params['not_valid_email_' . $lang]       = $this->params['not_valid_email_' . DEF_LANG];
        $this->params['not_valid_name_' . $lang]        = $this->params['not_valid_name_' . DEF_LANG];
        $this->params['not_valid_password_' . $lang]    = $this->params['not_valid_password_' . DEF_LANG];
        $this->params['not_valid_captcha_' . $lang]     = $this->params['not_valid_captcha_' . DEF_LANG];
        $this->params['registration_finished_' . $lang] = $this->params['registration_finished_' . DEF_LANG];
        $this->params['additional_text_' . $lang]       = $this->params['additional_text_' . DEF_LANG];
        $this->params['confirmation_mail_' . $lang]     = $this->params['confirmation_mail_' . DEF_LANG];
        $this->params['account_confirmed_' . $lang]     = $this->params['account_confirmed_' . DEF_LANG];
        $this->params['data_incorrect_' . $lang]        = $this->params['data_incorrect_' . DEF_LANG];
        $this->params['email_not_confirmed_' . $lang]   = $this->params['email_not_confirmed_' . DEF_LANG];
        $this->params['login_success_' . $lang]         = $this->params['login_success_' . DEF_LANG];
        $this->params['unknown_error_' . $lang]         = $this->params['unknown_error_' . DEF_LANG];
        $q = $this->engine->db->query("UPDATE " . DB_PREF . "modules SET `params`='" . $this->engine->db->escape(serialize($this->params)) . "' WHERE name='account'");
        if ($q) return true; else return false;
    }

    public function register($name, $email, $password) {
        $result = $this->validate($name, $email, $password);
        if ($result == '') {
            $name = $this->engine->db->escape($name);
            $email = $this->engine->db->escape(strtolower($email));
            $password = md5(md5($this->engine->db->escape($password)));
            if ($this->engine->db->query("INSERT INTO " . DB_PREF . "users (`name`, `email`, `password`, `user_group`, `joined`, `enabled`) VALUES ('" . $name . "', '" . $email . "', '" . $password . "', '5', '" . strtotime(date("Y-m-d H:i:s")) . "', '0')")) {
                $this->sendConfirm($email);
            }
            return true;
        } else {
            return $result;
        }
    }

    public function nameCheck() {
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $result = '';
        if (strlen($name) > 2 && strlen($name) < 255) {
            $exists = $this->engine->db->query("SELECT id FROM " . DB_PREF . "users WHERE  name = '" . $name . "'")->row;
            if (!empty($exists)) {
                $result = $this->params['account_exists_' . $_SESSION['lang']];
            }
        } else {
            $result = $this->params['not_valid_name_' . $_SESSION['lang']];
        }
        die(json_encode($result));
    }

    public function emailCheck() {
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $result = '';
        if (preg_match('/\w+@\w+?\.[a-zA-Z]{2,6}/', $email) && strlen($email) < 40) {
            $exists = $this->engine->db->query("SELECT id FROM " . DB_PREF . "users WHERE  email = '" . $email . "'")->row;
            if (!empty($exists)) {
                $result = $this->params['account_exists_' . $_SESSION['lang']];
            }
        } else {
            $result = $this->params['not_valid_email_' . $_SESSION['lang']];
        }
        die(json_encode($result));
    }

    private function validate($name, $email, $password) {
        $result = '';
        $email = $this->engine->db->escape(strtolower($email));
        $name = $this->engine->db->escape($name);
        if (preg_match('/\w+@\w+?\.[a-zA-Z]{2,6}/', $email) && strlen($email) < 40) {
            $exists = $this->engine->db->query("SELECT id FROM " . DB_PREF . "users WHERE email = '" . $email . "' OR name = '" . $name . "'")->row;
            if (!empty($exists)) {
                $result .= $this->params['account_exists_' . $_SESSION['lang']] . '<br />';
            }
        } else {
            $result .= $this->params['not_valid_email_' . $_SESSION['lang']] . '<br />';
        }
        if (strlen($name) < 2 || strlen($name) > 254) {
            $result .= $this->params['not_valid_name_' . $_SESSION['lang']] . '<br />';
        }
        if (strlen($password) < 5 || strlen($password) > 20) {
            $result .= $this->params['not_valid_password_' . $_SESSION['lang']] . '<br />';
        }
        if (!isset($_POST['agree'])) {
            $result .= 'You must agree with agreement<br />';
        }

        return $result;
    }

    private function sendConfirm($email) {
        $confirm_key = md5(md5(makeRandomString()));
        $this->engine->db->query("UPDATE " . DB_PREF . "users SET `confirm` = '" . $confirm_key . "' WHERE LOWER(email) = '" . strtolower($email) . "' ");
        $message = html_entity_decode($this->params['confirmation_mail_' . $_SESSION['lang']]);
        $message = str_replace('{confirm_link}', $this->engine->url->link('route=account&action=confirm' , 'confirm_key=') . $confirm_key, $message);
        $this->engine->sendMail($email, 'system@' . $_SERVER['HTTP_HOST'], $_SERVER['HTTP_HOST'] . ' - Notification system', 'Please confirm your account', $message);
    }

    public function confirm($key) {
        $this->engine->db->query("UPDATE " . DB_PREF . "users SET `confirm` = '', `enabled` = '1' WHERE `confirm` = '" . $this->engine->db->escape($key) . "'");
    }

    public function login() {
        die(json_encode($this->engine->user->login($_POST['email'], $_POST['password'])));
    }

    public function index() {
        $this->engine->ERROR_404 = FALSE;
        if (!isset($_GET['action'])) {
            $_GET['action'] = '';
        }
        if ($_GET['action'] == 'register') {
            if ($this->engine->user->logged) {
                $this->engine->url->redirect($this->engine->url->link('route=account'));
            }
            if (isset($_POST['register'])) {
                if (((bool)$this->params['captcha_required'] && $_SESSION['captcha'] == $_POST['captcha']) || !(bool)$this->params['captcha_required']) {
                    $result = $this->register($_POST['name'], $_POST['email'], $_POST['password']);
                    if ($result === true) {
                        $_SESSION['msg'] = 'success';
                        $this->engine->url->redirect($this->engine->url->full);
                    } else {
                        $_SESSION['msg'] = 'fail';
                        $this->data['text_message'] = $result;
                    }
                } else {
                    $_SESSION['msg'] = 'captcha_not_valid';
                }
            }
            $this->engine->document->setTitle($this->params['title_registration_' . $_SESSION['lang']]);
            if ((bool)$this->params['captcha_required']) {
                $captcha = new QCaptcha();
                $this->data['captcha'] = $captcha->getContent();
                $_SESSION['captcha'] = $captcha->getCode();
                unset($captcha);
            }
            $this->data['caption']              = $this->params['title_registration_' . $_SESSION['lang']];
            $this->data['placeholder_name']     = $this->params['placeholder_name_' . $_SESSION['lang']];
            $this->data['placeholder_email']    = $this->params['placeholder_email_' . $_SESSION['lang']];
            $this->data['placeholder_password'] = $this->params['placeholder_password_' . $_SESSION['lang']];
            $this->data['confirm']              = $this->params['confirm_' . $_SESSION['lang']];
            $this->data['agree']                = sprintf(html_entity_decode($this->params['agree_' . $_SESSION['lang']]), htmlspecialchars($this->engine->url->link($this->params['agreement'])));
            $this->data['password_not_valid']   = $this->params['not_valid_password_' . $_SESSION['lang']];
            $this->data['name']                 = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
            $this->data['password']             = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';
            $this->data['email']                = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';

            $template = 'template/account/register.tpl';
            if (isset($_SESSION['msg'])) {
                if ($_SESSION['msg'] == 'success') {
                    $this->data['caption'] = $this->params['registration_finished_' . $_SESSION['lang']];
                    $this->data['text'] = $this->params['additional_text_' . $_SESSION['lang']];
                    $template = 'template/common/success.tpl';
                } elseif ($_SESSION['msg'] == 'fail') {
                    $this->data['class_message'] = 'error';
                } elseif ($_SESSION['msg'] == 'captcha_not_valid') {
                    $this->data['text_message'] = $this->params['not_valid_captcha_' . $_SESSION['lang']];
                    $this->data['class_message'] = 'error';
                }
                unset($_SESSION['msg']);
            }
        } elseif ($_GET['action'] == 'confirm') {
            if ($this->engine->user->logged) {
                $this->engine->url->redirect($this->engine->url->link('route=account'));
            }
            if (!isset($_GET['confirm_key']) || $_GET['confirm_key'] == '') {
                $this->engine->url->redirect($this->engine->url->link('route=home'));
            }
            $this->confirm($_GET['confirm_key']);
            $this->engine->document->setTitle($this->params['account_confirmed_' . $_SESSION['lang']]);
            $this->data['caption'] = $this->params['account_confirmed_' . $_SESSION['lang']];
            $this->data['text'] = '';
            $template = 'template/common/success.tpl';
        } elseif ($_GET['action'] == 'login') {
            if ($this->engine->user->logged) {
                $this->engine->url->redirect($this->engine->url->link('route=account'));
            }
            $this->engine->document->setTitle($this->params['title_login_' . $_SESSION['lang']]);
            $this->data['caption']              = $this->params['title_login_' . $_SESSION['lang']];
            $this->data['placeholder_email']    = $this->params['placeholder_email_' . $_SESSION['lang']];
            $this->data['placeholder_password'] = $this->params['placeholder_password_' . $_SESSION['lang']];
            $this->data['log_in']               = $this->params['log_in_' . $_SESSION['lang']];
            $this->data['password_not_valid']   = $this->params['not_valid_password_' . $_SESSION['lang']];
            $this->data['remember_me']          = $this->params['remember_me_' . $_SESSION['lang']];
            $this->data['data_incorrect']       = $this->params['data_incorrect_' . $_SESSION['lang']];
            $this->data['email_not_confirmed']  = $this->params['email_not_confirmed_' . $_SESSION['lang']];
            $this->data['login_success']        = $this->params['login_success_' . $_SESSION['lang']];
            $this->data['unknown_error']        = $this->params['unknown_error_' . $_SESSION['lang']];
            $this->data['password']             = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';
            $this->data['email']                = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
            $template = 'template/account/login.tpl';
        } else {
            if (!$this->engine->user->logged) {
                $this->engine->url->redirect($this->engine->url->link('route=account', 'action=login'));
            }
            $template = 'template/account/account.tpl';
        }
        $this->template = TEMPLATE . $template;
    }
}