<?php

final class QOllyxar {
    const version = '2.0.1';
    public $config;
    public $db;
    public $languages = array();
    public $document;
    public $uri;
    public $url;
    public $host;
    public $user;
    public $ERROR_404 = true;
    public $modules = array();
    public $cache;
    private $available_modules = array();
    private $modules_in_position = array();

    public function __construct($dont_load_modules = false) {
        $this->db = new DB(DB_DRIVER, DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $this->db_queries = 0;
        $_GET['route'] = isset($_GET['route']) ? $_GET['route'] : '';
        $_GET['_route_'] = isset($_GET['_route_']) ? $_GET['_route_'] : '';
        $languages = $this->db->query("SELECT id, name, description, picture, ordering FROM " . DB_PREF . "lang_details ORDER BY 'ordering' ASC")->rows;
        foreach ($languages as $language) {
            $this->languages[$language['name']] = array(
                'name'          => $language['name'],
                'id'            => $language['id'],
                'description'   => $language['description'],
                'picture'       => $language['picture'],
                'ordering'      => $language['ordering']
            );
        }
        $this->document = new QDocument();
        $this->uri = $_SERVER["REQUEST_URI"];
        $this->host = $_SERVER["HTTP_HOST"];
        $_SESSION['lang'] = isset($_SESSION['lang']) ? $_SESSION['lang'] : DEF_LANG;
        $this->cache = new QCache();
        $this->url = new QUrl($this);
        $this->user = new QUser($this);
        $this->__loadModules($dont_load_modules);
    }

    public static function getVersion() {
        return self::version;
    }

    public function loadConfig($path) {
        $this->config = new QConfig($path);
    }

    private function __loadModules($load_and_init = false) {
        if ($load_and_init) {
            $modules = $this->db->query("SELECT * FROM " . DB_PREF . "modules ORDER BY ordering")->rows;
        } else {
            $modules = $this->db->query("SELECT * FROM " . DB_PREF . "modules WHERE enabled=1 ORDER BY ordering")->rows;
        }
        foreach ($modules as $module) {
            $this->modules_in_position[$module['position']][$module['id']] = $module['name'];
            $this->available_modules[$module['name']] = array(
                'id'        => $module['id'],
                'name'      => $module['name'],
                'route'     => ($module['route'] != '') ? explode(',', $module['route']) : array('__all'),
                'params'    => (@unserialize($module['params']) !== false) ? unserialize($module['params']) : array(),
                'rr'        => $module['rr'],
                'rw'        => $module['rw'],
                'enabled'   => $module['enabled'],
                'has_ui'    => $module['has_ui'],
                'description' => $module['description']
            );
            if ($load_and_init) {
                $this->appendModule($module['name']);
            }
        }
    }

    public function loadModules($position) {
        $modules = isset($this->modules_in_position[$position]) ? $this->modules_in_position[$position] : array();
        foreach ($modules as $module) {
            if (isset($this->modules[$module])) {
                $this->modules[$module]->output();
            }
        }
    }

    private function appendModule($module_name) {
        if (!isset($this->modules[$module_name])) {
            if (@include(dirname(dirname(dirname(__FILE__))) . '/modules/' . $module_name . '.php')) {
                $this->modules[$module_name] = new $module_name($this);
                $this->modules[$module_name]->params = $this->available_modules[$module_name]['params'];
                $this->modules[$module_name]->route = $this->available_modules[$module_name]['route'];
                $this->modules[$module_name]->adm_access['rr'] = $this->available_modules[$module_name]['rr'];
                $this->modules[$module_name]->adm_access['rw'] = $this->available_modules[$module_name]['rw'];
                $this->modules[$module_name]->enabled = $this->available_modules[$module_name]['enabled'];
                $this->modules[$module_name]->has_ui = (bool)$this->available_modules[$module_name]['has_ui'];
                $this->modules[$module_name]->description = $this->available_modules[$module_name]['description'];
            } else {
                trigger_error("Module " . $module_name . " doesn't load!", E_USER_WARNING);
            }
        }
    }

    private function loadRouters() {
        foreach ($this->available_modules as $module) {
            if (in_array($_GET['route'], $module['route']) || in_array('__all', $module['route'])) {
                $this->url->correctUrl($_GET['_route_']);
                $this->appendModule($module['name']);
                $this->modules[$module['name']]->index();
            }
        }
    }

    public function addLanguage($language) {
        $arr = $this->db->query("SELECT data FROM " . DB_PREF . "settings WHERE id='1'")->row;
        $arr = unserialize($arr['data']);
        $arr['site_name_' . $language]  = $arr['site_name_' . DEF_LANG];
        $arr['site_descr_' . $language] = $arr['site_descr_' . DEF_LANG];
        $arr['site_kw_' . $language]    = $arr['site_kw_' . DEF_LANG];
        $this->db->query("UPDATE " . DB_PREF . "settings SET `data`='" . $this->db->escape(serialize($arr)) . "' WHERE id='1'");
        foreach ($this->modules as $module) {
            $module->addLanguage($language);
        }
    }

    public function removeLanguage($language) {
        foreach ($this->modules as $module) {
            $module->removeLanguage($language);
        }
    }

    public function sendMail($mail_to, $mail_from, $name_from, $mail_subj, $mes_body, $additional_headers = '', $attachment = null, $attachment_name = '') {
        $random_hash = strtoupper(uniqid(time()));
        $name_from = "=?UTF-8?B?" . base64_encode($name_from) . "?=";
        $header = "From: " . $name_from . " <" . $mail_from . "> \n" . "Mime-Version: 1.0\nContent-Type: multipart/mixed;boundary=\"----------" . $random_hash . "\"\n" . $additional_headers;

        $body = "------------" . $random_hash . "\nContent-Type:text/html; charset=\"UTF-8\"\n";
        $body .= "Content-Transfer-Encoding: 8bit\n\n" . $mes_body . "\n\n";
        $body .= "------------" . $random_hash . "\n";
        if (!is_null($attachment)) {
            $body .= "Content-Type: application/octet-stream;";
            $body .= "name=\"" . $attachment_name . "\"\n";
            $body .= "Content-Transfer-Encoding:base64\n";
            $body .= "Content-Disposition:attachment;";
            $body .= "filename=\"" . $attachment_name . "\"\n\n";
            $body .= chunk_split(base64_encode(file_get_contents($attachment))) . "\n";
        }

        if (mail($mail_to, "=?UTF-8?B?" . base64_encode($mail_subj) . "?=", $body, $header)) {
            return true;
        } else return false;
    }

    public function getSetting() {
        $q = $this->db->query("SELECT data FROM " . DB_PREF . "settings WHERE id=1");
        return unserialize($q->row['data']);
    }

    private function callMethod($module_name) {
        $method = substr($_GET['module'], strpos($_GET['module'], '/') + 1);
        $this->appendModule($module_name);
        $this->modules[$module_name]->$method();
    }

    private function publicReloadCaptcha() {
        $captcha = new QCaptcha();
        $_SESSION['captcha'] = $captcha->getCode();
        die(json_encode($captcha->getContent()));
    }

    public function doRoute() {
        // call system methods
        if (isset($_GET['system'])) {
            $method = 'public' . $_GET['system'];
            if (method_exists($this, $method)) {
                $this->$method();
                exit();
            }
        }

        // call method of module if exists
        if (isset($_GET['module'])) {
            $module_name = substr($_GET['module'], 0, strpos($_GET['module'], '/'));
            if ($module_name != '') {
                $this->callMethod($module_name);
                exit();
            }
        }
        // redirecting to seo_lang

        if ((MULTILANG == true) && (SEO_MULTILANG == true) && (CLEAN_URL == true) &&
                (substr($this->uri, strlen(ROOT_DIR), strlen($_SESSION['lang'])) != $_SESSION['lang'] ||
                    (strpos($this->uri, ROOT_DIR . $_SESSION['lang'] . '/') === false))) {
            $must_redirect = true;
            foreach ($this->languages as $lang => $lang_arr) {
                if (substr($this->uri, strlen(ROOT_DIR), strlen($lang)) == $lang && strpos($this->uri, ROOT_DIR . $lang . '/') > -1) {
                    // then user switched language
                    $_SESSION['lang'] = $lang;
                    $must_redirect = false;
                    break;
                }
            }

            if ($must_redirect) {
                // then language does not exists. So redirect to default language
                $this->url->redirect(PRTCL . "://" . $this->host . ROOT_DIR . $_SESSION['lang'] . substr($this->uri, strlen(ROOT_DIR) - 1));
            }
        }

        if ((MULTILANG == true) && (SEO_MULTILANG == true) && (CLEAN_URL == false)) {
            if (!isset($_GET['lang'])) {
                if (strpos($this->uri, '?') > 0) {
                    $this->url->redirect(PRTCL . "://" . $this->host . $this->uri . '&lang=' . $_SESSION['lang']);
                } else {
                    $this->url->redirect(PRTCL . "://" . $this->host . ROOT_DIR . "?lang=" . $_SESSION['lang']);
                }
            }
        }

        if ((MULTILANG == true) && (SEO_MULTILANG == false) && (isset($_POST['lang_post']))) {
            $_SESSION['lang'] = $_POST['lang_name'];
            $this->url->redirect($this->url->full);
        }

        if (MULTILANG == true && CLEAN_URL == false && SEO_MULTILANG == true) {
            foreach ($this->languages as $lang => $lang_arr) {
                if ($_GET['lang'] == $lang) {
                    $_SESSION['lang'] = $lang;
                    break;
                }
            }
        }
		
		// prevent error when language was deleted from site
		$lang_exists = false;
		
		foreach ($this->languages as $lang => $lang_arr) {
			if ($_SESSION['lang'] == $lang) {
				$lang_exists = true;
				break;
			}
		}
		if (!$lang_exists) {
			session_destroy();
			$this->url->redirect($this->url->full);
		}

        // looking for root
        if ($this->uri == ROOT_DIR || (SEO_MULTILANG == true && CLEAN_URL == true &&
                ($this->uri == ROOT_DIR . $_SESSION['lang'] . '/' || strpos($this->uri, $_SESSION['lang'] . '/?') > -1) &&
                substr($this->uri, strlen(ROOT_DIR), strlen($_SESSION['lang'])) == $_SESSION['lang']) ||
                (SEO_MULTILANG == true && CLEAN_URL == false && $this->uri == ROOT_DIR . '?lang=' . $_SESSION['lang'])) {
            $this->ERROR_404 = false;
            $_GET['route'] = $_GET['route'] != '' ? $_GET['route'] : 'home';
        }

        // set default meta
        $settings = $this->getSetting();
        $this->document->setTitle($settings['site_name_' . $_SESSION['lang']]);
        $this->document->setDescription($settings['site_descr_' . $_SESSION['lang']]);
        $this->document->setKeywords($settings['site_kw_' . $_SESSION['lang']]);

        if ($_GET['route'] == 'home') {
            // starting home page from StaticPages.
            // remove code below if you don't need it
            $_GET['page_id'] = 2;
            $this->appendModule('staticpages');
            $this->modules['staticpages']->index();
        }

        $this->loadRouters();

        // rewrite function for 404 THIS CODE MUST BE IN THE BOTTOM OF METHOD!
        if ($this->ERROR_404 || $_GET['route'] == '404') {
            if (USE_404_REDIRECT && $_GET['route'] != '404') {
                $this->url->redirect($this->url->link('route=404'));
            }
            $this->ERROR_404 = false;
            $this->url->is_category = false;
            $_GET['page_id'] = 4;
            $this->modules['staticpages']->index();
            header("HTTP/1.1 404 Not Found");
            header("Status: 404 Not Found");
        }
    }
}

