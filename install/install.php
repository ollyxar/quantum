<?php
// DON'T USE SESSION!

error_reporting(E_ALL);
ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');

// check for existing application
if (file_exists('config.php')) {
    include_once('config.php');
    if (defined('SITE_CODE')) {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . ROOT_DIR);
        exit();
    }
}

function compatibilityCheck() {
    $error = '';
    if (!function_exists('curl_init')) {
        $error .= 'Extension CURL does not installed!<br />';
    }
    if (!class_exists('ZipArchive')) {
        $error .= 'Extension for Zip-archives does not installed!<br />';
    }
    return $error;
}

if (isset($_GET['unzip'])) {
    $zip = new ZipArchive;
    $res = $zip->open('quantum.zip');
    if ($res === true) {
        $zip->extractTo(getcwd());
        $zip->close();
        file_put_contents('install.tmp', 'unzipped');
    } else {
        file_put_contents('install.tmp', 'unzip fail');
    }
}

if (isset($_GET['download'])) {
    @unlink('quantum.zip');
    file_put_contents('install.tmp', '');

    $targetFile = fopen('quantum.zip', 'w');

    if (version_compare(phpversion(), '5.5.0', '>') == true) {
        function callback($resource, $download_size, $downloaded_size, $upload_size, $uploaded_size) {
            if ($download_size == 0) {
                $download_size = 1;
            }
            $progress = $downloaded_size / $download_size * 100;
            $fp = fopen('install.tmp', 'w');
            fwrite($fp, $progress);
            fclose($fp);
        }
    } else {
        function callback($download_size, $downloaded_size, $upload_size, $uploaded_size) {
            if ($download_size == 0) {
                $download_size = 1;
            }
            $progress = $downloaded_size / $download_size * 100;
            $fp = fopen('install.tmp', 'w');
            fwrite($fp, $progress);
            fclose($fp);
        }
    }

    $ch = curl_init('http://update.ollyxar.com/quantum/quantum.zip');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOPROGRESS, false);
    curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'callback');
    curl_setopt($ch, CURLOPT_FILE, $targetFile);
    curl_exec($ch);
    fclose($targetFile);
}

if (isset($_POST['get_status'])) {
    die(json_encode(file_get_contents('install.tmp')));
}

if (isset($_POST['db_host'])) {
    $db_host = isset($_POST['db_host']) ? $_POST['db_host'] : '';
    $db_name = isset($_POST['db_name']) ? $_POST['db_name'] : '';
    $db_user = isset($_POST['db_user']) ? $_POST['db_user'] : '';
    $db_pass = isset($_POST['db_pass']) ? $_POST['db_pass'] : '';
    $db_pref = isset($_POST['db_pref']) ? $_POST['db_pref'] : '';

    $link = false;
    if (!$link = @mysqli_connect($db_host, $db_user, $db_pass, $db_name)) {
        die(json_encode(0));
    }
    $name   = isset($_POST['name']) ? mysqli_real_escape_string($link, trim($_POST['name'])) : '';
    $email  = isset($_POST['email']) ? $_POST['email'] : '';
    $pass   = isset($_POST['pass']) ? md5(md5($_POST['pass'])) : '';
    $config = "<?php

// define constants
// Warning! Use only single quotes!
define('SITE_CODE', 'quantum');
define('ROOT_DIR', '/');
define('CLEAN_URL', true);
define('MULTILANG', true);
define('SEO_MULTILANG', true);
define('USE_404_REDIRECT', false);
define('USE_COMPRESSION', true);
define('DEF_LANG', 'en');
define('PAGE_SUFFIX', '.html');
define('DB_DRIVER', 'mysql_i');
define('DB_HOST', '" . $db_host . "');
define('DB_NAME', '" . $db_name . "');
define('DB_USER', '" . $db_user . "');
define('DB_PASS', '" . $db_pass . "');
define('DB_PREF', '');
define('TEMPLATE', 'view/default/');
define('ADM_PATH', '/admin/');
define('CACHE_TIME', 1800);
define('EMAIL', '" . $email . "');
define('PRTCL', 'http');";
    file_put_contents('config.php', $config);
    $db_query = file_get_contents('install.sql');
    $db_query = str_replace("CREATE TABLE IF NOT EXISTS `lang_details`", "CREATE TABLE IF NOT EXISTS `" . $db_pref . "lang_details`", $db_query);
    $db_query = str_replace("INSERT INTO `lang_details`", "INSERT INTO `" . $db_pref . "lang_details`", $db_query);
    $db_query = str_replace("CREATE TABLE IF NOT EXISTS `main_menu`", "CREATE TABLE IF NOT EXISTS `" . $db_pref . "main_menu`", $db_query);
    $db_query = str_replace("INSERT INTO `main_menu`", "INSERT INTO `" . $db_pref . "main_menu`", $db_query);
    $db_query = str_replace("CREATE TABLE IF NOT EXISTS `materials`", "CREATE TABLE IF NOT EXISTS `" . $db_pref . "materials`", $db_query);
    $db_query = str_replace("INSERT INTO `materials`", "INSERT INTO `" . $db_pref . "materials`", $db_query);
    $db_query = str_replace("CREATE TABLE IF NOT EXISTS `modules`", "CREATE TABLE IF NOT EXISTS `" . $db_pref . "modules`", $db_query);
    $db_query = str_replace("INSERT INTO `modules`", "INSERT INTO `" . $db_pref . "modules`", $db_query);
    $db_query = str_replace("CREATE TABLE IF NOT EXISTS `settings`", "CREATE TABLE IF NOT EXISTS `" . $db_pref . "settings`", $db_query);
    $db_query = str_replace("INSERT INTO `settings`", "INSERT INTO `" . $db_pref . "settings`", $db_query);
    $db_query = str_replace("CREATE TABLE IF NOT EXISTS `site_reviews`", "CREATE TABLE IF NOT EXISTS `" . $db_pref . "site_reviews`", $db_query);
    $db_query = str_replace("INSERT INTO `site_reviews`", "INSERT INTO `" . $db_pref . "site_reviews`", $db_query);
    $db_query = str_replace("CREATE TABLE IF NOT EXISTS `static_pages`", "CREATE TABLE IF NOT EXISTS `" . $db_pref . "static_pages`", $db_query);
    $db_query = str_replace("INSERT INTO `static_pages`", "INSERT INTO `" . $db_pref . "static_pages`", $db_query);
    $db_query = str_replace("CREATE TABLE IF NOT EXISTS `url_alias`", "CREATE TABLE IF NOT EXISTS `" . $db_pref . "url_alias`", $db_query);
    $db_query = str_replace("INSERT INTO `url_alias`", "INSERT INTO `" . $db_pref . "url_alias`", $db_query);
    $db_query = str_replace("CREATE TABLE IF NOT EXISTS `users`", "CREATE TABLE IF NOT EXISTS `" . $db_pref . "users`", $db_query);
    $db_query = str_replace("INSERT INTO `users`", "INSERT INTO `" . $db_pref . "users`", $db_query);
    $db_query = str_replace("CREATE TABLE IF NOT EXISTS `user_group`", "CREATE TABLE IF NOT EXISTS `" . $db_pref . "user_group`", $db_query);
    $db_query = str_replace("INSERT INTO `user_group`", "INSERT INTO `" . $db_pref . "user_group`", $db_query);
    mysqli_query($link, $db_query);
    mysqli_query($link, "UPDATE " . $db_pref . "users SET name = '" . $name . "', password = '" . $pass . "', email = '" . $email . "' WHERE id = '1'");
    die(json_encode(1));
}

?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>QUANTUM Install</title>
    <?php include_once('media.tpl') ?>
</head>
<body>
<div class="content">
    <div class="logo"></div>
    <div id="first-step">
        <h1>Welcome to QUANTUM Setup Wizard</h1>
        <?php if (compatibilityCheck() === '') { ?>
        <p class="caption">Press &quot;Install&quot; button to continue installation</p>
        <input class="btn centered" id="install-button" value="Install" type="button">
        <?php } else { ?>
            <div class="red caption">Sorry. Current server settings does not allow automatic installation.<br /><?php echo compatibilityCheck() ?></div>
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.content').fadeIn(900);
    });

    var progress = null;

    $('#install-button').click(function() {
        $('.logo').animate({
            left: "-520px"
        }, 500, function() {
            $(this).animate({
                height: "0px"
            }, 500, function() {
                $('#first-step').animate({
                    opacity: "0"
                }, 200, function() {
                    $(this).remove();
                    var html = '<div id="second-step"><h1>Downloading package...</h1><p class="caption">Please wait</p>';
                    html += '<div class="progress"><div style="width: 0" class="progress-bar progress-bar-striped active"></div></div></div>';
                    $('.content').append(html);
                });
            });
        });
        /* You should continue with timeout because downloading on some server is too fast */
        setTimeout(function() {
            progress = setInterval(function(){downloadTimer()}, 400);
        }, 1000);
        $.ajax({
            url: 'install.php',
            type: 'GET',
            dataType: 'json',
            data: 'download=1'
        });
    });

    function downloadTimer() {
        $.ajax({
            url: 'install.php',
            type: 'POST',
            dataType: 'json',
            data: 'get_status=1',
            success: function(data) {
                if (data != '') {
                    console.log(data);
                    $('.progress-bar.active').css('width', data + '%');
                }
                if (data == '100') {
                    clearInterval(progress);
                    $('#second-step').animate({
                        opacity: "0"
                    }, 200, function() {
                        $(this).remove();
                        var html = '<div id="third-step"><h1>Extracting data...</h1><p class="caption">Please wait</p>';
                        html += '<div class="unzip"></div></div>';
                        $('.content').append(html);
                        progress = setInterval(function() {
                            unzipTimer();
                        }, 400);
                        $.ajax({
                            url: 'install.php',
                            type: 'GET',
                            dataType: 'json',
                            data: 'unzip=1'
                        });
                    });
                }
            }
        });
    }
    function unzipTimer() {
        $.ajax({
            url: 'install.php',
            type: 'POST',
            dataType: 'json',
            data: 'get_status=1',
            success: function(data) {
                if (data == 'unzipped') {
                    clearInterval(progress);
                    $('#third-step').animate({
                        opacity: "0"
                    }, 200, function() {
                        $('#third-step').remove();
                        var html = '<div id="fours-step"><h1>Configuration.</h1><p class="caption">Please configure your project:</p>';
                        html += '<div style="margin: 0 auto; width: 580px"><div style="float:left"><form id="config"><table>' +
                        '<tr><td>DB host:</td><td><input type="text" name="db_host" value="localhost"></td></tr>' +
                        '<tr><td>DB name:</td><td><input type="text" name="db_name"></td></tr>' +
                        '<tr><td>DB prefix:</td><td><input type="text" name="db_pref"></td></tr>' +
                        '<tr><td>DB user:</td><td><input type="text" name="db_user"></td></tr>' +
                        '<tr><td>DB password:</td><td><input type="text" name="db_pass"></td></tr>' +
                        '</table></div><div style="float:right"><table>' +
                        '<tr><td>Admin name:</td><td><input type="text" name="name" value="admin"></td></tr>' +
                        '<tr><td>Admin password:</td><td><input type="text" name="pass" value="1111"></td></tr>' +
                        '<tr><td>Admin email:</td><td><input type="text" name="email" value="user@example.com"></td></tr>' +
                        '</table></form></div></div><div style="clear:both; margin-bottom: 10px"></div>' +
                        '<input class="btn centered" id="save" value="Finish" type="button">' +
                        '</div>';
                        $('.content').append(html);
                    });
                }
                if (data == 'unzip fail') {
                    clearInterval(progress);
                    $('#third-step').animate({
                        opacity: "0"
                    }, 200, function() {
                        $('#third-step').remove();
                        var html = '<div id="fours-step"><h1>Extract error.</h1><p class="red caption">Error occurred while extracting data.</p></div>';
                        $('.content').append(html);
                    });
                }
            },
            error: function() {
                console.log('error on trying get status for unzip progress');
            }
        });
    }
    $('#save').live('click', function() {
        $.ajax({
            url: 'install.php',
            type: 'POST',
            dataType: 'json',
            data: $('#config').serialize(),
            beforeSend: function() {
                $('.red').remove();
            },
            success: function(data) {
                if (data == 0) {
                    $('.content').append('<div class="red caption">Database connection error</div>');
                } else {
                    window.location.reload();
                }
            }
        })
    })
</script>
</body>
</html>