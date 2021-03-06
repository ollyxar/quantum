<?php

class Slider extends QModule {

    protected $version = '1.1';

    public function index() {
        $this->engine->document->addScript(TEMPLATE . 'js/jquery.bxslider.min.js');
        $this->engine->document->addStyle(TEMPLATE . 'css/jquery.bxslider.css');
        $this->data['slides']    = $this->params['slides'];
        $this->template          = TEMPLATE . 'template/widgets/slider.tpl';
    }
}
