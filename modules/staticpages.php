<?php

class StaticPages extends QModule {

    protected $version = '1.1';

    public function fillPage($page_id) {
        $page = $this->engine->db->query("SELECT caption_" . $_SESSION['lang'] . " as caption, text_" . $_SESSION['lang'] . " as text, " . "title_" . $_SESSION['lang'] . " as title, kw_" . $_SESSION['lang'] . " as kw, descr_" . $_SESSION['lang'] . " as descr FROM " . DB_PREF . "static_pages WHERE id='" . (int)$page_id . "' AND enabled=1")->row;
        if (!empty($page)) {
            $this->engine->ERROR_404    = false;
            $this->data['caption']      = $page['caption'];
            $this->data['text']         = $page['text'];

            if ($page['title'] != '') {
                $this->engine->document->setTitle($page['title']);
            }
            if ($page['kw'] != '') {
                $this->engine->document->setKeywords($page['kw']);
            }
            if ($page['descr'] != '') {
                $this->engine->document->setDescription($page['descr']);
            }
            return true;
        } else {
            $this->engine->ERROR_404 = true;
            return false;
        }
    }

    public function addLanguage($lang) {
        $this->engine->db->query("ALTER TABLE `" . DB_PREF . "static_pages` ADD `caption_" . $lang .
            "` VARCHAR( 255 ) NOT NULL, ADD `text_" . $lang . "` TEXT NOT NULL, ADD `title_" . $lang .
            "` TEXT NOT NULL, ADD `kw_" . $lang . "` TEXT NOT NULL, ADD `descr_" . $lang . "` TEXT NOT NULL;");
        $q = $this->engine->db->query("SELECT id, caption_" . DEF_LANG . " as caption, title_" . DEF_LANG .
            " as title, text_" . DEF_LANG . " as text, kw_" . DEF_LANG . " as kw, descr_" . DEF_LANG .
            " as descr FROM " . DB_PREF . "static_pages");
        foreach($q->rows as $f) {
            $this->engine->db->query("UPDATE " . DB_PREF . "static_pages SET `caption_" . $lang . "`='" .
                $f['caption'] . "', `title_" . $lang . "`='" . $f['title'] . "', `text_" . $lang . "`='" .
                $f['text'] . "', `kw_" . $lang . "`='" . $f['kw'] . "', `descr_" . $lang . "`='" . $f['descr'] .
                "' WHERE id=" . $f['id']);
        }
        if ($q) return true; else return false;
    }

    public function removeLanguage($lang) {
        $q = $this->engine->db->query("ALTER TABLE `" . DB_PREF . "static_pages` DROP `caption_" . $lang .
            "`, DROP `text_" . $lang . "`, DROP `title_" . $lang . "`, DROP `kw_" . $lang . "`, DROP `descr_" .
            $lang . "`;");
        if ($q) return true; else return false;
    }

    public function index() {
        if (!isset($_GET['page_id'])) {
            $this->engine->ERROR_404 = true;
            return false;
        }
        if (!$this->engine->url->is_category) {
            $this->fillPage($_GET['page_id']);
        }
        $this->template = TEMPLATE . 'template/modules/static_page.tpl';
    }
}