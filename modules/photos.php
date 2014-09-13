<?php

class Photos extends QModule {

    public function addLanguage($lang) {
        $this->params['title_' . $lang]  = $this->params['title_' . DEF_LANG];
        $this->params['kw_' . $lang]     = $this->params['kw_' . DEF_LANG];
        $this->params['descr_' . $lang]  = $this->params['descr_' . DEF_LANG];
        $q = $this->engine->db->query("UPDATE " . DB_PREF . "modules SET `params`='" . $this->engine->db->escape(serialize($this->params)) . "' WHERE name='photos'");
        if ($q) return true; else return false;
    }

    private function findPhoto($start = 1, $limit = 20) {
        $dir = dirname(dirname(__FILE__)) . ROOT_DIR . 'upload/images/photos/';
        $photos = array();
        $i = 0;
        foreach (glob($dir . "*.*") as $filename) {
            $i++;
            if ($i > $limit + $start - 1) break;
            $filepath = $filename;
            $filepath = str_replace('\\', '/', $filepath);
            $path_parts = pathinfo($filepath);
            $photos[$i]['src'] = ROOT_DIR . 'upload/images/photos/' . $path_parts['basename'];
            $photos[$i]['thumb'] = resizeImage(ROOT_DIR . 'upload/images/photos/' . $path_parts['basename'], 150, 150);
        }
        if ($start > 1) {
            for ($i = 1; $i < $start; $i++) {
                unset($photos[$i]);
            }
        }
        return $photos;
    }

    public function find() {
        if (isset($_POST['photo_id'])) {
            $photos = $this->findPhoto(intval($_POST['photo_id']), 10);
            die(json_encode($photos));
        }
    }

    public function index() {
        if (!$this->engine->url->is_category) {
            $this->engine->ERROR_404 = false;
            $this->engine->document->setTitle($this->params['title_' . $_SESSION['lang']]);
            $this->engine->document->setKeywords($this->params['kw_' . $_SESSION['lang']]);
            $this->engine->document->setDescription($this->params['descr_' . $_SESSION['lang']]);
            $this->engine->document->addScript(TEMPLATE .'js/jquery.colorbox.js');
            $this->engine->document->addStyle(TEMPLATE . 'css/colorbox.css');
            $this->findPhoto();
            $this->data['photos'] = $this->findPhoto();
            $this->template = TEMPLATE . '/template/modules/photos.tpl';
        }
    }
}