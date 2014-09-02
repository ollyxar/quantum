<?php

class Slider extends QModule {

    protected $version = '1.1';

    public function index() {
        if ($_GET['route'] == 'home') {
            $this->engine->document->addHeaderString('<script src="' . TEMPLATE . 'js/jquery.bxslider.min.js"></script>');
            $this->engine->document->addHeaderString('<link href="' . TEMPLATE . 'css/jquery.bxslider.css" rel="stylesheet" media="screen">');
            $this->data['slides']    = $this->params['slides'];
            $this->template          = TEMPLATE . 'slider.tpl';
        }
    }
}