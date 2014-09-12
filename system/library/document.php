<?php

class QDocument {
    private $title;
    private $meta_description;
    private $meta_keywords;
    private $header_strings;
    private $scripts = array();
    private $styles = array();

    public function __construct() {
        $this->header_strings = '<meta name="generator" content="Ollyxar" />';
    }

    public function setTitle($str) {
        $this->title = $str;
    }

    public function setDescription($str) {
        $this->meta_description = $str;
    }

    public function setKeywords($str) {
        $this->meta_keywords = $str;
    }

    public function addHeaderString($str) {
        $this->header_strings .= $str;
    }

    public function addScript($str) {
        $this->scripts[] = $str;
    }

    public function addStyle($str) {
        $this->styles[] = $str;
    }

    public function render() {
        $data = '<title>' . $this->title . '</title><meta name="description" content="' . $this->meta_description . '" /><meta name="keywords" content="' . $this->meta_keywords . '" />' . $this->header_strings;
        foreach ($this->styles as $style) {
            $data .= '<link href="' . $style . '" rel="stylesheet" media="screen">';
        }
        foreach ($this->scripts as $script) {
            $data .= '<script src="' . $script . '"></script>';
        }
        return $data;
    }
}

