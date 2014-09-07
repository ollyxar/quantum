<?php

class Feedback extends QModule {

    protected $version = '1.2';

    public function addLanguage($lang) {
        $this->params['title_' . $lang]                  = $this->params['title_' . DEF_LANG];
        $this->params['kw_' . $lang]                     = $this->params['kw_' . DEF_LANG];
        $this->params['descr_' . $lang]                  = $this->params['descr_' . DEF_LANG];
        $this->params['sent_' . $lang]                   = $this->params['sent_' . DEF_LANG];
        $this->params['message_fail_' . $lang]           = $this->params['message_fail_' . DEF_LANG];
        $this->params['empty_vars_' . $lang]             = $this->params['empty_vars_' . DEF_LANG];
        $this->params['info_' . $lang]                   = $this->params['info_' . DEF_LANG];
        $this->params['caption_' . $lang]                = $this->params['caption_' . DEF_LANG];
        $this->params['send_' . $lang]                   = $this->params['send_' . DEF_LANG];
        $this->params['email_placeholder_' . $lang]      = $this->params['email_placeholder_' . DEF_LANG];
        $this->params['name_placeholder_' . $lang]       = $this->params['name_placeholder_' . DEF_LANG];
        $this->params['phone_placeholder_' . $lang]      = $this->params['phone_placeholder_' . DEF_LANG];
        $this->params['message_placeholder_' . $lang]    = $this->params['message_placeholder_' . DEF_LANG];
        $q = $this->engine->db->query("UPDATE " . DB_PREF . "modules SET `params`='" . $this->engine->db->escape(serialize($this->params)) . "' WHERE name='feedback'");
        if ($q) return true; else return false;
    }

    private function validate() {
        if (
            ((bool)$this->params['email_required'] && empty($_POST['email'])) ||
            ((bool)$this->params['name_required'] && empty($_POST['name'])) ||
            ((bool)$this->params['phone_required'] && empty($_POST['phone'])) ||
            ((bool)$this->params['message_required'] && empty($_POST['message']) ||
            ((bool)$this->params['captcha_required'] && $_SESSION['captcha'] != $_POST['captcha']))
        ) {
            return false;
        } else {
            return true;
        }
    }

    public function index() {
        if (isset($_POST['feedback'])) {
            if ($this->validate()) {
                $mb = '<table><tr><td>Message from:</td><td>' . $_POST['name'] . '</td></tr>';
                $mb .= '<tr><td>Email:</td><td>' . $_POST['email'] . '</td></tr>';
                $mb .= '<tr><td>Phone:</td><td>' . $_POST['phone'] . '</td></tr>';
                $mb .= '<tr><td>Message:</td><td>' . $_POST['message'] . '</td></tr></table>';
                if ($this->engine->sendMail(EMAIL, 'system@' . $this->engine->host, 'System mailer', 'Feedback', $mb)) {
                    $_SESSION['msg'] = 'sent';
                } else {
                    $_SESSION['msg'] = 'message_fail';
                }
                $this->engine->url->redirect($this->engine->url->full);
            } else {
                $_SESSION['msg'] = 'empty_vars';
            }
        }

        $this->engine->ERROR_404 = FALSE;
        $this->engine->document->setTitle($this->params['title_' . $_SESSION['lang']]);
        $this->engine->document->setKeywords($this->params['kw_' . $_SESSION['lang']]);
        $this->engine->document->setDescription($this->params['descr_' . $_SESSION['lang']]);

        if ((bool)$this->params['captcha_required']) {
            $captcha = new QCaptcha();
            $this->data['captcha'] = $captcha->getContent();
            $_SESSION['captcha'] = $captcha->getCode();
            unset($captcha);
        }

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
        $this->data['name']                 = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
        $this->data['phone']                = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
        $this->data['email']                = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
        $this->data['message']              = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '';

        $this->data['info']                 = $this->params['info_' . $_SESSION['lang']];
        $this->data['caption']              = $this->params['caption_' . $_SESSION['lang']];
        $this->data['send']                 = $this->params['send_' . $_SESSION['lang']];
        $this->data['email_placeholder']    = $this->params['email_placeholder_' . $_SESSION['lang']];
        $this->data['name_placeholder']     = $this->params['name_placeholder_' . $_SESSION['lang']];
        $this->data['phone_placeholder']    = $this->params['phone_placeholder_' . $_SESSION['lang']];
        $this->data['message_placeholder']  = $this->params['message_placeholder_' . $_SESSION['lang']];
        $this->template = TEMPLATE . 'feedback.tpl';
    }
}