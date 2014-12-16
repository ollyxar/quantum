<?php

abstract class AUnit {
    protected $version = '1.0';
    protected $engine;
    protected $data = array();
    protected $template;
    protected $language;
    protected $access = array();
    protected $params = array();

    public function __construct(QOllyxar &$engine, &$language) {
        $this->engine = $engine;
        $this->language = $language;
        $this->access['rr'] = 3;
        $this->access['rw'] = 3;
        $q = $this->engine->db->query("SELECT * FROM " . DB_PREF . "modules WHERE `name` = '" .
            strtolower(substr(get_class($this), 2)) . "'");
        if (!empty($q->row)) {
            $this->params = unserialize($q->row['params']);
            $this->access['rr'] = (int)$q->row['rr'];
            $this->access['rw'] = (int)$q->row['rw'];
        }
    }

    public function output() {
        extract($this->data);
        if (!empty($this->template)) {
            $language = &$this->language;
            $engine = &$this->engine;
            include($this->template);
        }
    }

    abstract public function index();
}
