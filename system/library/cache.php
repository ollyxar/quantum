<?php
class QCache {
    private $expire = CACHE_TIME;
    public $path_to_system = '';

    public function get($key) {
        $data = false;
        $files = glob($this->path_to_system . 'system/cache/cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');
        if ($files) {
            $cache = file_get_contents($files[0]);
            $data = unserialize($cache);
            foreach ($files as $file) {
                $time = substr(strrchr($file, '.'), 1);
                if ($time < time()) {
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
            }
        }
        return $data;
    }

    public function set($key, $value) {
        $this->delete($key);
        $file = $this->path_to_system . 'system/cache/cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.' . (time() + $this->expire);
        $handle = fopen($file, 'w');
        fwrite($handle, serialize($value));
        fclose($handle);
    }

    public function delete($key) {
        $files = glob($this->path_to_system . 'system/cache/cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');
        if ($files) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }
    }
}