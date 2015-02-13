<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang'] ?>">
<head>
    <title><?php echo $language['cms-title']; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico" />
    <link href="template/css/bootstrap.css" rel="stylesheet" media="screen">
    <script src="template/js/jquery-1.8.1.min.js"></script>
    <script src="template/js/jquery-ui-1.8.16.custom.min.js"></script>
    <link rel="stylesheet" href="template/css/font-awesome.css">
    <!--[if IE 7]>
    <link rel="stylesheet" href="template/css/font-awesome-ie7.css">
    <![endif]-->
</head>
<body>
<div class="container admin" style="display: none">
    <div class="content">
        <div class="logo"></div>
        <div id="adm"  class="login-form clearfix">
            <form method="POST">
                <div class="clearfix margin-top input-prepend">
                    <span class="add-on"><i class="icon-user"></i></span>
                    <input type="text" name="username" placeholder="<?php echo $language['log_in']; ?>">
                </div>
                <div class="clearfix margin-top input-prepend">
                    <span class="add-on"><i class="icon-key"></i></span>
                    <input type="password" name="password" placeholder="<?php echo $language['password']; ?>">
                </div>
                <div class="clearfix margin-top input-prepend">
                    <span class="add-on"><i class="icon-user-md"></i></span>
                    <select name="lang">
                        <?php foreach ($adm_languages as $lang) { ?>
                        <option <?php if (isset($_COOKIE['lang']) && $_COOKIE['lang'] == $lang) echo 'selected="selected"' ?> value="<?php echo $lang ?>"><?php echo isset($ld[$lang]) ? $ld[$lang] : $lang ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="clearfix margin-top-button pull-right" style="margin-top: 9px;">
                    <input class="btn btn-primary" type="submit" name="enter" value="<?php echo $language['enter']; ?>">
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.admin').fadeIn(900);
    });
</script>
</body>
</html>