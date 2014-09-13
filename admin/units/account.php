<?php
namespace A;

class Account extends \AUnit {

    private function update() {
        if ($this->access['rw'] >= $_SESSION['access']) {
            foreach ($this->engine->languages as $lang) {
                $params_arr['placeholder_name_' . $lang['name']]     = $_POST['placeholder_name_' . $lang['name']];
                $params_arr['placeholder_email_' . $lang['name']]    = $_POST['placeholder_email_' . $lang['name']];
                $params_arr['placeholder_password_' . $lang['name']] = $_POST['placeholder_password_' . $lang['name']];
                $params_arr['old_pass_' . $lang['name']]             = $_POST['old_pass_' . $lang['name']];
                $params_arr['new_pass_' . $lang['name']]             = $_POST['new_pass_' . $lang['name']];
                $params_arr['title_registration_' . $lang['name']]   = $_POST['title_registration_' . $lang['name']];
                $params_arr['title_account_' . $lang['name']]        = $_POST['title_account_' . $lang['name']];
                $params_arr['title_login_' . $lang['name']]          = $_POST['title_login_' . $lang['name']];
                $params_arr['log_in_' . $lang['name']]               = $_POST['log_in_' . $lang['name']];
                $params_arr['log_out_' . $lang['name']]              = $_POST['log_out_' . $lang['name']];
                $params_arr['confirm_' . $lang['name']]              = $_POST['confirm_' . $lang['name']];
                $params_arr['save_' . $lang['name']]                 = $_POST['save_' . $lang['name']];
                $params_arr['agree_' . $lang['name']]                = htmlspecialchars($_POST['agree_' . $lang['name']], ENT_QUOTES, "UTF-8");
                $params_arr['account_exists_' . $lang['name']]       = $_POST['account_exists_' . $lang['name']];
                $params_arr['not_valid_email_' . $lang['name']]      = $_POST['not_valid_email_' . $lang['name']];
                $params_arr['not_valid_name_' . $lang['name']]       = $_POST['not_valid_name_' . $lang['name']];
                $params_arr['not_valid_password_' . $lang['name']]   = $_POST['not_valid_password_' . $lang['name']];
                $params_arr['not_valid_captcha_' . $lang['name']]    = $_POST['not_valid_captcha_' . $lang['name']];
                $params_arr['registration_finished_' . $lang['name']]= $_POST['registration_finished_' . $lang['name']];
                $params_arr['additional_text_' . $lang['name']]      = htmlspecialchars($_POST['additional_text_' . $lang['name']]);
                $params_arr['account_confirmed_' . $lang['name']]    = $_POST['account_confirmed_' . $lang['name']];
            }
            $params_arr['captcha_required'] = $_POST['captcha_required'];
            $params_arr['agreement']        = $_POST['agreement'];

            $this->engine->db->query("UPDATE " . DB_PREF . "modules SET `params`='" . $this->engine->db->escape(serialize($params_arr)) . "' WHERE name='account'");

            $_SESSION['msg'] = 'success';
        } else $_SESSION['msg'] = 'denied';
        $this->engine->url->redirect($this->engine->url->full);
    }

    public function index() {
        if ($_SESSION['access'] > $this->access['rr']) {
            die('Access denied');
        }

        if (isset($_POST['action']) && $_POST['action'] == 'save') {
            $this->update();
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

        $this->data['settings'] = $this->params;
        $this->data['breadcrumbs'][] = array(
            'caption'   => $this->language['home'],
            'link'      => ADM_PATH
        );
        $this->data['breadcrumbs'][] = array(
            'caption'   => $this->language['modules'],
            'link'      => 'index.php?page=modules'
        );
        $this->data['breadcrumb_cur'] = $this->language['account'];

        $this->template = 'template/account.tpl';
    }
}