<?php

function magicQuotesFix() {
    if (ini_get('magic_quotes_gpc')) {
        function clean($data) {
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    $data[clean($key)] = clean($value);
                }
            } else {
                $data = stripslashes($data);
            }
            return $data;
        }

        $_GET = clean($_GET);
        $_POST = clean($_POST);
        $_REQUEST = clean($_REQUEST);
        $_COOKIE = clean($_COOKIE);
    }
}

function compress($buffer) {
    //$buffer = str_replace("\r\n", "", $buffer);
    $buffer = preg_replace('/[\s]{2,}/', ' ', $buffer);
    return $buffer;
}

function getPermission($filename) {
    return substr(sprintf('%o', fileperms($filename)), -4);
}

function mysqlImport($mysqlDatabaseName, $mysqlUserName, $mysqlPassword, $mysqlHostName, $mysqlImportFilename) {
    $command = 'mysql -h' . $mysqlHostName . ' -u' . $mysqlUserName . ' -p' . $mysqlPassword . ' ' .
        $mysqlDatabaseName . ' < ' . $mysqlImportFilename;
    $output = array();
    exec($command, $output, $result);
    if ($result == 0) {
        return true;
    } else {
        return false;
    }
}

function mysqlExport($mysqlDatabaseName, $mysqlUserName, $mysqlPassword, $mysqlHostName, $mysqlExportPath) {
    $command = 'mysqldump --opt -h' . $mysqlHostName . ' -u' . $mysqlUserName . ' -p' . $mysqlPassword .
        ' ' . $mysqlDatabaseName . ' > ' . $mysqlExportPath;
    $output = array();
    exec($command, $output, $result);
    if ($result == 0) {
        return true;
    } else {
        return false;
    }
}

function zip($source, $destination) {
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source),
            RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file) {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if (in_array(substr($file, strrpos($file, '/') + 1), array('.', '..')))
                continue;

            $file = realpath($file);

            if (is_dir($file) === true) {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            } else if (is_file($file) === true) {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    } else if (is_file($source) === true) {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}

function str_replace_once($search, $replace, $text) {
    $pos = strpos($text, $search);
    return $pos !== false ? substr_replace($text, $replace, $pos, strlen($search)) : $text;
}

function makeRandomString($max = 9) {
    $i = 0;
    $possible_keys = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $keys_length = strlen($possible_keys);
    $str = "";
    while ($i < $max) {
        $rand = mt_rand(1, $keys_length - 1);
        $str .= $possible_keys[$rand];
        $i++;
    }
    return $str;
}

function printPagination($page_count, $url, $centered = true, $backend = false) {
    $url = str_replace('page_n=' . $_GET['page_n'], '', $url);
    $url = isset($_GET['lang']) ? str_replace('&lang=' . $_GET['lang'], '', $url) : $url;
    if (strpos($url, '?') === false) {
        $url .= '?';
    } elseif (substr($url, -1) != '?') {
        $url .= '&';
    }
    if ($page_count == 0) $page_count = 1;
    if ($backend) {
        if ($centered) $cls = ' pagination-centered'; else $cls = '';
        $tmp_res = '<div class="pagination' . $cls . '">
        <ul>';
    } else {
        if ($centered) $cls = ' center-block text-center'; else $cls = '';
        $tmp_res = '<div class="' . $cls . '">
        <ul class="pagination">';
    }
    $tmp_res .= '<li ';
    if ($_GET["page_n"] == 1) $tmp_res .= 'class="disabled"';
    $tmp_res .= '><a ';
    if ($_GET["page_n"] > 1) {
        if ($_GET["page_n"] == 2) {
            $tmp_res .= 'href="' . substr($url, 0, -1) . '"';
        } else {
            $tmp_res .= 'href="' . $url . 'page_n=' . ($_GET["page_n"] - 1) . '"';
        }
    }
    $tmp_res .= '>&laquo;</a></li><li ';
    if ($_GET["page_n"] == 1) $tmp_res .= 'class="active"';
    $tmp_res .= '><a ';
    if ($_GET["page_n"] > 1) $tmp_res .= 'href="' . substr($url, 0, -1) . '"';
    $tmp_res .= '>1</a></li>';
    if ($page_count <= 5 && $_GET["page_n"] <> 2 && $page_count > 2) $tmp_res .= '<li><a href="' . $url . 'page_n=2">2</a></li>';
    if ($page_count <= 5 && $_GET["page_n"] > 3 && $page_count > 3) $tmp_res .= '<li><a href="' . $url . 'page_n=3">3</a></li>';
    if ($page_count <= 5 && $_GET["page_n"] > 4 && $page_count > 4) $tmp_res .= '<li><a href="' . $url . 'page_n=4">4</a></li>';
    if ($page_count > 5 && $_GET["page_n"] > 2) $tmp_res .= '<li class="disable"><a>..</a></li>';
    if ($_GET["page_n"] > 1) $tmp_res .= '<li class="active"><a>' . $_GET["page_n"] . '</a></li>';
    if ($page_count <= 5 && $_GET["page_n"] < 3 && $page_count > 3) $tmp_res .= '<li><a href="' . $url . 'page_n=3">3</a></li>';
    if ($page_count <= 5 && $_GET["page_n"] < 4 && $page_count > 4) $tmp_res .= '<li><a href="' . $url . 'page_n=4">4</a></li>';
    if ($page_count > 5 && $_GET["page_n"] < ($page_count - 1)) $tmp_res .= '<li class="disable"><a>..</a></li>';
    if ($page_count > 1 && $_GET["page_n"] <> $page_count) $tmp_res .= '<li><a href="' . $url . 'page_n=' . $page_count . '">' . $page_count . '</a></li>';
    $tmp_res .= '<li ';
    if ($page_count == 1 || $_GET["page_n"] == $page_count) $tmp_res .= 'class="disabled"';
    $tmp_res .= '><a ';
    if ($_GET["page_n"] < $page_count) $tmp_res .= 'href="' . $url . 'page_n=' . ($_GET["page_n"] + 1) . '"';
    $tmp_res .= '>&raquo;</a></li></ul></div>';
    return $tmp_res;
}

function captionToLink($str) {
    $str = htmlspecialchars(strip_tags(trim($str)));
    $str = str_replace('.', '-', $str);
    $str = str_replace(',', '-', $str);
    $str = str_replace('!', '-', $str);
    $str = str_replace(';', '-', $str);
    $str = str_replace(' ', '-', $str);
    $str = str_replace('"', '-', $str);
    $str = str_replace("'", '-', $str);
    $str = str_replace('&', '-', $str);
    $str = str_replace('<', '-', $str);
    $str = str_replace('>', '-', $str);
    $str = str_replace('/', '-', $str);
    $str = str_replace('?', '-', $str);
    $str = str_replace('+', '-', $str);
    $str = str_replace('=', '-', $str);
    $str = str_replace('^', '-', $str);
    $str = str_replace(':', '-', $str);
    $str = str_replace('`', '-', $str);
    $str = str_replace('%', '-', $str);
    $str = str_replace('$', '-', $str);
    $str = str_replace('#', '-', $str);
    $str = str_replace('*', '-', $str);
    $str = str_replace('(', '-', $str);
    $str = str_replace(')', '-', $str);
    $str = str_replace('[', '-', $str);
    $str = str_replace(']', '-', $str);
    $str = str_replace('{', '-', $str);
    $str = str_replace('}', '-', $str);
    $str = str_replace('\\', '-', $str);
    $str = str_replace('|', '-', $str);
    $str = str_replace('@', '-', $str);
    return strtolower($str);
}

function resizeImage($filename, $width, $height, $crop = true, $use_urldecode = true, $noimage = '/upload/images/no-image.jpg') {
    $root_dir = dirname(dirname(__FILE__)) . ROOT_DIR;
    if ($use_urldecode) {
        $filename = urldecode($filename);
    }
    if (!file_exists($root_dir . $filename) || !is_file($root_dir . $filename)) {
        if ($noimage == '') {
            return null;
        } else {
            $filename = $noimage;
        }
    }

    $info = pathinfo($filename);
    $extension = $info['extension'];

    $in_name = $crop ? '' : 'a';

    $old_image = $filename;
    $new_image = str_replace('upload/images', 'upload/cache/images', substr($filename, 0, strrpos($filename, '.')) . '-' . $width . 'x' . $height . $in_name . '.' . $extension);

    if (!file_exists($root_dir . $new_image) || (filemtime($root_dir . $old_image) > filemtime($root_dir . $new_image))) {
        $path = '';

        $directories = explode('/', dirname(str_replace('../', '', $new_image)));

        foreach ($directories as $directory) {
            $path = $path . '/' . $directory;

            if (!file_exists($root_dir . $path)) {
                @mkdir($root_dir . $path, 0777);
            }
        }

        $image = new QImage($root_dir . $old_image);
        $image->resize($width, $height, $crop);
        $image->save($root_dir . $new_image);
        unset($image);
    }

    return $new_image;
}