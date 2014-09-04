<?php

class Account extends QModule {

    protected $version = '1.0';

    public function addLanguage($lang) {
        $this->params['title_' . $lang]                  = $this->params['title_' . DEF_LANG];
        $this->params['kw_' . $lang]                     = $this->params['kw_' . DEF_LANG];
        $q = $this->engine->db->query("UPDATE " . DB_PREF . "modules SET `params`='" . $this->engine->db->escape(serialize($this->params)) . "' WHERE name='account'");
        if ($q) return true; else return false;
    }

    public function confirm() {
        $this->engine->db->query("UPDATE " . DB_PREF . "users SET `confirm` = '1' WHERE `confirm` = '" . $_GET['confirm_key'] . "'");
    }

    public function index() {
        $this->engine->ERROR_404 = FALSE;
        if ($_GET['action'] == 'register') {
            $this->engine->document->setTitle($this->params['title_registration_' . $_SESSION['lang']]);

            if (isset($_SESSION['msg'])) {
                if ($_SESSION['msg'] == 'sent') {
                    $this->data['text_message'] = $this->params['sent_' . $_SESSION['lang']];
                    $this->data['class_message'] = 'success';
                } elseif ($_SESSION['msg'] == 'message_fail') {
                    $this->data['text_message'] = $this->params['message_fail_' . $_SESSION['lang']];
                    $this->data['class_message'] = 'error';
                } elseif ($_SESSION['msg'] == 'empty_vars') {
                    $this->data['text_message'] = $this->params['empty_vars_' . $_SESSION['lang']];
                    $this->data['class_message'] = 'error';
                }
                unset($_SESSION['msg']);
            }
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
            $this->data['name']                 = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
            $this->data['password']             = isset($_POST['name']) ? htmlspecialchars($_POST['password']) : '';
            $this->data['phone']                = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
            $this->data['email']                = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
            $this->data['message']              = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '';

            $this->template = TEMPLATE . 'account_register.tpl';
        }
    }
}