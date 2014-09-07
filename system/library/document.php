<?php

class QDocument {
    private $title;
    private $meta_description;
    private $meta_keywords;
    private $header_strings;

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

    public function render() {
        return '<title>' . $this->title . '</title><meta name="description" content="' . $this->meta_description . '" /><meta name="keywords" content="' . $this->meta_keywords . '" />' . $this->header_strings;
    }
}

