<?php

abstract class QModule {
    protected $version = '1.0';
    protected $engine;
    protected $data = array();
    protected $template;
    public $adm_access = array();
    public $enabled = false;
    public $has_ui = false;
    public $params = array();
    public $route = array();
    public $description;

    public function output() {
        extract($this->data);
        if (!empty($this->template)) {
            // for calling engine from template
            $engine = &$this->engine;
            include($this->template);
        }
    }

    abstract protected function index();

    public function addLanguage($lang) {}

    public function removeLanguage($lang) {}

    public function __construct(QOllyxar &$engine) {
        $this->engine = $engine;
    }

    public function getVersion() {
        return $this->version;
    }
}
