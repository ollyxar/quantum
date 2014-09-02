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
    public $modules;
    public $a_modules = array();
    public $cache;
    private $__modules;

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

    private function __loadModules($dont_load_modules = false) {
        if ($dont_load_modules) {
            return false;
        } else {
            $modules = $this->db->query("SELECT * FROM " . DB_PREF . "modules WHERE enabled=1 ORDER BY ordering")->rows;
            foreach ($modules as $module) {
                $this->__modules[$module['position']][$module['id']] = $module['name'];
                if (@include('modules/' . $module['name'] . '.php')) {
                    $this->modules[$module['name']] = new $module['name']($this);
                    $this->modules[$module['name']]->params = (@unserialize($module['params']) !== false) ? unserialize($module['params']) : array();
                    $this->modules[$module['name']]->route = ($module['route'] != '') ? explode(',', $module['route']) : array('__all');
                    $this->modules[$module['name']]->adm_access['rr'] = $module['rr'];
                    $this->modules[$module['name']]->adm_access['rw'] = $module['rw'];
                    $this->modules[$module['name']]->enabled = $module['enabled'];
                    $this->modules[$module['name']]->has_ui = (bool)$module['has_ui'];
                    $this->modules[$module['name']]->description = $module['description'];
                } else {
                    trigger_error("Module " . $module['name'] . " doesn't load!", E_NOTICE);
                }
            }
            return true;
        }
    }

    public function loadModules($position) {
        $modules = isset($this->__modules[$position]) ? $this->__modules[$position] : array();
        foreach ($modules as $module) {
            $this->modules[$module]->output();
        }
    }

    private function loadRouters() {
        foreach ($this->modules as $module) {
            if (in_array($_GET['route'], $module->route) || in_array('__all', $module->route)) {
                $this->url->correctUrl($_GET['_route_']);
                $module->index();
            }
        }
    }

    public function addLanguage($language) {
        $q = $this->db->query("SELECT data FROM " . DB_PREF . "settings WHERE id='1'");
        $arr = $q->row;
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
        $this->modules[$module_name]->$method();
    }

    public function doRoute() {
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
        $this->document->setMDescription($settings['site_descr_' . $_SESSION['lang']]);
        $this->document->setMKeywords($settings['site_kw_' . $_SESSION['lang']]);

        if ($_GET['route'] == 'home') {
            // starting home page from StaticPages.
            // remove code below if you don't need it
            $_GET['page_id'] = 2;
            $this->modules['staticpages']->index();
        }

        $this->loadRouters();

        // looking for 404 - parsing cURL only!
        if ((CLEAN_URL == true && $this->uri == ROOT_DIR . $_SESSION['lang'] . "/404" . PAGE_SUFFIX) ||
                (CLEAN_URL == false && SEO_MULTILANG == true && $this->uri == ROOT_DIR . "?route=404&lang=" . $_SESSION['lang']) ||
                (CLEAN_URL == false && SEO_MULTILANG == false && $this->uri == ROOT_DIR . "?route=404")) {
            $this->ERROR_404 = false;
            header("HTTP/1.1 404 Not Found");
            header("Status: 404 Not Found");
        }

        // rewrite function for 404 THIS CODE MUST BE IN THE BOTTOM OF METHOD!
        if ($this->ERROR_404) {
            $this->ERROR_404 = false;
            $this->url->is_category = false;
            $_GET['page_id'] = 4;
            $this->modules['staticpages']->index();
            header("HTTP/1.1 404 Not Found");
            header("Status: 404 Not Found");
            if (USE_404_REDIRECT) {
                if (SEO_MULTILANG == true && CLEAN_URL == false) {
                    $this->url->redirect(PRTCL . "://" . $this->host . ROOT_DIR . "?route=404&lang=" . $_SESSION['lang']);
                } elseif (CLEAN_URL == false && SEO_MULTILANG == false) {
                    $this->url->redirect(PRTCL . "://" . $this->host . ROOT_DIR . "?route=404");
                } elseif (CLEAN_URL == true) {
                    $this->url->redirect(PRTCL . "://" . $this->host . ROOT_DIR . $_SESSION['lang'] . "/404" . PAGE_SUFFIX);
                }
            }
        }
    }
}

