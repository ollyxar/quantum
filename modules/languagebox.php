<?php

class LanguageBox extends QModule {

    public function index() {
        if (MULTILANG) {
            foreach ($this->engine->languages as $lang => $lang_arr) {
                if (CLEAN_URL) {
                    $this->data['lang_box'][$lang]['link'] = PRTCL . '://' . $this->engine->host . ROOT_DIR . $lang . '/' . substr($this->engine->uri, strlen(ROOT_DIR . $_SESSION['lang'] . '/'));
                } else {
                    $this->data['lang_box'][$lang]['link'] = PRTCL . '://' . $this->engine->host . str_replace('lang=' . $_SESSION['lang'], 'lang=' . $lang, $this->engine->uri);
                }
                $this->data['lang_box'][$lang]['description'] = $lang_arr['description'];
                $this->data['lang_box'][$lang]['picture']     = $lang_arr['picture'];
            }
            $this->template = TEMPLATE . 'template/widgets/languagebox.tpl';
        }
    }
}