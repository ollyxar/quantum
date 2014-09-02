<?php
namespace A;

class Feedback extends \AUnit {

    private function update() {
        if ($this->access['rw'] >= $_SESSION['access']) {
            foreach ($this->engine->languages as $lang) {
                $params_arr['title_' . $lang['name']]               = $_POST['title_' . $lang['name']];
                $params_arr['kw_' . $lang['name']]                  = $_POST['kw_' . $lang['name']];
                $params_arr['descr_' . $lang['name']]               = $_POST['descr_' . $lang['name']];
                $params_arr['info_' . $lang['name']]                = $_POST['info_' . $lang['name']];
                $params_arr['caption_' . $lang['name']]             = $_POST['caption_' . $lang['name']];
                $params_arr['send_' . $lang['name']]                = $_POST['send_' . $lang['name']];
                $params_arr['sent_' . $lang['name']]                = $_POST['sent_' . $lang['name']];
                $params_arr['message_fail_' . $lang['name']]        = $_POST['message_fail_' . $lang['name']];
                $params_arr['empty_vars_' . $lang['name']]          = $_POST['empty_vars_' . $lang['name']];
                $params_arr['email_placeholder_' . $lang['name']]   = $_POST['email_placeholder_' . $lang['name']];
                $params_arr['name_placeholder_' . $lang['name']]    = $_POST['name_placeholder_' . $lang['name']];
                $params_arr['phone_placeholder_' . $lang['name']]   = $_POST['phone_placeholder_' . $lang['name']];
                $params_arr['message_placeholder_' . $lang['name']] = $_POST['message_placeholder_' . $lang['name']];
            }
            $params_arr['captcha_required'] = $_POST['captcha_required'];
            $params_arr['email_required']   = $_POST['email_required'];
            $params_arr['name_required']    = $_POST['name_required'];
            $params_arr['phone_required']   = $_POST['phone_required'];
            $params_arr['message_required'] = $_POST['message_required'];

            $this->engine->db->query("UPDATE " . DB_PREF . "modules SET `params`='" .
                $this->engine->db->escape(serialize($params_arr)) . "' WHERE name='feedback'");

            if ($_POST['alias'] != '') {
                $row = $this->engine->db->query("SELECT id FROM " . DB_PREF . "url_alias WHERE query = 'route=feedback'")->row;
                if (!empty($row)) {
                    $this->engine->db->query("UPDATE " . DB_PREF . "url_alias SET `keyword` = '" . $_POST['alias'] . "' WHERE id = " . (int)$row['id']);
                } else {
                    $this->engine->db->query("INSERT INTO " . DB_PREF . "url_alias (`query`, `keyword`, `is_directory`) VALUES ('route=feedback', '" . $this->engine->db->escape($_POST['alias']) . "', '0')");
                }
                $this->engine->cache->delete('aliases');
            }

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

        $this->engine->document->addHeaderString('<script src="template/js/ckeditor/ckeditor.js"></script>');

        $this->data['settings'] = $this->params;
        $this->data['alias'] = '';
        $alias = $this->engine->db->query("SELECT keyword FROM " . DB_PREF . "url_alias WHERE query='route=feedback'")->row;
        if (!empty($alias)) {
            $this->data['alias'] = $alias['keyword'];
        }
        if (!isset($this->params['inputs'])) $this->data['settings']['inputs'] = array();

        $this->data['breadcrumbs'][] = array(
            'caption'   => $this->language['home'],
            'link'      => ADM_PATH
        );
        $this->data['breadcrumbs'][] = array(
            'caption'   => $this->language['modules'],
            'link'      => 'index.php?page=modules'
        );
        $this->data['breadcrumb_cur'] = $this->language['feedback'];

        $this->template = 'template/feedback.tpl';
    }
}