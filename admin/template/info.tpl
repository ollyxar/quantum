<div class="row-fluid">
    <div class="span12">
        <div class="span7">
            <div class="nav-users clearfix">
                <h4><?php echo $language['quick_menu'] ?></h4>
                <div class="pull-left"><a href="index.php?page=materials"
                                          class="materials-icon"><?php echo $language['materials'] ?></a></div>
                <div class="pull-left"><a href="index.php?page=staticpages"
                                          class="static-pages"><?php echo $language['static_pages'] ?></a></div>
                <?php if ($_SESSION['access'] <= 2) { ?>
                    <div class="pull-left"><a href="index.php?page=users"
                                              class="user-icon"><?php echo $language['users'] ?></a></div>
                    <div class="pull-left"><a href="index.php?page=control"
                                              class="settings"><?php echo $language['settings'] ?></a></div>
                <?php } ?>
            </div>
        </div>
        <div class="span5">
            <div class="hero-unit" style="font-size: 14px">
                <div class="last-user-visit">
                    <table class="table table-hover">
                        <caption><h4><?php echo $language['last_visitors'] ?></h4></caption>
                        <thead>
                        <tr>
                            <th><?php echo $language['id'] ?></th>
                            <th><?php echo $language['name'] ?></th>
                            <th><?php echo $language['group'] ?></th>
                            <th><?php echo $language['time'] ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($last_visitors as $id => $last_visitor) { ?>
                            <tr class="success">
                                <td><?php echo $id ?></td>
                                <td><?php echo $last_visitor['name'] ?></td>
                                <td><?php echo $last_visitor['description'] ?></td>
                                <td><?php echo $last_visitor['adm_last_login'] ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-bordered table-striped table-hover">
        <thead>
        <tr>
            <th colspan="2" style="text-align: center;"><?php echo $language['info'] ?></th>
        </tr>
        </thead>
        <tr>
            <td><?php echo $language['engine_version'] ?>:</td>
            <td><?php echo $engine->getVersion() ?></td>
        </tr>
        <tr>
            <td><?php echo $language['avail_langs'] ?>:</td>
            <td>
                <table class="table-bordered">
                    <?php foreach ($engine->languages as $lang)
                        echo '
                    <tr>
                        <td>' . $lang['description'] . "</td>
                    </tr>
                    "; ?>
                </table>
            </td>
        </tr>
        <tr>
            <td><?php echo $language['display_errors'] ?>:</td>
            <td><?php echo ini_get('display_errors') ?></td>
        </tr>
        <tr>
            <td><?php echo $language['magic_quotes_gpc'] ?>:</td>
            <td><?php echo ini_get('magic_quotes_gpc') ? ini_get('magic_quotes_gpc') : ' - ' ?></td>
        </tr>
        <tr>
            <td><?php echo $language['register_globals'] ?>:</td>
            <td><?php echo ini_get('register_globals') ? ini_get('register_globals') : ' - ' ?></td>
        </tr>
        <tr>
            <td><?php echo $language['session_life_time'] ?>:</td>
            <td><?php echo ini_get('session.gc_maxlifetime') ?> sec</td>
        </tr>
        <tr>
            <td><?php echo $language['post_max_size'] ?>:</td>
            <td><?php echo ini_get('post_max_size') ?></td>
        </tr>
        <tr>
            <td><?php echo $language['memory_limit'] ?>:</td>
            <td><?php echo ini_get('memory_limit') ?></td>
        </tr>
        <tr>
            <td><?php echo $language['operating_system'] ?>:</td>
            <td><?php echo php_uname() ?></td>
        </tr>
        <tr>
            <td><?php echo $language['php_version'] ?>:</td>
            <td><?php echo phpversion() ?></td>
        </tr>
        <tr>
            <td><?php echo $language['db_info'] ?>:</td>
            <td><?php echo $engine->db->getServerInfo() ?></td>
        </tr>
        <tr>
            <td><?php echo $language['server_software'] ?></td>
            <td><?php echo $_SERVER['SERVER_SOFTWARE'] ?></td>
        </tr>
        <tr>
            <td><?php echo $language['gateway_interface'] ?></td>
            <td><?php echo $_SERVER['GATEWAY_INTERFACE'] ?></td>
        </tr>
    </table>
</div>