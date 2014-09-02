<?php
final class MySQL_i {
    private $link;

    public function __construct($hostname, $username, $password, $database) {
        if (!$this->link = mysqli_connect($hostname, $username, $password, $database)) {
            trigger_error('Error: Could not make a database link using ' . $username . '@' . $hostname);
        }

        mysqli_query($this->link, "SET NAMES 'utf8'");
        mysqli_query($this->link, "SET CHARACTER SET utf8");
        mysqli_query($this->link, "SET CHARACTER_SET_CONNECTION=utf8");
        mysqli_query($this->link, "SET SQL_MODE = ''");
    }

    public function query($sql) {
        $resource = mysqli_query($this->link, $sql);

        if ($resource != false) {
            if (($resource !== true) && (get_class($resource) == 'mysqli_result')) {
                $data = array();
                if (function_exists('mysqli_fetch_all')) {
                    $data = mysqli_fetch_all($resource, MYSQLI_ASSOC);
                } else {
                    $i = 0;
                    while ($result = mysqli_fetch_assoc($resource)) {
                        $data[$i] = $result;
                        $i++;
                    }
                }

                $query = new stdClass();
                $query->row = isset($data[0]) ? $data[0] : array();
                $query->rows = $data;
                $query->num_rows = mysqli_num_rows($resource);

                mysqli_free_result($resource);
                unset($data);

                return $query;
            } else {
                return true;
            }
        } else {
            trigger_error('Error: ' . mysqli_error($this->link) . '<br />Error No: ' . mysqli_errno($this->link)
            . '<br />' . $sql);
            exit();
        }
    }

    public function escape($value) {
        return mysqli_real_escape_string($this->link, $value);
    }

    public function countAffected() {
        return mysqli_affected_rows($this->link);
    }

    public function getLastId() {
        return mysqli_insert_id($this->link);
    }

    public function getServerInfo() {
        return 'MySQL v ' . mysqli_get_server_info($this->link);
    }

    public function __destruct() {
        mysqli_close($this->link);
    }
}