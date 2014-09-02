<?php

class QConfig {

    private $data;
    private $path;

    public function __construct($path) {
        $this->loadConfig($path);
    }

    public function getPerm() {
        return substr(sprintf('%o', fileperms($this->path . '/config.php')), -4);
    }

    private function loadConfig($path) {
        $this->data = file_get_contents($path . '/config.php');
        $this->path = $path;
    }

    public function saveConfig() {
        return file_put_contents($this->path . '/config.php', $this->data);
    }

    public function replaceConst($name, $value) {
        if ($name <> '') {
            $search = substr($this->data, strpos($this->data, "define('" . $name));
            $search = substr($search, 0, strpos($search, ');') + 2);
            $replace = "define('" . $name . "', " . "'" . $value . "');";
            $this->data = str_replace($search, $replace, $this->data);
        }
    }
    public function replaceBool($name, $value) {
        if ($name <> '') {
            if ($value == '1' || $value == strtolower('true')) {
                $value = 'true';
            }
            if ($value == '0' || $value == strtolower('false')) {
                $value = 'false';
            }
            $search = substr($this->data, strpos($this->data, "define('" . $name));
            $search = substr($search, 0, strpos($search, ');') + 2);
            $replace = "define('" . $name . "', " . $value . ");";
            $this->data = str_replace($search, $replace, $this->data);
        }
    }
}