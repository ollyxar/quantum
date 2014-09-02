<?php

class DB {

    private $driver;
    public $db_queries = 0;

    public function __construct($driver, $hostname, $username, $password, $database) {
        if (file_exists(dirname(__FILE__) . '/' . $driver . '.php')) {
            require_once(dirname(__FILE__) . '/' . $driver . '.php');
        } else {
            exit('Error: Could not load database file ' . $driver . '!');
        }

        $this->driver = new $driver($hostname, $username, $password, $database);
    }

    public function query($sql) {
        $this->db_queries++;
        return $this->driver->query($sql);
    }

    public function escape($value) {
        return $this->driver->escape($value);
    }

    public function countAffected() {
        return $this->driver->countAffected();
    }

    public function getLastId() {
        return $this->driver->getLastId();
    }

    public function getServerInfo() {
        return $this->driver->getServerInfo();
    }
}