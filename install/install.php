<?php
// DON'T USE SESSION!

error_reporting(E_ALL);
ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');

// check for existing application
if (file_exists('config.php')) {
    include_once('config.php');
    if (defined('SITE_CODE')) {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/');
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
    file_put_contents('install.tmp', '');

    $targetFile = fopen('quantum.zip', 'w');

    if (version_compare(phpversion(), '5.5.0', '>') == true) {
        function callback($resource, $download_size, $downloaded_size, $upload_size, $uploaded_size) {
            if ($download_size == 0) {
                $download_size = 1;
            }
            $progress = $downloaded_size / $download_size * 100;
            $fp = fopen('install.tmp', 'w');
            fwrite($fp, "$progress");
            fclose($fp);
        }
    } else {
        function callback($download_size, $downloaded_size, $upload_size, $uploaded_size) {
            if ($download_size == 0) {
                $download_size = 1;
            }
            $progress = $downloaded_size / $download_size * 100;
            $fp = fopen('install.tmp', 'w');
            fwrite($fp, "$progress");
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
        $.ajax({
            url: 'install.php',
            type: 'GET',
            dataType: 'json',
            data: 'download=1'
        });
        /* You should continue with timeout because downloading on some server is too fast */
        setTimeout(function() {
            progress = setInterval(function(){downloadTimer()}, 400);
        }, 1000);
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
                        $.ajax({
                            url: 'install.php',
                            type: 'GET',
                            dataType: 'json',
                            data: 'unzip=1'
                        });
                        progress = setInterval(function(){unzipTimer()}, 400);
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
                if (data != '' && data != '100') {
                    clearInterval(progress);
                }
                if (data == 'unzipped') {
                    clearInterval(progress);
                    $('#third-step').animate({
                        opacity: "0"
                    }, 200, function() {
                        $('#third-step').remove();
                        var html = '<div id="fours-step"><h1>Extracted.</h1><p class="caption">Please wait</p>';
                        $('.content').append(html);
                    });
                }
                if (data == 'unzip fail') {
                    clearInterval(progress);
                    $('#third-step').animate({
                        opacity: "0"
                    }, 200, function() {
                        $('#third-step').remove();
                        var html = '<div id="fours-step"><h1>Extract error.</h1><p class="red caption">Error occurred while extracting data.</p>';
                        $('.content').append(html);
                    });
                }
            }
        });
    }
</script>
</body>
</html>