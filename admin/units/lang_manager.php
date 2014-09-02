<?php
namespace A;

class Lang_Manager extends \AUnit {
    private function removeLanguages() {
        foreach ($_POST['fp-language'] as $id) {
            switch ($_POST['language-action']) {
                case 'remove':
                    // get language code
                    $code = '';
                    foreach ($this->engine->languages as $language) {
                        if ($language['id'] == $id) {
                            $code = $language['name'];
                            break;
                        }
                    }
                    if ($code == DEF_LANG) {
                        $_SESSION['msg'] = 'denied';
                        $this->engine->url->redirect($this->engine->url->full);
                    }
                    $this->engine->removeLanguage($code);
                    $this->engine->db->query("DELETE FROM " . DB_PREF . "lang_details WHERE id=" . (int)$id);
                    break;
                default:
                    break;
            }
        }
        $_SESSION['msg'] = 'success';
        $this->engine->url->redirect($this->engine->url->full);
    }

    private function addLanguage() {
        $this->engine->addLanguage($this->engine->db->escape($_POST['language']['code']));
        $this->engine->db->query("INSERT INTO " . DB_PREF . "lang_details (`name`, `description`, `picture`, " .
            "`ordering`) VALUES ('" . $this->engine->db->escape($_POST['language']['code']) . "', '" .
            $this->engine->db->escape($_POST['language']['description']) . "', '" .
            $this->engine->db->escape($_POST['language']['picture']) . "', '" .
            $this->engine->db->escape($_POST['language']['ordering']) . "')");
        $_SESSION['msg'] = true;
        $this->engine->url->redirect($this->engine->url->full);
    }

    private function updateLanguage() {
        foreach ($_POST['lng'] as $id => $data) {
            $this->engine->db->query("UPDATE " . DB_PREF . "lang_details SET `description`='" .
                $this->engine->db->escape($data['description']) . "', `picture`='" .
                $this->engine->db->escape($data['picture']) . "', `ordering`='" .
                $this->engine->db->escape($data['ordering']) . "' WHERE id=" . (int)$id);
        }
        $_SESSION['msg'] = true;
        $this->engine->url->redirect($this->engine->url->full);
    }

    public function index() {
        if ($_SESSION['access'] > 2) {
            die('Access denied');
        }
        if (isset($_POST['fp-language'])) {
            $this->removeLanguages();
        }
        if (isset($_POST['language'])) {
            $this->addLanguage();
        }
        if (isset($_POST['lng']) && $_POST['language-action'] == 'save') {
            $this->updateLanguage();
        }
        if (isset($_SESSION['msg']) && $_SESSION['msg'] == 'success') {
            $this->data['text_message'] = $this->language['changes_applied'];
            $this->data['class_message'] = 'success';
            unset($_SESSION['msg']);
        }
        if (isset($_SESSION['msg']) && $_SESSION['msg'] == 'denied') {
            $this->data['text_message'] = $this->language['cant_delete_def_lang'];
            $this->data['class_message'] = 'error';
            unset($_SESSION['msg']);
        }
        $this->engine->document->addHeaderString('<script type="text/javascript" src="template/js/qfinder/qfinder.js"></script>');

        $this->data['breadcrumbs'][] = array(
            'caption'   => $this->language['home'],
            'link'      => ADM_PATH
        );
        $this->data['breadcrumb_cur'] = $this->language['language_manager'];

        $this->template = 'template/lang_manager.tpl';
    }
}