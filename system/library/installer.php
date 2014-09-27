<?php

final class QInstaller {

    const version = '1.3';
    private $root_dir = '../';
    private $language;
    private $engine;

    protected function normalizePath($path) {
        $path = str_replace('{modules}', '/modules/', $path);
        $path = str_replace('{adm_tpl}', ADM_PATH . 'template/', $path);
        $path = str_replace('{adm_units}', ADM_PATH . 'units/', $path);
        $path = str_replace('{adm_language}', ADM_PATH . 'language/', $path);
        $path = str_replace('{template}', '/' . TEMPLATE, $path);
        return $path;
    }

    public function __construct(QOllyxar &$engine, &$language) {
        $this->engine = $engine;
        $this->language = $language;
        $this->root_dir = dirname(dirname(dirname(__FILE__)));
    }

    public function uninstallModule($id) {
        if ((int)$id < 1) {
            $_SESSION['msg'] = 'error_uninstall';
            $_SESSION['msg_text'] = $this->language['mi_wrong_id'];
            return false;
        }
        $q = $this->engine->db->query("SELECT `name` FROM " . DB_PREF . "modules WHERE id=" . (int)$id);
        if (!isset($q->row['name'])) {
            $_SESSION['msg'] = 'error_uninstall';
            $_SESSION['msg_text'] = $this->language['mi_no_module'];
            return false;
        }
        $m_name = $q->row['name'];
        if (file_exists($this->root_dir . '/modules/app_data/' . $m_name . '/qms.ini')) {
            $ini = parse_ini_file($this->root_dir . '/modules/app_data/' . $m_name . '/qms.ini', true);
            for ($i = 1; $i <= $ini['setup']['files_count']; $i++) {
                if (file_exists($this->root_dir . $this->normalizePath($ini['file_' . $i]['dest'])) && (!$ini['file_' . $i]['dont_uninstall'])) {
                    @unlink($this->root_dir . $this->normalizePath($ini['file_' . $i]['dest']));
                }
            }
        }
        $this->engine->db->query("DELETE FROM " . DB_PREF . "modules WHERE id=" . $id);
        $_SESSION['msg'] = 'uninstalled';
        return true;
    }

    public function installModule($filename, $path) {
        // unpacking data
        $zip = new ZipArchive;
        $res = $zip->open($filename);
        if ($res === true) {
            $zip->extractTo($path);
            $zip->close();
            $ini = parse_ini_file($path . '/qms.ini', true);
            if (!$ini) {
                $_SESSION['msg'] = 'error_install';
                $_SESSION['msg_text'] = $this->language['no_install_instruct'];
                return false;
            } else {
                // do installation
                if (self::version <> $ini['setup']['installer_version']) {
                    $_SESSION['msg'] = 'error_install';
                    $_SESSION['msg_text'] = $this->language['mi_different_ver'];
                    return false;
                }
                $m_name = pathinfo($filename, PATHINFO_FILENAME);
                if (isset($this->engine->modules[$m_name]) && (version_compare($this->engine->modules[$m_name]->getVersion(), $ini['setup']['module_version'], '>=') == true)) {
                    $_SESSION['msg'] = 'error_install';
                    $_SESSION['msg_text'] = $this->language['mi_already_installed'];
                    return false;
                }
                $updating = false;
                if (isset($this->engine->modules[$m_name]) && (version_compare($this->engine->modules[$m_name]->getVersion(), $ini['setup']['module_version'], '<') == true)) {
                    $updating = true;
                }
                try {
                    for ($i = 1; $i <= $ini['setup']['files_count']; $i++) {
                        if (file_exists($this->root_dir . $this->normalizePath($ini['file_' . $i]['dest'])) && $ini['file_' . $i]['skip_if_exists']) {
                            @unlink($path . '/' . $ini['file_' . $i]['src']);
                            continue;
                        }
                        if (!copy($path . '/' . $ini['file_' . $i]['src'], $this->root_dir . $this->normalizePath($ini['file_' . $i]['dest']))) {
                            $_SESSION['msg'] = 'error_install';
                            $_SESSION['msg_text'] = $this->language['mi_cant_copy'] . $this->root_dir . $this->normalizePath($ini['file_' . $i]['dest']);
                            return false;
                        }
                        @unlink($path . '/' . $ini['file_' . $i]['src']);
                    }
                    $params = array();
                    for ($i = 1; $i <= $ini['setup']['params_count']; $i++) {
                        if ($ini['param_' . $i]['is_multilang']) {
                            foreach ($this->engine->languages as $lang) {
                                $params[$ini['param_' . $i]['name'] . '_' . $lang['name']] = $ini['param_' . $i]['def_value'];
                            }
                        } else {
                            $params[$ini['param_' . $i]['name']] = $ini['param_' . $i]['def_value'];
                        }
                    }
                    if ($updating) {
                        $this->engine->db->query("UPDATE " . DB_PREF . "modules SET `name`='" . $m_name . "', `position`='" . $this->engine->db->escape($ini['setup']['position']) . "', `route`='" . $this->engine->db->escape($ini['setup']['route']) . "', `description`='" . $this->engine->db->escape($ini['setup']['description']) . "', `params`='" . $this->engine->db->escape(serialize($params)) . "', `has_ui`='" . (int)$ini['setup']['has_ui'] . "' WHERE name='" . $m_name . "';");
                    } else {
                        $this->engine->db->query("INSERT INTO " . DB_PREF . "modules (`name`, `position`, `route`, `description`, `params`, `rr`, `rw`, `has_ui`, `enabled`, `ordering`) VALUES ('" . $m_name . "', '" . $this->engine->db->escape($ini['setup']['position']) . "', '" . $this->engine->db->escape($ini['setup']['route']) . "', '" . $this->engine->db->escape($ini['setup']['description']) . "', '" . $this->engine->db->escape(serialize($params)) . "', '2', '2', '" . (int)$ini['setup']['has_ui'] . "', '0', '1');");
                    }

                    if (file_exists($path . '/install.sql')) {
                        $this->engine->db->query($this->engine->db->escape(file_get_contents($path . '/install.sql')));
                        @unlink($path . '/install.sql');
                    }
                    $_SESSION['msg'] = 'success';
                    $_SESSION['msg_text'] = $this->language['changes_applied'];
                    return true;
                } catch (Exception $e) {
                    $_SESSION['msg'] = 'error_install';
                    $_SESSION['msg_text'] = $e->getMessage();
                    return false;
                }
            }
        } else {
            $_SESSION['msg'] = 'error_install';
            $_SESSION['msg_text'] = $this->language['cant_unpack'];
            return false;
        }
    }
}